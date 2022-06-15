<?php

namespace App\CustomClasses;

use App\Helpers\NetworkHelper;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SgcLogger
{
    /* Classe personalizada para log do sistema.

    use App\CustomClasses\SgcLogger;
    ...
    SgcLogger::writeLog();

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
    SgcLogger::writeLog(null, 'tried login', $request); [chamado do método authenticate com senha errada]
    => NoID:prof1@ufes.br|tried login|System

    SgcLogger::writeLog(); [chamando do método authenticate]
    => 7:prof1@ufes.br|authenticate|System

    SgcLogger::writeLog(); [chamando do método index do UserController]
    => 7:prof1@ufes.br|index|User

    SgcLogger::writeLog($user); [chamado do método store do UserController]
    => 7:prof1@ufes.br|store| User:18:marco@gmail.com */

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

    public static function logBadAttemptOnUri(Request $request, int $httpErrorCode)
    {
        $uri = $request->getRequestUri();
        $method = $request->getMethod();
        $executor = Auth::user();
        $executorId = $executor?->id ?? 'NoID';
        $executorEmail = isset($executor->email) ? ':' . $executor->email : ":\t";
        $executorRole = $executor->getCurrentUta()->userType->name ?? 'NULL UTA';

        $logText = "\t" . NetworkHelper::getClientIpAddress() . "\t|\t${executorId}${executorEmail} [${executorRole}]\t|\tattempted " . $method . " on '" . $uri . "' with result " . $httpErrorCode;

        Log::warning($logText);
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
        $executorRole = ($_executor instanceof User) ? ((! is_null($_executor->getCurrentUta())) ? $_executor->getCurrentUta()->userType->name : 'Null Current UTA') : 'NoRole';

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
