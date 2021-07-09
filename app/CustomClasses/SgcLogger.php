<?php

namespace App\CustomClasses;

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


    public static function writeLog(mixed $target = null, mixed $action = null, mixed $executor = null)
    {
        if ($executor == null) {
            $executor = Auth::user();
        }

        if (isset($executor->id))
            $strId = $executor->id;
        else if (is_string($executor))
            $strId = $executor;
        else $strId = 'NoID';

        if (isset($executor->email))
            $strEMail = ':' . $executor->email;
        else
            $strEMail = '';

        if ($action == null)
            $strAct = (debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2)[1]['function']);
        else if (is_string($action))
            $strAct = $action;
        else $strAct = 'No Action';

        $strTgtClass = '';
        $strTgtEmail = '';
        if ($target == null)
            $strTgt = 'System';
        else if (isset($target->id)) {
            $strTgtClass = class_basename(get_class($target));
            $strTgt = ':' . $target->id;
            if (isset($target->email))
                $strTgtEmail = ':' . $target->email;
        } else if (is_string($target))
            $strTgt = $target;
        else $strTgt = 'No Target';

        $severityMapping = [
            'tried login' => 'notice',
            'authenticate' => 'info',
            'logout' => 'info',
            'index' => 'info',
            'create' => 'info',
            'store' => 'notice',
            'Updated existent Employee info on User' => 'warning',
            'updated user' => 'notice',
            'show' => 'info',
            'edit' => 'info',
            'update' => 'notice',
            'destroy' => 'warning',
        ];

        switch ($severityMapping[$strAct]) {
            case 'info':
                Log::info("\t" . $strId . $strEMail . "\t|\t" . $strAct . "\t|\t" . $strTgtClass . $strTgt . $strTgtEmail . "\t");
                break;
            case 'notice':
                Log::notice("\t" . $strId . $strEMail . "\t|\t" . $strAct . "\t|\t" . $strTgtClass . $strTgt . $strTgtEmail . "\t");
                break;
            case 'warning':
                Log::warning("\t" . $strId . $strEMail . "\t|\t" . $strAct . "\t|\t" . $strTgtClass . $strTgt . $strTgtEmail . "\t");
                break;
            case 'error':
                Log::error("\t" . $strId . $strEMail . "\t|\t" . $strAct . "\t|\t" . $strTgtClass . $strTgt . $strTgtEmail . "\t");
                break;
            case 'critical':
                Log::critical("\t" . $strId . $strEMail . "\t|\t" . $strAct . "\t|\t" . $strTgtClass . $strTgt . $strTgtEmail . "\t");
                break;
            case 'alert':
                Log::alert("\t" . $strId . $strEMail . "\t|\t" . $strAct . "\t|\t" . $strTgtClass . $strTgt . $strTgtEmail . "\t");
                break;
            case 'emergency':
                Log::emergency("\t" . $strId . $strEMail . "\t|\t" . $strAct . "\t|\t" . $strTgtClass . $strTgt . $strTgtEmail . "\t");
                break;
            default:
                Log::debug("\t" . $strId . $strEMail . "\t|\t" . $strAct . "\t|\t" . $strTgtClass . $strTgt . $strTgtEmail . "\t");
        }
    }
}
