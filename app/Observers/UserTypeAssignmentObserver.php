<?php

namespace App\Observers;

use App\Helpers\SgcLogHelper;
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
        SgcLogHelper::writeLog(target: 'UserTypeAssignment', action: __FUNCTION__, model_json: $userTypeAssignment->toJson(JSON_UNESCAPED_UNICODE));
    }

    /**
     * Handle the UserTypeAssignment "updated" event.
     *
     * @param  \App\Models\UserTypeAssignment  $userTypeAssignment
     * @return void
     */
    public function updating(UserTypeAssignment $userTypeAssignment)
    {
        SgcLogHelper::writeLog(target: 'UserTypeAssignment', action: __FUNCTION__, model_json: json_encode($userTypeAssignment->getOriginal(), JSON_UNESCAPED_UNICODE));
    }

    /**
     * Handle the UserTypeAssignment "updated" event.
     *
     * @param  \App\Models\UserTypeAssignment  $userTypeAssignment
     * @return void
     */
    public function updated(UserTypeAssignment $userTypeAssignment)
    {
        SgcLogHelper::writeLog(target: 'UserTypeAssignment', action: __FUNCTION__, model_json: $userTypeAssignment->toJson(JSON_UNESCAPED_UNICODE));
    }

    /**
     * Handle the UserTypeAssignment "deleted" event.
     *
     * @param  \App\Models\UserTypeAssignment  $userTypeAssignment
     * @return void
     */
    public function deleted(UserTypeAssignment $userTypeAssignment)
    {
        SgcLogHelper::writeLog(target: 'UserTypeAssignment', action: __FUNCTION__, model_json: $userTypeAssignment->toJson(JSON_UNESCAPED_UNICODE));
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
        SgcLogHelper::writeLog(target: 'UserTypeAssignment', action: __FUNCTION__);
    }

    public function fetched(UserTypeAssignment $userTypeAssignment)
    {
        SgcLogHelper::writeLog(target: 'UserTypeAssignment', action: __FUNCTION__, model_json: $userTypeAssignment->toJson(JSON_UNESCAPED_UNICODE));
    }
}
