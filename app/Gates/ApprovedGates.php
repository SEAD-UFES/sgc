<?php

namespace App\Gates;

use Illuminate\Support\Facades\Gate;

class ApprovedGates
{
    public static function define()
    {
        Gate::define('approved-list', function ($user) {
            //who can do it (global).
            if (Gate::forUser($user)->any(['isAdm-global', 'isDir-global', 'isSec-global'])) {
                return true;
            }

            //coord on any course or global
            if (Gate::forUser($user)->any(['isCoord'])) {
                return true;
            }

            //no permission
            return false;
        });

        Gate::define('approved-store', function ($user) {
            //who can do it (global).
            if (Gate::forUser($user)->any(['isAdm-global', 'isDir-global', 'isSec-global'])) {
                return true;
            }

            //no permission
            return false;
        });

        Gate::define('approved-update-state', function ($user) {
            //who can do it (global).
            if (Gate::forUser($user)->any(['isAdm-global', 'isDir-global', 'isSec-global'])) {
                return true;
            }

            //no permission
            return false;
        });

        Gate::define('approved-destroy', function ($user) {
            //who can do it (global).
            if (Gate::forUser($user)->any(['isAdm-global', 'isDir-global', 'isSec-global'])) {
                return true;
            }

            //no permission
            return false;
        });

        Gate::define('approved-designate', function ($user) {
            //who can do it (global).
            if (Gate::forUser($user)->any(['isAdm-global', 'isDir-global', 'isSec-global'])) {
                return true;
            }

            //no permission
            return false;
        });
    }
}
