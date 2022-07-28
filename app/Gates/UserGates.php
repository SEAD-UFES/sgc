<?php

namespace App\Gates;

use Illuminate\Support\Facades\Gate;

class UserGates
{
    /**
     * @return void
     */
    public static function define(): void
    {
        Gate::define('user-list', static function ($user) {
            //admins can do it.
            return Gate::forUser($user)->allows('isAdm-global');
        });

        Gate::define('user-show', static function ($user) {
            //admins can do it.
            return Gate::forUser($user)->allows('isAdm-global');
        });

        Gate::define('user-store', static function ($user) {
            //admins can do it.
            return Gate::forUser($user)->allows('isAdm-global');
        });

        Gate::define('user-update', static function ($user) {
            //admins can do it.
            return Gate::forUser($user)->allows('isAdm-global');
        });

        Gate::define('user-destroy', static function ($user) {
            //admins can do it.
            return Gate::forUser($user)->allows('isAdm-global');
        });
    }
}
