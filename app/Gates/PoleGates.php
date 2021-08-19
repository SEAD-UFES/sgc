<?php

namespace App\Gates;

use Illuminate\Support\Facades\Gate;

class PoleGates
{
    public static function define()
    {
        Gate::define('pole-list', function ($user) {
            //who can do it.
            if (Gate::forUser($user)->any(['is-Adm', 'is-Dir', 'is-Ass', 'is-Sec'])) return true;

            //no permission
            return false;
        });

        Gate::define('pole-show', function ($user) {
            //who can do it.
            if (Gate::forUser($user)->any(['is-Adm', 'is-Dir', 'is-Ass', 'is-Sec'])) return true;

            //no permission
            return false;
        });

        Gate::define('pole-store', function ($user) {
            //who can do it.
            if (Gate::forUser($user)->any(['is-Adm', 'is-Dir', 'is-Ass', 'is-Sec'])) return true;

            //no permission
            return false;
        });

        Gate::define('pole-update', function ($user) {
            //who can do it.
            if (Gate::forUser($user)->any(['is-Adm', 'is-Dir', 'is-Ass', 'is-Sec'])) return true;

            //no permission
            return false;
        });

        Gate::define('pole-destroy', function ($user) {
            //who can do it.
            if (Gate::forUser($user)->any(['is-Adm'])) return true;

            //no permission
            return false;
        });
    }
}
