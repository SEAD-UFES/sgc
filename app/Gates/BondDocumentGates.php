<?php

namespace App\Gates;

use Illuminate\Support\Facades\Gate;

class BondDocumentGates
{
    public static function define()
    {
        Gate::define('bondDocument-rights', static function ($user) {
            //who can do it (global).
            if (Gate::forUser($user)->any(['isAdm-global', 'isDir-global', 'isAss-global', 'isSec-global', 'isLdi-global'])) {
                return true;
            }
            //coords of any course
            if (Gate::forUser($user)->any(['isCoord'])) {
                return true;
            }
            //no permission
            return false;
        });

        Gate::define('bondDocument-list', static function ($user) {
            //who can do it (global).
            if (Gate::forUser($user)->any(['isAdm-global', 'isDir-global', 'isAss-global', 'isSec-global'])) {
                return true;
            }
            //no permission
            return false;
        });

        Gate::define('bondDocument-store', static function ($user) {
            //who can do it (global).
            if (Gate::forUser($user)->any(['isAdm-global', 'isDir-global', 'isAss-global', 'isSec-global'])) {
                return true;
            }
            //no permission
            return false;
        });

        Gate::define('bondDocument-download', static function ($user) {
            //who can do it (global).
            if (Gate::forUser($user)->any(['isAdm-global', 'isDir-global', 'isAss-global', 'isSec-global'])) {
                return true;
            }
            //no permission
            return false;
        });
    }
}
