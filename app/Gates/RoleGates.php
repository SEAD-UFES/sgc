<?php

namespace App\Gates;

use Illuminate\Support\Facades\Gate;

class RoleGates
{
    public static function define()
    {
        Gate::define('role-list', function ($user) {
            //who can do it.
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

        Gate::define('role-show', function ($user) {
            //who can do it.
            if (Gate::forUser($user)->any(['isAdm-global', 'isDir-global', 'isAss-global', 'isSec-global', 'isCoord-global'])) {
                return true;
            }

            //no permission
            return false;
        });

        Gate::define('role-store', function ($user) {
            //who can do it.
            if (Gate::forUser($user)->any(['isAdm-global', 'isDir-global', 'isAss-global', 'isSec-global', 'isCoord-global'])) {
                return true;
            }

            //no permission
            return false;
        });

        Gate::define('role-update', function ($user) {
            //who can do it.
            if (Gate::forUser($user)->any(['isAdm-global', 'isDir-global', 'isAss-global', 'isSec-global', 'isCoord-global'])) {
                return true;
            }

            //no permission
            return false;
        });

        Gate::define('role-destroy', function ($user) {
            //who can do it.
            if (Gate::forUser($user)->any(['isAdm-global'])) {
                return true;
            }

            //no permission
            return false;
        });
    }
}
