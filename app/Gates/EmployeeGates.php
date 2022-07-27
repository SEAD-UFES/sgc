<?php

namespace App\Gates;

use Illuminate\Support\Facades\Gate;

class EmployeeGates
{
    public static function define()
    {
        Gate::define('employee-list', static function ($user) {
            //who can do it (global).
            if (Gate::forUser($user)->any(['isAdm-global', 'isDir-global', 'isAss-global', 'isSec-global'])) {
                return true;
            }
            //coord on any course or global
            if (Gate::forUser($user)->any(['isCoord'])) {
                return true;
            }
            //no permission
            return false;
        });

        Gate::define('employee-show', static function ($user) {
            //who can do it (global).
            if (Gate::forUser($user)->any(['isAdm-global', 'isDir-global', 'isAss-global', 'isSec-global'])) {
                return true;
            }
            //no permission
            return false;
        });

        Gate::define('employee-store', static function ($user) {
            //who can do it (global).
            if (Gate::forUser($user)->any(['isAdm-global', 'isDir-global', 'isAss-global', 'isSec-global'])) {
                return true;
            }
            //no permission
            return false;
        });

        Gate::define('employee-update', static function ($user) {
            //who can do it (global).
            if (Gate::forUser($user)->any(['isAdm-global', 'isDir-global', 'isAss-global', 'isSec-global'])) {
                return true;
            }
            //no permission
            return false;
        });

        Gate::define('employee-destroy', static function ($user) {
            //who can do it (global).
            if (Gate::forUser($user)->any(['isAdm-global'])) {
                return true;
            }
            //no permission
            return false;
        });
    }
}
