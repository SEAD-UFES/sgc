<?php

namespace App\Gates;

use Illuminate\Support\Facades\Gate;

class ApplicantGates
{
    /**
     * @return void
     */
    public static function define(): void
    {
        Gate::define('applicant-list', static function ($user) {
            //who can do it (global).
            if (Gate::forUser($user)->any(['isAdm-global', 'isDir-global', 'isSec-global'])) {
                return true;
            }

            //coord on any course or global
            return Gate::forUser($user)->any(['isCoord']);
        });

        Gate::define('applicant-store', static function ($user) {
            //who can do it (global).
            return Gate::forUser($user)->any(['isAdm-global', 'isDir-global', 'isSec-global']);
        });

        Gate::define('applicant-update-state', static function ($user) {
            //who can do it (global).
            return Gate::forUser($user)->any(['isAdm-global', 'isDir-global', 'isSec-global']);
        });

        Gate::define('applicant-destroy', static function ($user) {
            //who can do it (global).
            return Gate::forUser($user)->any(['isAdm-global', 'isDir-global', 'isSec-global']);
        });

        Gate::define('applicant-designate', static function ($user) {
            //who can do it (global).
            return Gate::forUser($user)->any(['isAdm-global', 'isDir-global', 'isSec-global']);
        });
    }
}
