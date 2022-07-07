<?php

namespace App\Observers;

use App\Helpers\SgcLogHelper;
use App\Models\EmployeeDocument;

class EmployeeDocumentObserver
{
    /**
     * Handle the EmployeeDocument "created" event.
     *
     * @param  \App\Models\EmployeeDocument  $employeeDocument
     * @return void
     */
    public function created(EmployeeDocument $employeeDocument)
    {
        SgcLogHelper::writeLog(target: 'EmployeeDocument', action: __FUNCTION__, model_json: $employeeDocument->toJson(JSON_UNESCAPED_UNICODE));
    }

    /**
     * Handle the EmployeeDocument "updated" event.
     *
     * @param  \App\Models\EmployeeDocument  $employeeDocument
     * @return void
     */
    public function updating(EmployeeDocument $employeeDocument)
    {
        SgcLogHelper::writeLog(target: 'EmployeeDocument', action: __FUNCTION__, model_json: json_encode($employeeDocument->getOriginal(), JSON_UNESCAPED_UNICODE));
    }

    /**
     * Handle the EmployeeDocument "updated" event.
     *
     * @param  \App\Models\EmployeeDocument  $employeeDocument
     * @return void
     */
    public function updated(EmployeeDocument $employeeDocument)
    {
        SgcLogHelper::writeLog(target: 'EmployeeDocument', action: __FUNCTION__, model_json: $employeeDocument->toJson(JSON_UNESCAPED_UNICODE));
    }

    /**
     * Handle the EmployeeDocument "deleted" event.
     *
     * @param  \App\Models\EmployeeDocument  $employeeDocument
     * @return void
     */
    public function deleted(EmployeeDocument $employeeDocument)
    {
        SgcLogHelper::writeLog(target: 'EmployeeDocument', action: __FUNCTION__, model_json: $employeeDocument->toJson(JSON_UNESCAPED_UNICODE));
    }

    /**
     * Handle the EmployeeDocument "restored" event.
     *
     * @param  \App\Models\EmployeeDocument  $employeeDocument
     * @return void
     */
    public function restored(EmployeeDocument $employeeDocument)
    {
        //
    }

    /**
     * Handle the EmployeeDocument "force deleted" event.
     *
     * @param  \App\Models\EmployeeDocument  $employeeDocument
     * @return void
     */
    public function forceDeleted(EmployeeDocument $employeeDocument)
    {
        //
    }

    public function listed()
    {
        SgcLogHelper::writeLog(target: 'EmployeeDocument', action: __FUNCTION__);
    }

    public function fetched(EmployeeDocument $employeeDocument)
    {
        SgcLogHelper::writeLog(target: 'EmployeeDocument', action: __FUNCTION__, model_json: $employeeDocument->toJson(JSON_UNESCAPED_UNICODE));
    }
}
