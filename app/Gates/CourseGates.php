<?php

namespace App\Gates;

use Illuminate\Support\Facades\Gate;

class CourseGates
{
    public static function define()
    {
        Gate::define('course-list', static function ($user) {
            //who can do it.
            if (Gate::forUser($user)->any(['isAdm-global', 'isDir-global', 'isAss-global', 'isSec-global'])) {
                return true;
            }

            //coord on any course or global
            return Gate::forUser($user)->any(['isCoord']);
        });

        Gate::define('course-show', static function ($user) {
            //who can do it.
            return Gate::forUser($user)->any(['isAdm-global', 'isDir-global', 'isAss-global', 'isSec-global', 'isCoord-global']);
        });

        Gate::define('course-store', static function ($user) {
            //who can do it.
            return Gate::forUser($user)->any(['isAdm-global', 'isDir-global', 'isAss-global', 'isSec-global', 'isCoord-global']);
        });

        Gate::define('course-update', static function ($user) {
            //who can do it.
            return Gate::forUser($user)->any(['isAdm-global', 'isDir-global', 'isAss-global', 'isSec-global', 'isCoord-global']);
        });

        Gate::define('course-destroy', static function ($user) {
            //who can do it.
            return Gate::forUser($user)->any(['isAdm-global']);
        });
    }
}
