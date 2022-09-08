<?php

namespace App\Gates;

use Illuminate\Support\Facades\Gate;

class ResponsibilityGates
{
    /**
     * @return void
     */
    public static function define(): void
    {
        Gate::define('responsibility-list', static function ($user) {
            //admins can do it.
            return Gate::forUser($user)->allows('isAdm-global');
        });

        Gate::define('responsibility-show', static function ($user) {
            //admins can do it.
            return Gate::forUser($user)->allows('isAdm-global');
        });

        Gate::define('responsibility-store', static function ($user) {
            //admins can do it.
            return Gate::forUser($user)->allows('isAdm-global');
        });

        Gate::define('responsibility-update', static function ($user) {
            //admins can do it.
            return Gate::forUser($user)->allows('isAdm-global');
        });

        Gate::define('responsibility-destroy', static function ($user) {
            //admins can do it.
            return Gate::forUser($user)->allows('isAdm-global');
        });
    }
}
