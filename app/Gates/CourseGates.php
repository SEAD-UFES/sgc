<?php

namespace App\Gates;

use Illuminate\Support\Facades\Gate;

class CourseGates
{
    public static function define()
    {
        Gate::define('course-list', function ($user) {
            //who can do it.
            if (Gate::forUser($user)->any(['isAdm-global', 'isDir-global', 'isAss-global', 'isSec-global'])) {
                return true;
            }

            //coord on any course or global
            if (Gate::forUser($user)->any(['isCoord'])) {
                return true;
            }

            //no permission
            return false;
        });

        Gate::define('course-show', function ($user) {
            //who can do it.
            if (Gate::forUser($user)->any(['isAdm-global', 'isDir-global', 'isAss-global', 'isSec-global', 'isCoord-global'])) {
                return true;
            }

            //no permission
            return false;
        });

        Gate::define('course-store', function ($user) {
            //who can do it.
            if (Gate::forUser($user)->any(['isAdm-global', 'isDir-global', 'isAss-global', 'isSec-global', 'isCoord-global'])) {
                return true;
            }

            //no permission
            return false;
        });

        Gate::define('course-update', function ($user) {
            //who can do it.
            if (Gate::forUser($user)->any(['isAdm-global', 'isDir-global', 'isAss-global', 'isSec-global', 'isCoord-global'])) {
                return true;
            }

            //no permission
            return false;
        });

        Gate::define('course-destroy', function ($user) {
            //who can do it.
            if (Gate::forUser($user)->any(['isAdm-global'])) {
                return true;
            }

            //no permission
            return false;
        });
    }
}
