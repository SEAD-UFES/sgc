<?php

namespace App\Observers;

use App\CustomClasses\SgcLogger;
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
        SgcLogger::writeLog(target: 'EmployeeDocument', action: __FUNCTION__, model: $employeeDocument);
    }

    /**
     * Handle the EmployeeDocument "updated" event.
     *
     * @param  \App\Models\EmployeeDocument  $employeeDocument
     * @return void
     */
    public function updating(EmployeeDocument $employeeDocument)
    {
        SgcLogger::writeLog(target: 'EmployeeDocument', action: __FUNCTION__, model: $employeeDocument);
    }

    /**
     * Handle the EmployeeDocument "updated" event.
     *
     * @param  \App\Models\EmployeeDocument  $employeeDocument
     * @return void
     */
    public function updated(EmployeeDocument $employeeDocument)
    {
        SgcLogger::writeLog(target: 'EmployeeDocument', action: __FUNCTION__, model: $employeeDocument);
    }

    /**
     * Handle the EmployeeDocument "deleted" event.
     *
     * @param  \App\Models\EmployeeDocument  $employeeDocument
     * @return void
     */
    public function deleted(EmployeeDocument $employeeDocument)
    {
        SgcLogger::writeLog(target: 'EmployeeDocument', action: __FUNCTION__, model: $employeeDocument);
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
        SgcLogger::writeLog(target: 'EmployeeDocument', action: __FUNCTION__);
    }

    public function fetched(EmployeeDocument $approved)
    {
        SgcLogger::writeLog(target: 'EmployeeDocument', action: __FUNCTION__, model: $approved);
    }
}