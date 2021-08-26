<?php

namespace App\Gates;

use Illuminate\Support\Facades\Gate;

class BondGates
{
    public static function define()
    {
        Gate::define('bond-list', function ($user) {
            //who can do it (global).
            if (Gate::forUser($user)->any(['is-Adm-global', 'is-Dir-global', 'is-Ass-global', 'is-Sec-global'])) return true;

            //coord on any course
            if (Gate::forUser($user)->any(['isCoord'])) return true;

            //no permission
            return false;
        });

        Gate::define('bond-show', function ($user) {
            //who can do it (global).
            if (Gate::forUser($user)->any(['is-Adm-global', 'is-Dir-global', 'is-Ass-global', 'is-Sec-global'])) return true;

            //no permission
            return false;
        });

        Gate::define('bond-store', function ($user) {
            //who can do it (global).
            if (Gate::forUser($user)->any(['is-Adm-global', 'is-Dir-global', 'is-Ass-global', 'is-Sec-global'])) return true;

            //no permission
            return false;
        });

        Gate::define('bond-update', function ($user) {
            //who can do it (global).
            if (Gate::forUser($user)->any(['is-Adm-global', 'is-Dir-global', 'is-Ass-global', 'is-Sec-global'])) return true;

            //no permission
            return false;
        });

        Gate::define('bond-destroy', function ($user) {
            //who can do it (global).
            if (Gate::forUser($user)->any(['is-Adm-global'])) return true;

            //no permission
            return false;
        });

        Gate::define('bond-requestReview', function ($user) {
            //who can do it (global).
            if (Gate::forUser($user)->any(['is-Adm-global', 'is-Dir-global', 'is-Sec-global'])) return true;

            //no permission
            return false;
        });

        Gate::define('bond-review', function ($user) {
            //who can do it (global).
            if (Gate::forUser($user)->any(['is-Adm-global', 'is-Dir-global', 'is-Ass-global'])) return true;

            //no permission
            return false;
        });
    }
}
