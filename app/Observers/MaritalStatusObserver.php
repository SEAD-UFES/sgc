<?php

namespace App\Observers;

use App\CustomClasses\SgcLogger;
use App\Models\MaritalStatus;

class MaritalStatusObserver
{
    /**
     * Handle the MaritalStatus "created" event.
     *
     * @param  \App\Models\MaritalStatus  $maritalStatus
     * @return void
     */
    public function created(MaritalStatus $maritalStatus)
    {
        SgcLogger::writeLog(target: 'MaritalStatus', action: __FUNCTION__, model: $maritalStatus);
    }

    /**
     * Handle the MaritalStatus "updated" event.
     *
     * @param  \App\Models\MaritalStatus  $maritalStatus
     * @return void
     */
    public function updating(MaritalStatus $maritalStatus)
    {
        SgcLogger::writeLog(target: 'MaritalStatus', action: __FUNCTION__, model: $maritalStatus);
    }

    /**
     * Handle the MaritalStatus "updated" event.
     *
     * @param  \App\Models\MaritalStatus  $maritalStatus
     * @return void
     */
    public function updated(MaritalStatus $maritalStatus)
    {
        SgcLogger::writeLog(target: 'MaritalStatus', action: __FUNCTION__, model: $maritalStatus);
    }

    /**
     * Handle the MaritalStatus "deleted" event.
     *
     * @param  \App\Models\MaritalStatus  $maritalStatus
     * @return void
     */
    public function deleted(MaritalStatus $maritalStatus)
    {
        SgcLogger::writeLog(target: 'MaritalStatus', action: __FUNCTION__, model: $maritalStatus);
    }

    /**
     * Handle the MaritalStatus "restored" event.
     *
     * @param  \App\Models\MaritalStatus  $maritalStatus
     * @return void
     */
    public function restored(MaritalStatus $maritalStatus)
    {
        //
    }

    /**
     * Handle the MaritalStatus "force deleted" event.
     *
     * @param  \App\Models\MaritalStatus  $maritalStatus
     * @return void
     */
    public function forceDeleted(MaritalStatus $maritalStatus)
    {
        //
    }

    public function listed()
    {
        SgcLogger::writeLog(target: 'MaritalStatus', action: __FUNCTION__);
    }

    public function viewed(MaritalStatus $approved)
    {
        SgcLogger::writeLog(target: 'MaritalStatus', action: __FUNCTION__, model: $approved);
    }
}
