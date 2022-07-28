<?php

namespace App\Gates;

use Illuminate\Support\Facades\Gate;

class BondDocumentGates
{
    /**
     *
     * @return void
     */
    public static function define(): void
    {
        Gate::define('bondDocument-rights', static function ($user) {
            //who can do it (global).
            if (Gate::forUser($user)->any(['isAdm-global', 'isDir-global', 'isAss-global', 'isSec-global', 'isLdi-global'])) {
                return true;
            }

            //coords of any course
            return Gate::forUser($user)->any(['isCoord']);
        });

        Gate::define('bondDocument-list', static function ($user) {
            //who can do it (global).
            return Gate::forUser($user)->any(['isAdm-global', 'isDir-global', 'isAss-global', 'isSec-global']);
        });

        Gate::define('bondDocument-store', static function ($user) {
            //who can do it (global).
            return Gate::forUser($user)->any(['isAdm-global', 'isDir-global', 'isAss-global', 'isSec-global']);
        });

        Gate::define('bondDocument-download', static function ($user) {
            //who can do it (global).
            return Gate::forUser($user)->any(['isAdm-global', 'isDir-global', 'isAss-global', 'isSec-global']);
        });
    }
}
