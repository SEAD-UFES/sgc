<?php

namespace App\Observers;

use App\Helpers\SgcLogHelper;
use App\Models\User;

class UserObserver
{
    /**
     * Handle the User "created" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function created(User $user)
    {
        SgcLogHelper::writeLog(target: 'User', action: __FUNCTION__, model_json: $user->toJson(JSON_UNESCAPED_UNICODE));
    }

    /**
     * Handle the User "updated" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function updating(User $user)
    {
        SgcLogHelper::writeLog(target: 'User', action: __FUNCTION__, model_json: json_encode($user->getOriginal(), JSON_UNESCAPED_UNICODE));
    }

    /**
     * Handle the User "updated" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function updated(User $user)
    {
        SgcLogHelper::writeLog(target: 'User', action: __FUNCTION__, model_json: $user->toJson(JSON_UNESCAPED_UNICODE));
    }

    /**
     * Handle the User "deleted" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function deleted(User $user)
    {
        SgcLogHelper::writeLog(target: 'User', action: __FUNCTION__, model_json: $user->toJson(JSON_UNESCAPED_UNICODE));
    }

    /**
     * Handle the User "restored" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function restored(User $user)
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function forceDeleted(User $user)
    {
        //
    }

    public function listed()
    {
        SgcLogHelper::writeLog(target: 'User', action: __FUNCTION__);
    }

    public function fetched(User $user)
    {
        SgcLogHelper::writeLog(target: 'User', action: __FUNCTION__, model_json: $user->toJson(JSON_UNESCAPED_UNICODE));
    }
}
