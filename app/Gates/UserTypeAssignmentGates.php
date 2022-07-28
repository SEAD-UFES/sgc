<?php

namespace App\Gates;

use Illuminate\Support\Facades\Gate;

class UserTypeAssignmentGates
{
    public static function define()
    {
        Gate::define('userTypeAssignment-list', static function ($user) {
            //admins can do it.
            return Gate::forUser($user)->allows('isAdm-global');
        });

        Gate::define('userTypeAssignment-show', static function ($user) {
            //admins can do it.
            return Gate::forUser($user)->allows('isAdm-global');
        });

        Gate::define('userTypeAssignment-store', static function ($user) {
            //admins can do it.
            return Gate::forUser($user)->allows('isAdm-global');
        });

        Gate::define('userTypeAssignment-update', static function ($user) {
            //admins can do it.
            return Gate::forUser($user)->allows('isAdm-global');
        });

        Gate::define('userTypeAssignment-destroy', static function ($user) {
            //admins can do it.
            return Gate::forUser($user)->allows('isAdm-global');
        });
    }
}
