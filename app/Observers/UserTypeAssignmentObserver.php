<?php

namespace App\Observers;

use App\CustomClasses\SgcLogger;
use App\Models\UserTypeAssignment;

class UserTypeAssignmentObserver
{
    /**
     * Handle the UserTypeAssignment "created" event.
     *
     * @param  \App\Models\UserTypeAssignment  $userTypeAssignment
     * @return void
     */
    public function created(UserTypeAssignment $userTypeAssignment)
    {
        SgcLogger::writeLog(target: 'UserTypeAssignment', action: __FUNCTION__, model: $userTypeAssignment);
    }

    /**
     * Handle the UserTypeAssignment "updated" event.
     *
     * @param  \App\Models\UserTypeAssignment  $userTypeAssignment
     * @return void
     */
    public function updating(UserTypeAssignment $userTypeAssignment)
    {
        SgcLogger::writeLog(target: 'UserTypeAssignment', action: __FUNCTION__, model: $userTypeAssignment);
    }

    /**
     * Handle the UserTypeAssignment "updated" event.
     *
     * @param  \App\Models\UserTypeAssignment  $userTypeAssignment
     * @return void
     */
    public function updated(UserTypeAssignment $userTypeAssignment)
    {
        SgcLogger::writeLog(target: 'UserTypeAssignment', action: __FUNCTION__, model: $userTypeAssignment);
    }

    /**
     * Handle the UserTypeAssignment "deleted" event.
     *
     * @param  \App\Models\UserTypeAssignment  $userTypeAssignment
     * @return void
     */
    public function deleted(UserTypeAssignment $userTypeAssignment)
    {
        SgcLogger::writeLog(target: 'UserTypeAssignment', action: __FUNCTION__, model: $userTypeAssignment);
    }

    /**
     * Handle the UserTypeAssignment "restored" event.
     *
     * @param  \App\Models\UserTypeAssignment  $userTypeAssignment
     * @return void
     */
    public function restored(UserTypeAssignment $userTypeAssignment)
    {
        //
    }

    /**
     * Handle the UserTypeAssignment "force deleted" event.
     *
     * @param  \App\Models\UserTypeAssignment  $userTypeAssignment
     * @return void
     */
    public function forceDeleted(UserTypeAssignment $userTypeAssignment)
    {
        //
    }

    public function listed()
    {
        SgcLogger::writeLog(target: 'UserTypeAssignment', action: __FUNCTION__);
    }

    public function viewed(UserTypeAssignment $approved)
    {
        SgcLogger::writeLog(target: 'UserTypeAssignment', action: __FUNCTION__, model: $approved);
    }
}
