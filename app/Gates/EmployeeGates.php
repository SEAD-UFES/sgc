<?php

namespace App\Gates;

use Illuminate\Support\Facades\Gate;

class EmployeeGates
{
    /**
     *
     * @return void
     */
    public static function define(): void
    {
        Gate::define('employee-list', static function ($user) {
            //who can do it (global).
            if (Gate::forUser($user)->any(['isAdm-global', 'isDir-global', 'isAss-global', 'isSec-global'])) {
                return true;
            }

            //coord on any course or global
            return Gate::forUser($user)->any(['isCoord']);
        });

        Gate::define('employee-show', static function ($user) {
            //who can do it (global).
            return Gate::forUser($user)->any(['isAdm-global', 'isDir-global', 'isAss-global', 'isSec-global']);
        });

        Gate::define('employee-store', static function ($user) {
            //who can do it (global).
            return Gate::forUser($user)->any(['isAdm-global', 'isDir-global', 'isAss-global', 'isSec-global']);
        });

        Gate::define('employee-update', static function ($user) {
            //who can do it (global).
            return Gate::forUser($user)->any(['isAdm-global', 'isDir-global', 'isAss-global', 'isSec-global']);
        });

        Gate::define('employee-destroy', static function ($user) {
            //who can do it (global).
            return Gate::forUser($user)->any(['isAdm-global']);
        });
    }
}
