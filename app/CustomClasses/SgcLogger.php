<?php

namespace App\CustomClasses;

use Illuminate\Database\Eloquent\Model;
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

    public static function writeLog(mixed $target = null, mixed $action = null, mixed $executor = null, mixed $request = null, mixed $model = null)
    {
        $functionCaller = (debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2)[1]['function']);
        $executorInfo = self::getExecutorInfo($executor);
        $actionInfo = self::getActionInfo($action, $functionCaller);
        $targetInfo = self::getTargetInfo($target);
        $severity = self::getSeverityMapping($actionInfo);

        $logText = "\t$executorInfo\t|\t$actionInfo\t|\t$targetInfo\t";

        if ($request) {
            $logText .= "|\trequest-params: " . self::getRequestParams($request);
        }

        if ($model) {
            $logText .= "|\tmodel-before-change: " . self::getCurrentModelData($model);
        }

        switch ($severity) {
            case 'info':
                Log::info($logText);
                break;

            case 'notice':
                Log::notice($logText);
                break;

            case 'warning':
                Log::warning($logText);
                break;

            case 'error':
                Log::error($logText);
                break;

            case 'critical':
                Log::critical($logText);
                break;

            case 'alert':
                Log::alert($logText);
                break;

            case 'emergency':
                Log::emergency($logText);
                break;

            default:
                Log::info($logText);
        }
    }

    private static function getExecutorInfo($executor): string
    {
        if (is_string($executor)) {
            return $executor;
        }

        if (is_int($executor)) {
            return "NoID";
        }

        $_executor = $executor ?? Auth::user();
        $executorId = $_executor->id ?? "NoID";
        $executorEmail = ($_executor->email) ? (':' . $_executor->email) : '';

        return "$executorId$executorEmail";
    }

    private static function getActionInfo($action, $functionCaller): string
    {
        if ($action == null) {
            return $functionCaller;
        }

        if (is_string($action)) {
            return $action;
        }

        return "No Action";
    }

    private static function getTargetInfo($target): string
    {
        if ($target == null) {
            return "System";
        }

        if (is_string($target)) {
            return $target;
        }

        if (isset($target->id)) {
            $targetId = ':' . $target->id;
            $targetClass = class_basename(get_class($target));
            $targetEmail = ($target->email) ? (':' . $target->email) : '';

            return "$targetClass$targetId$targetEmail";
        }

        return 'Maybe there is something wrong with $target on logger';
    }

    private static function getCurrentModelData(Model $model): string
    {
        return $model->toJson(JSON_UNESCAPED_UNICODE);
    }

    private static function getRequestParams(array $request): string
    {
        $json = collect($request)
            ->toJson(JSON_UNESCAPED_UNICODE);

        return $json;
    }

    private static function getSeverityMapping(string $severityKey): string | null
    {
        $severityMapping = collect([
            'tried login' => 'notice',
            'authenticate' => 'info',
            'logout' => 'info',
            'index' => 'info',
            'create' => 'info',
            'designate' => 'info',
            'import' => 'info',
            'store' => 'notice',
            'Updated existent Employee info on User' => 'warning',
            'updated user' => 'notice',
            'show' => 'info',
            'edit' => 'info',
            'review' => 'info',
            'request review' => 'info',
            'update' => 'notice',
            'getAllDocumentsOfBond' => 'notice',
            'getAllDocumentsOfEmployee' => 'notice',
            'destroy' => 'warning',
        ]);

        return $severityMapping->get($severityKey);
    }

}
