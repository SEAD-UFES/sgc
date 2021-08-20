<?php

namespace App\Gates;

use Illuminate\Support\Facades\Gate;

class CourseGates
{
    public static function define()
    {
        Gate::define('course-list', function ($user) {
            //who can do it.
            if (Gate::forUser($user)->any(['is-Adm-global', 'is-Dir-global', 'is-Ass-global', 'is-Sec-global', 'is-Gra-global'])) return true;

            //no permission
            return false;
        });

        Gate::define('course-show', function ($user) {
            //who can do it.
            if (Gate::forUser($user)->any(['is-Adm-global', 'is-Dir-global', 'is-Ass-global', 'is-Sec-global', 'is-Gra-global'])) return true;

            //no permission
            return false;
        });

        Gate::define('course-store', function ($user) {
            //who can do it.
            if (Gate::forUser($user)->any(['is-Adm-global', 'is-Dir-global', 'is-Ass-global', 'is-Sec-global', 'is-Gra-global'])) return true;

            //no permission
            return false;
        });

        Gate::define('course-update', function ($user) {
            //who can do it.
            if (Gate::forUser($user)->any(['is-Adm-global', 'is-Dir-global', 'is-Ass-global', 'is-Sec-global', 'is-Gra-global'])) return true;

            //no permission
            return false;
        });

        Gate::define('course-destroy', function ($user) {
            //who can do it.
            if (Gate::forUser($user)->any(['is-Adm-global'])) return true;

            //no permission
            return false;
        });
    }
}
