<?php

namespace App\Gates;

use Illuminate\Support\Facades\Gate;

class RoleGates
{
    /**
     *
     * @return void
     */
    public static function define(): void
    {
        Gate::define('role-list', static function ($user) {
            //who can do it.
            if (Gate::forUser($user)->any(['isAdm-global', 'isDir-global', 'isAss-global', 'isSec-global'])) {
                return true;
            }

            //coord on any course or global
            return Gate::forUser($user)->any(['isCoord']);
        });

        Gate::define('role-show', static function ($user) {
            //who can do it.
            return Gate::forUser($user)->any(['isAdm-global', 'isDir-global', 'isAss-global', 'isSec-global', 'isCoord-global']);
        });

        Gate::define('role-store', static function ($user) {
            //who can do it.
            return Gate::forUser($user)->any(['isAdm-global', 'isDir-global', 'isAss-global', 'isSec-global', 'isCoord-global']);
        });

        Gate::define('role-update', static function ($user) {
            //who can do it.
            return Gate::forUser($user)->any(['isAdm-global', 'isDir-global', 'isAss-global', 'isSec-global', 'isCoord-global']);
        });

        Gate::define('role-destroy', static function ($user) {
            //who can do it.
            return Gate::forUser($user)->any(['isAdm-global']);
        });
    }
}
