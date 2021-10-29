<?php

namespace App\Observers;

use App\CustomClasses\SgcLogger;
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
        SgcLogger::writeLog(target: 'User', action: __FUNCTION__, model: $user);
    }

    /**
     * Handle the User "updated" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function updating(User $user)
    {
        SgcLogger::writeLog(target: 'User', action: __FUNCTION__, model: $user);
    }

    /**
     * Handle the User "updated" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function updated(User $user)
    {
        SgcLogger::writeLog(target: 'User', action: __FUNCTION__, model: $user);
    }

    /**
     * Handle the User "deleted" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function deleted(User $user)
    {
        SgcLogger::writeLog(target: 'User', action: __FUNCTION__, model: $user);
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
        SgcLogger::writeLog(target: 'User', action: __FUNCTION__);
    }

    public function fetched(User $user)
    {
        SgcLogger::writeLog(target: 'User', action: __FUNCTION__, model: $user);
    }
}
