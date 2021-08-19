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

            //if currentUTA (UserTypeAssignment) is adm, ok
            if ($currentUTA && $currentUTA->userType->acronym === 'adm') return true;

            //if no permission
            return false;
        });

        /* define a system diretor user role */
        Gate::define('is-Dir', function (User $user) {

            //need to have session UserTypeAssignment active.
            $currentUTA = session('sessionUser')->getCurrentUTA();
            if (!$currentUTA) return false;

            //if currentUTA (UserTypeAssignment) is dir, ok
            if ($currentUTA && $currentUTA->userType->acronym === 'dir') return true;

            //if no permission
            return false;
        });

        /* define a system Assistant user role */
        Gate::define('is-Ass', function (User $user) {

            //need to have session UserTypeAssignment active.
            $currentUTA = session('sessionUser')->getCurrentUTA();
            if (!$currentUTA) return false;

            //if currentUTA (UserTypeAssignment) is ass, ok
            if ($currentUTA && $currentUTA->userType->acronym === 'ass') return true;

            //if no permission
            return false;
        });

        /* define a system sec user role */
        Gate::define('is-Sec', function (User $user) {

            //need to have session UserTypeAssignment active.
            $currentUTA = session('sessionUser')->getCurrentUTA();
            if (!$currentUTA) return false;

            //if currentUTA (UserTypeAssignment) is sec, ok
            if ($currentUTA && $currentUTA->userType->acronym === 'sec') return true;

            //if no permission
            return false;
        });
    }
}
