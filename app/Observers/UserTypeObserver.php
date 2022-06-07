<?php

namespace App\Observers;

use App\Models\UserType;
use App\CustomClasses\SgcLogger;
use Illuminate\Support\Facades\Log;
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
        SgcLogger::writeLog(target: 'UserType', action: __FUNCTION__, model_json: $userType->toJson(JSON_UNESCAPED_UNICODE));
    }

    /**
     * Handle the UserType "updated" event.
     *
     * @param  \App\Models\UserType  $userType
     * @return void
     */
    public function updating(UserType $userType)
    {
        SgcLogger::writeLog(target: 'UserType', action: __FUNCTION__, model_json: json_encode($userType->getOriginal(), JSON_UNESCAPED_UNICODE));
    }

    /**
     * Handle the UserType "updated" event.
     *
     * @param  \App\Models\UserType  $userType
     * @return void
     */
    public function updated(UserType $userType)
    {
        SgcLogger::writeLog(target: 'UserType', action: __FUNCTION__, model_json: $userType->toJson(JSON_UNESCAPED_UNICODE));
    }

    /**
     * Handle the UserType "deleted" event.
     *
     * @param  \App\Models\UserType  $userType
     * @return void
     */
    public function deleted(UserType $userType)
    {
        SgcLogger::writeLog(target: 'UserType', action: __FUNCTION__, model_json: $userType->toJson(JSON_UNESCAPED_UNICODE));
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

    public function listed()
    {
        SgcLogger::writeLog(target: 'UserType', action: __FUNCTION__);
    }

    public function fetched(UserType $userType)
    {
        SgcLogger::writeLog(target: 'UserType', action: __FUNCTION__, model_json: $userType->toJson(JSON_UNESCAPED_UNICODE));
    }
}
