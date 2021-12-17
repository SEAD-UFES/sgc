<?php

namespace App\Gates;

use Illuminate\Support\Facades\Gate;

class UserTypeAssignmentGates
{
    public static function define()
    {
        Gate::define('userTypeAssignment-list', function ($user) {
            //admins can do it.
            if (Gate::forUser($user)->allows('isAdm-global')) {
                return true;
            }

            //no permission
            return false;
        });

        Gate::define('userTypeAssignment-show', function ($user) {
            //admins can do it.
            if (Gate::forUser($user)->allows('isAdm-global')) {
                return true;
            }

            //no permission
            return false;
        });

        Gate::define('userTypeAssignment-store', function ($user) {
            //admins can do it.
            if (Gate::forUser($user)->allows('isAdm-global')) {
                return true;
            }

            //no permission
            return false;
        });

        Gate::define('userTypeAssignment-update', function ($user) {
            //admins can do it.
            if (Gate::forUser($user)->allows('isAdm-global')) {
                return true;
            }

            //no permission
            return false;
        });

        Gate::define('userTypeAssignment-destroy', function ($user) {
            //admins can do it.
            if (Gate::forUser($user)->allows('isAdm-global')) {
                return true;
            }

            //no permission
            return false;
        });
    }
}
