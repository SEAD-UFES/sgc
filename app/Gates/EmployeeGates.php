<?php

namespace App\Gates;

use Illuminate\Support\Facades\Gate;

class EmployeeGates
{
    public static function define()
    {
        Gate::define('employee-list', function ($user) {
            //who can do it (global).
            if (Gate::forUser($user)->any(['is-Adm-global', 'is-Dir-global', 'is-Ass-global', 'is-Sec-global'])) return true;

            //coord on any course
            if (Gate::forUser($user)->any(['isCoord'])) return true;

            //no permission
            return false;
        });

        Gate::define('employee-show', function ($user) {
            //who can do it (global).
            if (Gate::forUser($user)->any(['is-Adm-global', 'is-Dir-global', 'is-Ass-global', 'is-Sec-global'])) return true;

            //no permission
            return false;
        });

        Gate::define('employee-store', function ($user) {
            //who can do it (global).
            if (Gate::forUser($user)->any(['is-Adm-global', 'is-Dir-global', 'is-Ass-global', 'is-Sec-global'])) return true;

            //no permission
            return false;
        });

        Gate::define('employee-update', function ($user) {
            //who can do it (global).
            if (Gate::forUser($user)->any(['is-Adm-global', 'is-Dir-global', 'is-Ass-global', 'is-Sec-global'])) return true;

            //no permission
            return false;
        });

        Gate::define('employee-destroy', function ($user) {
            //who can do it (global).
            if (Gate::forUser($user)->any(['is-Adm-global'])) return true;

            //no permission
            return false;
        });
    }
}
