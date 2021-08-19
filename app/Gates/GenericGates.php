<?php

namespace App\Gates;

use Illuminate\Support\Facades\Gate;
use App\Models\User;

class GenericGates
{
    public static function define()
    {
        /* define a system admin user role */
        Gate::define('is-Adm', function (User $user) {

            //need to have session UserTypeAssignment active.
            $currentUTA = session('sessionUser')->getCurrentUTA();
            if (!$currentUTA) return false;

            //if currentUTA (UserTypeAssignment) is admin, ok
            if ($currentUTA && $currentUTA->userType->acronym === 'adm') return true;

            //if no permission
            return false;
        });
    }
}
