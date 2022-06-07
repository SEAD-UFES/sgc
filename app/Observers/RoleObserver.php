<?php

namespace App\Observers;

use App\CustomClasses\SgcLogger;
use App\Models\Role;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

class RoleObserver
{
    /**
     * Handle the Role "created" event.
     *
     * @param  \App\Models\Role  $role
     * @return void
     */
    public function created(Role $role)
    {
        SgcLogger::writeLog(target: 'Role', action: __FUNCTION__, model_json: $role->toJson(JSON_UNESCAPED_UNICODE));
    }

    /**
     * Handle the Role "updated" event.
     *
     * @param  \App\Models\Role  $role
     * @return void
     */
    public function updating(Role $role)
    {
        SgcLogger::writeLog(target: 'Role', action: __FUNCTION__, model_json: json_encode($role->getOriginal(), JSON_UNESCAPED_UNICODE));
    }

    /**
     * Handle the Role "updated" event.
     *
     * @param  \App\Models\Role  $role
     * @return void
     */
    public function updated(Role $role)
    {
        SgcLogger::writeLog(target: 'Role', action: __FUNCTION__, model_json: $role->toJson(JSON_UNESCAPED_UNICODE));
    }

    /**
     * Handle the Role "deleted" event.
     *
     * @param  \App\Models\Role  $role
     * @return void
     */
    public function deleted(Role $role)
    {
        SgcLogger::writeLog(target: 'Role', action: __FUNCTION__, model_json: $role->toJson(JSON_UNESCAPED_UNICODE));
    }

    /**
     * Handle the Role "restored" event.
     *
     * @param  \App\Models\Role  $role
     * @return void
     */
    public function restored(Role $role)
    {
        //
    }

    /**
     * Handle the Role "force deleted" event.
     *
     * @param  \App\Models\Role  $role
     * @return void
     */
    public function forceDeleted(Role $role)
    {
        //
    }

    public function listed()
    {
        SgcLogger::writeLog(target: 'Role', action: __FUNCTION__);
    }

    public function fetched(Role $role)
    {
        SgcLogger::writeLog(target: 'Role', action: __FUNCTION__, model_json: $role->toJson(JSON_UNESCAPED_UNICODE));
    }
}
