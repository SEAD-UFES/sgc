<?php

namespace App\Observers;

use App\CustomClasses\SgcLogger;
use App\Models\UserType;
use PhpOffice\PhpSpreadsheet\Calculation\Statistical\AggregateBase;

class UserTypeObserver
{
    /**
     * Handle the UserType "created" event.
     *
     * @param  \App\Models\UserType  $userType
     * @return void
     */
    public function created(UserType $userType)
    {
        SgcLogger::writeLog(target: 'UserType', action: 'created', model: $userType);
    }

    /**
     * Handle the UserType "updated" event.
     *
     * @param  \App\Models\UserType  $userType
     * @return void
     */
    public function updated(UserType $userType)
    {
        SgcLogger::writeLog(target: 'UserType', action: 'updated', model: $userType);
    }

    /**
     * Handle the UserType "deleted" event.
     *
     * @param  \App\Models\UserType  $userType
     * @return void
     */
    public function deleted(UserType $userType)
    {
        SgcLogger::writeLog(target: 'UserType', action: 'deleted', model: $userType);
    }

    /**
     * Handle the UserType "restored" event.
     *
     * @param  \App\Models\UserType  $userType
     * @return void
     */
    public function restored(UserType $userType)
    {
        //
    }

    /**
     * Handle the UserType "force deleted" event.
     *
     * @param  \App\Models\UserType  $userType
     * @return void
     */
    public function forceDeleted(UserType $userType)
    {
        //
    }
}
