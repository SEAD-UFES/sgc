<?php

namespace App\Gates;

use Illuminate\Support\Facades\Gate;

class UserTypeAssignmentGates
{
    public static function define()
    {
        Gate::define('userTypeAssignment-list', static function ($user) {
            //admins can do it.
            if (Gate::forUser($user)->allows('isAdm-global')) {
                return true;
            }
            //no permission
            return false;
        });

        Gate::define('userTypeAssignment-show', static function ($user) {
            //admins can do it.
            if (Gate::forUser($user)->allows('isAdm-global')) {
                return true;
            }
            //no permission
            return false;
        });

        Gate::define('userTypeAssignment-store', static function ($user) {
            //admins can do it.
            if (Gate::forUser($user)->allows('isAdm-global')) {
                return true;
            }
            //no permission
            return false;
        });

        Gate::define('userTypeAssignment-update', static function ($user) {
            //admins can do it.
            if (Gate::forUser($user)->allows('isAdm-global')) {
                return true;
            }
            //no permission
            return false;
        });

        Gate::define('userTypeAssignment-destroy', static function ($user) {
            //admins can do it.
            if (Gate::forUser($user)->allows('isAdm-global')) {
                return true;
            }
            //no permission
            return false;
        });
    }
}
