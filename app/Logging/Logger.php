<?php

namespace App\Logging;

use App\Helpers\NetworkHelper;
use App\Models\Document;
use App\Models\Responsibility;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Spatie\Activitylog\Models\Activity;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Logger implements LoggerInterface
{
    public static function writeLog(mixed $target = null, mixed $action = null, mixed $executor = null, mixed $request = null, ?string $model_json = null): void
    {
        $functionCaller = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2)[1]['function'];
        $executorInfo = self::getExecutorInfo($executor);
        $actionInfo = self::getActionInfo($action, $functionCaller);
        $targetInfo = self::getTargetInfo($target);
        $severity = self::severityMap($actionInfo);

        $logText = implode("\t|\t", [
            NetworkHelper::getClientIpAddress(),
            $executorInfo,
            $actionInfo,
            $targetInfo,
        ]);

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
        $executorUserTypeName = 'UnknownCurrentResponsibility';

        $httpErrorCode = $exception->getStatusCode();
        $uri = $request->getRequestUri();
        $method = $request->getMethod();

        $user = Auth::user();
        $userEmail = optional($user)->login;
        $executorLabel = $userEmail ?? request()->input('email') ?? request()->ip();
        $executorId = optional($user)->id ?? '-';

        $executorCurrentResponsibility = session('loggedInUser.currentResponsibility');
        $executorUserTypeName = $executorCurrentResponsibility?->userType->name ?? 'NullCurrentResponsibility';

        $logText = "\tHTTP_ERROR\t| " . NetworkHelper::getClientIpAddress() . "\t| {$executorId}:{$executorLabel} [{$executorUserTypeName}]\t| attempted {$method} on '{$uri}' with result {$httpErrorCode}";

        Log::warning($logText);
    }

    public static function logModelEvent(Activity|string $activity, Model|string|null $model = null): void
    {
        $executor = null;
        $executorId = '-';
        $executorLabel = 'UnknownUser';
        $executorUserTypeName = 'UnknownCurrentResponsibility';
        $activityLabel = 'UnknownActivity';
        $modelClassLabel = 'UnknownModel';
        $modelId = 'UnknownModelID';
        $modelAttributes = '{?}';

        if (Auth::user() instanceof User) {
            /** @var User $executor */
            $executor = Auth::user();
            $executorId = $executor->id;
            $executorLabel = $executor->login;
            /** @var Responsibility|null $executorCurrentResponsibility */
            $executorCurrentResponsibility = session('loggedInUser.currentResponsibility');
            $executorUserTypeName = $executorCurrentResponsibility?->userType->name ?? 'NullCurrentResponsibility';
        } else {
            $executorLabel = request()->ip() . ' (Seed?)';
        }

        if (isset($activity->causer) && $activity->causer instanceof User) {
            /** @var User $executor */
            $executor = $activity->causer;
            $executorId = $executor->id;
            $executorLabel = $executor->login;
            /** @var Responsibility|null $executorCurrentResponsibility */
            $executorCurrentResponsibility = session('loggedInUser.currentResponsibility');
            $executorUserTypeName = $executorCurrentResponsibility?->userType->name ?? 'NullCurrentResponsibility';
        }

        if (is_string($activity)) {
            $activityLabel = $activity;
        }

        if (isset($model) && (! is_string($model)) && $model instanceof \Illuminate\Database\Eloquent\Model) {
            $modelClassLabel = class_basename($model);
            $modelId = ':' . $model->getKey();
            $originalAttributes = $model->getOriginal();
            Arr::forget($originalAttributes, ['password', 'remember_token']);
            $modelAttributes = ':' . json_encode($originalAttributes, JSON_UNESCAPED_UNICODE);
        }

        if (isset($model) && is_string($model)) {
            $modelClassLabel = class_basename($model);
            $modelId = '';
            $modelAttributes = '';
        }

        if ($activity instanceof Activity) {
            $activityLabel = $activity->description;
            if ($activity->subject_type !== null) {
                $modelClassLabel = class_basename($activity->subject_type);
            }

            $modelId = ':' . $activity->subject_id;
            $modelAttributes = ':' . $activity->changes;
        }

        if (isset($model) && (! is_string($model)) && ($model::class === Document::class) && $activityLabel === 'read' && (is_string($modelAttributes))) {
            $modelAttributes = json_decode($modelAttributes, true);
            $modelAttributes = ':' . json_encode($modelAttributes, JSON_UNESCAPED_UNICODE);
        }

        if ($activity === 'listed') {
            $modelId = '';
            $modelAttributes = '';
        }

        $logText = "\tMODEL_EVENT\t| " . NetworkHelper::getClientIpAddress() . "\t| {$executorId}:{$executorLabel} [{$executorUserTypeName}]\t| [" . $activityLabel . "]\t| Model:" . $modelClassLabel . $modelId . $modelAttributes;
        Log::info($logText);
    }

    public static function logDomainEvent(string $event, ?Model $model = null): void
    {
        /** @var User|null $user */
        $user = Auth::user();
        $executorId = '-';
        $executorLabel = 'UnknownUser';
        $executorUserTypeName = 'UnknownCurrentResponsibility';
        $modelClassLabel = 'UnknownModel';
        $modelId = 'UnknownModelID';
        $modelAttributes = '{?}';

        if ($user instanceof \App\Models\User) {
            $executorId = $user->id;
            $executorLabel = $user->login ?? $executorLabel;
            $executorUserTypeName = session('loggedInUser.currentResponsibility')?->userType->name ?? $executorUserTypeName;
        } elseif (request()->filled('email')) {
            $executorLabel = request()->email;
        } else {
            $executorLabel = request()->ip();
        }

        if ($model instanceof \Illuminate\Database\Eloquent\Model) {
            $modelClassLabel = class_basename($model);
            $modelId = $model->getKey();
            $modelAttributes = json_encode($model->getOriginal(), JSON_UNESCAPED_UNICODE);
        }

        $logMessage = "\tDOMAIN_EVENT\t| " . NetworkHelper::getClientIpAddress() . "\t| {$executorId}:{$executorLabel} [{$executorUserTypeName}]\t| [" . $event . "]\t| Model:" . $modelClassLabel . ':' . $modelId . ':' . $modelAttributes;

        Log::info($logMessage);
    }

    public static function logSystemEvent(string $event, Model|string|null $model = null): void
    {
        $executorId = '-';
        $executorLabel = 'UnknownUser';
        $executorUserTypeName = 'UnknownCurrentResponsibility';
        $modelClassLabel = 'UnknownModel';
        $modelId = 'UnknownModelID';
        $modelAttributes = '{?}';

        $user = Auth::user();
        $executorLabel = $user?->login ?? request()->login ?? request()->ip() ?? '';
        $executorId = $user?->id ?? '-';

        $currentResponsibility = session('loggedInUser.currentResponsibility');
        $executorUserTypeName = $currentResponsibility?->userType->name ?? 'NullCurrentResponsibility';

        if ($model instanceof \Illuminate\Database\Eloquent\Model) {
            $modelClassLabel = class_basename($model);
            $modelId = $model->getKey();
            $originalAttributes = $model->getOriginal();
            Arr::forget($originalAttributes, ['password', 'remember_token']);
            $modelAttributes = json_encode($originalAttributes, JSON_UNESCAPED_UNICODE);
        } elseif (is_string($model)) {
            $modelAttributes = $model;
        }

        $logMessage = "\tSYSTEM_EVENT\t| " . NetworkHelper::getClientIpAddress() . "\t| {$executorId}:{$executorLabel} [{$executorUserTypeName}]\t| [" . $event . "]\t| Model:" . $modelClassLabel . ':' . $modelId . ':' . $modelAttributes;

        Log::info($logMessage);
    }

    private static function getExecutorInfo(mixed $executor): string
    {
        if (is_string($executor)) {
            return $executor;
        }

        if (is_int($executor)) {
            return 'NoID';
        }

        $loggedInUser = Auth::user();

        if (! $loggedInUser instanceof User) {
            return 'NoRole';
        }

        $executorId = $loggedInUser->id ?? 'NoID';
        $executorEmail = $loggedInUser->login ?? '';
        $executorEmailPart = $executorEmail ? ":{$executorEmail}" : '';
        $executorRole = session()->has('loggedInUser.currentResponsibility')
            ? session('loggedInUser.currentResponsibility')->userType->name
            : 'NoRole';

        return "{$executorId}{$executorEmailPart} [{$executorRole}]";
    }

    private static function getActionInfo(mixed $action, mixed $functionCaller): string
    {
        return $action !== null ? (is_string($action) ? $action : 'No Action') : $functionCaller;
    }

    private static function getTargetInfo(mixed $target): string
    {
        if ($target === null) {
            return 'System';
        }

        if (is_string($target)) {
            return $target;
        }

        if (is_object($target) && property_exists($target, 'id')) {
            $targetId = ':' . $target->id;
            $targetClass = class_basename($target);
            $targetEmail = method_exists($target, 'getEmail') ? ':' . $target->getEmail() : '';

            return "{$targetClass}{$targetId}{$targetEmail}";
        }

        return 'Maybe there is something wrong with $target on logger';
    }

    private static function getRequestParams(Request $request): string
    {
        return collect($request)
            ->toJson(JSON_UNESCAPED_UNICODE);
    }

    private static function severityMap(string $action): string
    {
        $severityMap = [
            'info' => [
                'authenticate', 'logout', 'index', 'listed', 'create',
                'created', 'designate', 'import', 'show', 'fetched',
                'edit', 'review', 'request review',
            ],
            'notice' => [
                'tried login', 'store', 'updated user', 'updating',
                'update', 'updated', 'exportDocuments', 'dismiss',
            ],
            'warning' => [
                'Updated existent Employee info on User', 'destroy', 'deleted',
            ],
        ];

        foreach ($severityMap as $severity => $actions) {
            if (in_array($action, $actions)) {
                return $severity;
            }
        }

        return 'info';
    }
}
