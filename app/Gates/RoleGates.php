<?php

namespace App\Gates;

use Illuminate\Support\Facades\Gate;

class RoleGates
{
    public static function define()
    {
        Gate::define('role-list', function ($user) {
            //who can do it.
            if (Gate::forUser($user)->any(['is-Adm', 'is-Dir', 'is-Ass', 'is-Sec', 'is-Gra'])) return true;

            //no permission
            return false;
        });

        Gate::define('role-show', function ($user) {
            //who can do it.
            if (Gate::forUser($user)->any(['is-Adm', 'is-Dir', 'is-Ass', 'is-Sec', 'is-Gra'])) return true;

            //no permission
            return false;
        });

        Gate::define('role-store', function ($user) {
            //who can do it.
            if (Gate::forUser($user)->any(['is-Adm', 'is-Dir', 'is-Ass', 'is-Sec', 'is-Gra'])) return true;

            //no permission
            return false;
        });

        Gate::define('role-update', function ($user) {
            //who can do it.
            if (Gate::forUser($user)->any(['is-Adm', 'is-Dir', 'is-Ass', 'is-Sec', 'is-Gra'])) return true;

            //no permission
            return false;
        });

        Gate::define('role-destroy', function ($user) {
            //who can do it.
            if (Gate::forUser($user)->any(['is-Adm'])) return true;

            //no permission
            return false;
        });
    }
}
