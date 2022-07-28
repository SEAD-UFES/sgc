<?php

namespace App\Gates;

use Illuminate\Support\Facades\Gate;

class PoleGates
{
    /**
     *
     * @return void
     */
    public static function define(): void
    {
        Gate::define('pole-list', static function ($user) {
            //who can do it.
            if (Gate::forUser($user)->any(['isAdm-global', 'isDir-global', 'isAss-global', 'isSec-global'])) {
                return true;
            }

            //coord on any course or global
            return Gate::forUser($user)->any(['isCoord']);
        });

        Gate::define('pole-show', static function ($user) {
            //who can do it.
            return Gate::forUser($user)->any(['isAdm-global', 'isDir-global', 'isAss-global', 'isSec-global']);
        });

        Gate::define('pole-store', static function ($user) {
            //who can do it.
            return Gate::forUser($user)->any(['isAdm-global', 'isDir-global', 'isAss-global', 'isSec-global']);
        });

        Gate::define('pole-update', static function ($user) {
            //who can do it.
            return Gate::forUser($user)->any(['isAdm-global', 'isDir-global', 'isAss-global', 'isSec-global']);
        });

        Gate::define('pole-destroy', static function ($user) {
            //who can do it.
            return Gate::forUser($user)->any(['isAdm-global']);
        });
    }
}
