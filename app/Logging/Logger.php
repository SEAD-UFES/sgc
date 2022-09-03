<?php

namespace App\Logging;

use App\Helpers\NetworkHelper;
use App\Models\Document;
use App\Models\User;
use App\Models\UserTypeAssignment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Spatie\Activitylog\Models\Activity;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Logger implements LoggerInterface
{
    /* Classe personalizada para log do sistema.

    use App\Logging\Logger;
    ...
    Logger::writeLog();

    O método 'writeLog' tem 3 parâmetros opcionais: $target, $action, $executor
    Assim, lendo de trás pra frente, Quem->O que->Onde

    = Target =
    Pode receber null/vazio, Classe com atributo id ou string.
    - Com null ou vazio, usa 'System'.
    - Com uma string, usa a string.
    - Com uma classe com id, usa o o nome da classe, id e email, se possuir.

    = Action =
    Pode receber null/vazio ou string.
    - Com null ou vazio, usa o nome do método que chamou o writeLog.
    - Com uma string, usa a string.

    = Executor =
    Pode receber null/vazio, Classe com atributo id ou string.
    - Com null ou vazio, usa o id e o email do usuário logado.
    - Com uma string, usa a string.
    - Com uma classe com id, usa o id e email.

    Ex.:
    Logger::writeLog(null, 'tried login', $request); [chamado do método authenticate com senha errada]
    => NoID:prof1@ufes.br|tried login|System

    Logger::writeLog(); [chamando do método authenticate]
    => 7:prof1@ufes.br|authenticate|System

    Logger::writeLog(); [chamando do método index do UserController]
    => 7:prof1@ufes.br|index|User

    Logger::writeLog($user); [chamado do método store do UserController]
    => 7:prof1@ufes.br|store| User:18:marco@gmail.com */

    /*========================================================================================================
    - Model Events
        ** retrieved **
        creating
        created
        updating
        updated
        saving
        saved
        deleting
        deleted
        restoring
        restored

    - HTTP Access Error Events
        401
        403
        404

    - Domain Events
        designate
        review
        request review

    - System Events
        authenticate
        logout
        import
        exportBondDocuments
        exportEmployeeDocuments

    ========================================================================================================*/

    /**
     * @var array<int, string> $severities
     */
    private static $severities = ['info', 'notice', 'warning', 'error', 'critical', 'alert', 'emergency'];

    public static function writeLog(mixed $target = null, mixed $action = null, mixed $executor = null, mixed $request = null, ?string $model_json = null)
    {
        $functionCaller = (debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2)[1]['function']);
        $executorInfo = self::getExecutorInfo($executor);
        $actionInfo = self::getActionInfo($action, $functionCaller);
        $targetInfo = self::getTargetInfo($target);
        $severity = self::severityMap($actionInfo);

        $logText = "\t" . NetworkHelper::getClientIpAddress() . "\t|\t${executorInfo}\t|\t${actionInfo}\t|\t${targetInfo}\t";

        if ($request) {
            $logText .= "|\trequest-params: " . self::getRequestParams($request);
        }

        if ($model_json) {
            $logText .= "|\tmodel: " . $model_json;
        }

        Log::$severity($logText);
    }

    public static function logHttpError(Request $request, HttpException $exception): void
    {
        $executorId = '-';
        $executorLabel = 'UnknownUser';
        $executorUserTypeName = 'UnknownCurrentUTA';

        $httpErrorCode = $exception->getStatusCode();
        $uri = $request->getRequestUri();
        $method = $request->getMethod();
        /**
         * @var User|null $user
         */
        $user = Auth::user();
        /**
         * @var string|null $userEmail
         */
        $userEmail = $user?->email;
        /**
         * @var string $executorLabel
         */
        $executorLabel = (($userEmail ?? request()->email) ?? request()->ip()) ?? '';
        $executorId = $user?->id ?? '-';

        $executorUserTypeName = $user?->getCurrentUta()->userType->name ?? 'NullCurrentUTA';

        $logText = "\tHTTP_ERROR\t| " . NetworkHelper::getClientIpAddress() . "\t| User:${executorId}:${executorLabel} [${executorUserTypeName}]\t| attempted " . $method . " on '" . $uri . "' with result " . $httpErrorCode;

        Log::warning($logText);
        /* Log::warning(
            'HttpException: ' . $httpErrorCode . ' Path: ' . $request->path() . "\n" . $exception::class . "\n",
            [
                'user' => auth()->user(),
                'request' => $request,
            ]
        ); */
    }

    public static function logModelEvent(Activity|string $activity, Model|string|null $model = null): void
    {
        $executor = null;
        $executorId = '-';
        $executorLabel = 'UnknownUser';
        $executorUserTypeName = 'UnknownCurrentUTA';
        $activityLabel = 'UnknownActivity';
        $modelClassLabel = 'UnknownModel';
        $modelId = 'UnknownModelID';
        $modelAttributes = '{?}';

        if (Auth::user() instanceof User) {
            /** @var User $executor */
            $executor = Auth::user();
            $executorId = $executor->id;
            $executorLabel = $executor->email;
            /** @var UserTypeAssignment|null $executorCurrentUta */
            $executorCurrentUta = $executor->getCurrentUta();
            $executorUserTypeName = $executorCurrentUta?->userType->name ?? 'NullCurrentUTA';
        } else {
            $executorLabel = request()->ip() . ' (Seed?)';
        }

        if (isset($activity->causer) && $activity->causer instanceof User) {
            /** @var User $executor */
            $executor = $activity->causer;
            $executorId = $executor->id;
            $executorLabel = $executor->email;
            /** @var UserTypeAssignment|null $executorCurrentUta */
            $executorCurrentUta = $executor->getCurrentUta();
            $executorUserTypeName = $executorCurrentUta?->userType->name ?? 'NullCurrentUTA';
        }

        if (is_string($activity)) {
            $activityLabel = $activity;
        }

        if (isset($model) && (! is_string($model)) && is_a($model, Model::class)) {
            $modelClassLabel = class_basename($model);
            $modelId = $model->getKey();
            $modelAttributes = json_encode($model->getOriginal(), JSON_UNESCAPED_UNICODE);
        }

        if (isset($model) && is_string($model)) {
            $modelClassLabel = class_basename($model);
            $modelId = '';
            $modelAttributes = '';
        }

        if ($activity instanceof Activity) {
            $activityLabel = $activity->description;

            $modelClassLabel = class_basename($activity->subject_type);
            $modelId = $activity->subject_id;
            $modelAttributes = $activity->changes;
        }

        if (isset($model) && (! is_string($model)) && ($model::class === Document::class) && $activityLabel === 'read' && (is_string($modelAttributes))) {
            $modelAttributes = json_decode($modelAttributes, true);
            Arr::forget($modelAttributes, 'file_data');
            $modelAttributes = json_encode($modelAttributes, JSON_UNESCAPED_UNICODE);
        }

        $logText = "\tMODEL_EVENT\t| " . NetworkHelper::getClientIpAddress() . "\t| User:${executorId}:${executorLabel} [${executorUserTypeName}]\t| [" . $activityLabel . "]\t| Model:" . $modelClassLabel . ':' . $modelId . ':' . $modelAttributes;
        Log::info($logText);
    }

    public static function logDomainEvent(string $event, ?Model $model = null): void
    {
        $executorId = '-';
        $executorLabel = 'UnknownUser';
        $executorUserTypeName = 'UnknownCurrentUTA';
        $modelClassLabel = 'UnknownModel';
        $modelId = 'UnknownModelID';
        $modelAttributes = '{?}';

        /**
         * @var User|null $user
         */
        $user = Auth::user();
        /**
         * @var string|null $userEmail
         */
        $userEmail = $user?->email;
        /**
         * @var string $executorLabel
         */
        $executorLabel = (($userEmail ?? request()->email) ?? request()->ip()) ?? '';
        $executorId = $user?->id ?? '-';

        $executorUserTypeName = $user?->getCurrentUta()->userType->name ?? 'NullCurrentUTA';

        if (isset($model) && is_a($model, Model::class)) {
            $modelClassLabel = class_basename($model);
            $modelId = $model->getKey();
            $modelAttributes = json_encode($model->getOriginal(), JSON_UNESCAPED_UNICODE);
        }

        $logText = "\tDOMAIN_EVENT\t| " . NetworkHelper::getClientIpAddress() . "\t| User:${executorId}:${executorLabel} [${executorUserTypeName}]\t| [" . $event . "]\t| Model:" . $modelClassLabel . ':' . $modelId . ':' . $modelAttributes;

        Log::info($logText);
    }

    public static function logSystemEvent(string $event, Model|string|null $model = null): void
    {
        $executorId = '-';
        $executorLabel = 'UnknownUser';
        $executorUserTypeName = 'UnknownCurrentUTA';
        $modelClassLabel = 'UnknownModel';
        $modelId = 'UnknownModelID';
        $modelAttributes = '{?}';

        /**
         * @var User|null $user
         */
        $user = Auth::user();
        /**
         * @var string|null $userEmail
         */
        $userEmail = $user?->email;
        /**
         * @var string $executorLabel
         */
        $executorLabel = (($userEmail ?? request()->email) ?? request()->ip()) ?? '';
        $executorId = $user?->id ?? '-';

        $executorUserTypeName = $user?->getCurrentUta()->userType->name ?? 'NullCurrentUTA';

        if (isset($model) && ! is_string($model) && is_a($model, Model::class)) {
            $modelClassLabel = class_basename($model);
            $modelId = $model->getKey();
            $modelAttributes = json_encode($model->getOriginal(), JSON_UNESCAPED_UNICODE);
        }

        if (isset($model) && is_string($model)) {
            $modelClassLabel = '';
            $modelId = '';
            $modelAttributes = $model;
        }

        $logText = "\tSYSTEM_EVENT\t| " . NetworkHelper::getClientIpAddress() . "\t| User:${executorId}:${executorLabel} [${executorUserTypeName}]\t| [" . $event . "]\t| Model:" . $modelClassLabel . ':' . $modelId . ':' . $modelAttributes;

        Log::info($logText);
    }

    private static function getExecutorInfo($executor): string
    {
        if (is_string($executor)) {
            return $executor;
        }

        if (is_int($executor)) {
            return 'NoID';
        }

        $_executor = $executor ?? Auth::user();
        $executorId = $_executor->id ?? 'NoID';
        $executorEmail = isset($_executor->email) ? ':' . $_executor->email : ":\t";
        $executorRole = $_executor instanceof User ? (is_null($_executor->getCurrentUta()) ? 'Null Current UTA' : $_executor->getCurrentUta()->userType->name) : 'NoRole';

        return "${executorId}${executorEmail} [${executorRole}]";
    }

    private static function getActionInfo($action, $functionCaller): string
    {
        if ($action === null) {
            return $functionCaller;
        }

        if (is_string($action)) {
            return $action;
        }

        return 'No Action';
    }

    private static function getTargetInfo($target): string
    {
        if ($target === null) {
            return 'System';
        }

        if (is_string($target)) {
            return $target;
        }

        if (isset($target->id)) {
            $targetId = ':' . $target->id;
            $targetClass = class_basename($target::class);
            $targetEmail = $target->email ? ':' . $target->email : '';

            return "${targetClass}${targetId}${targetEmail}";
        }

        return 'Maybe there is something wrong with $target on logger';
    }

    private static function getRequestParams(array $request): string
    {
        return collect($request)
            ->toJson(JSON_UNESCAPED_UNICODE);
    }

    /**
     * @param string $action
     *
     * @return string
     */
    private static function severityMap(string $action): string
    {
        $loggedActions = [];

        $loggedActions['info'] = [
            'authenticate',
            'logout',
            'index',
            'listed',
            'create',
            'created',
            'designate',
            'import',
            'show',
            'fetched',
            'edit',
            'review',
            'request review',
        ];

        $loggedActions['notice'] = [
            'tried login',
            'store',
            'updated user',
            'updating',
            'update',
            'updated',
            'exportBondDocuments',
            'exportEmployeeDocuments',
            'dismiss',
        ];

        $loggedActions['warning'] = [
            'Updated existent Employee info on User',
            'destroy',
            'deleted',
        ];

        foreach (self::$severities as $severity) {
            if (in_array($action, $loggedActions[$severity])) {
                return $severity;
            }
        }

        return 'info';
    }
}
