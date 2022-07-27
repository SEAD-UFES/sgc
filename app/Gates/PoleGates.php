<?php

namespace App\Gates;

use Illuminate\Support\Facades\Gate;

class PoleGates
{
    public static function define()
    {
        Gate::define('pole-list', static function ($user) {
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

        Gate::define('pole-show', static function ($user) {
            //who can do it.
            if (Gate::forUser($user)->any(['isAdm-global', 'isDir-global', 'isAss-global', 'isSec-global'])) {
                return true;
            }
            //no permission
            return false;
        });

        Gate::define('pole-store', static function ($user) {
            //who can do it.
            if (Gate::forUser($user)->any(['isAdm-global', 'isDir-global', 'isAss-global', 'isSec-global'])) {
                return true;
            }
            //no permission
            return false;
        });

        Gate::define('pole-update', static function ($user) {
            //who can do it.
            if (Gate::forUser($user)->any(['isAdm-global', 'isDir-global', 'isAss-global', 'isSec-global'])) {
                return true;
            }
            //no permission
            return false;
        });

        Gate::define('pole-destroy', static function ($user) {
            //who can do it.
            if (Gate::forUser($user)->any(['isAdm-global'])) {
                return true;
            }
            //no permission
            return false;
        });
    }
}
