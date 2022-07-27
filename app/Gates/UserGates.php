<?php

namespace App\Gates;

use Illuminate\Support\Facades\Gate;

class UserGates
{
    public static function define()
    {
        Gate::define('user-list', static function ($user) {
            //admins can do it.
            if (Gate::forUser($user)->allows('isAdm-global')) {
                return true;
            }
            //no permission
            return false;
        });

        Gate::define('user-show', static function ($user) {
            //admins can do it.
            if (Gate::forUser($user)->allows('isAdm-global')) {
                return true;
            }
            //no permission
            return false;
        });

        Gate::define('user-store', static function ($user) {
            //admins can do it.
            if (Gate::forUser($user)->allows('isAdm-global')) {
                return true;
            }
            //no permission
            return false;
        });

        Gate::define('user-update', static function ($user) {
            //admins can do it.
            if (Gate::forUser($user)->allows('isAdm-global')) {
                return true;
            }
            //no permission
            return false;
        });

        Gate::define('user-destroy', static function ($user) {
            //admins can do it.
            if (Gate::forUser($user)->allows('isAdm-global')) {
                return true;
            }
            //no permission
            return false;
        });
    }
}
