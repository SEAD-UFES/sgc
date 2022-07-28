<?php

namespace App\Gates;

use Illuminate\Support\Facades\Gate;

class ApprovedGates
{
    /**
     *
     * @return void
     */
    public static function define(): void
    {
        Gate::define('approved-list', static function ($user) {
            //who can do it (global).
            if (Gate::forUser($user)->any(['isAdm-global', 'isDir-global', 'isSec-global'])) {
                return true;
            }

            //coord on any course or global
            return Gate::forUser($user)->any(['isCoord']);
        });

        Gate::define('approved-store', static function ($user) {
            //who can do it (global).
            return Gate::forUser($user)->any(['isAdm-global', 'isDir-global', 'isSec-global']);
        });

        Gate::define('approved-update-state', static function ($user) {
            //who can do it (global).
            return Gate::forUser($user)->any(['isAdm-global', 'isDir-global', 'isSec-global']);
        });

        Gate::define('approved-destroy', static function ($user) {
            //who can do it (global).
            return Gate::forUser($user)->any(['isAdm-global', 'isDir-global', 'isSec-global']);
        });

        Gate::define('approved-designate', static function ($user) {
            //who can do it (global).
            return Gate::forUser($user)->any(['isAdm-global', 'isDir-global', 'isSec-global']);
        });
    }
}
