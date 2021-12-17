<?php

namespace App\Gates;

use Illuminate\Support\Facades\Gate;

class UserGates
{
    public static function define()
    {
        Gate::define('user-list', function ($user) {
            //admins can do it.
            if (Gate::forUser($user)->allows('isAdm-global')) {
                return true;
            }

            //no permission
            return false;
        });

        Gate::define('user-show', function ($user) {
            //admins can do it.
            if (Gate::forUser($user)->allows('isAdm-global')) {
                return true;
            }

            //no permission
            return false;
        });

        Gate::define('user-store', function ($user) {
            //admins can do it.
            if (Gate::forUser($user)->allows('isAdm-global')) {
                return true;
            }

            //no permission
            return false;
        });

        Gate::define('user-update', function ($user) {
            //admins can do it.
            if (Gate::forUser($user)->allows('isAdm-global')) {
                return true;
            }

            //no permission
            return false;
        });

        Gate::define('user-destroy', function ($user) {
            //admins can do it.
            if (Gate::forUser($user)->allows('isAdm-global')) {
                return true;
            }

            //no permission
            return false;
        });
    }
}
