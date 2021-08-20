<?php

namespace App\Gates;

use Illuminate\Support\Facades\Gate;
use App\Models\User;

class GenericGates
{
    public static function define()
    {
        /* define a system admin user role */
        Gate::define('is-Adm-global', function (User $user) {

            //need to have session UserTypeAssignment active.
            $currentUTA = session('sessionUser')->getCurrentUTA();
            if (!$currentUTA) return false;

            //if currentUTA (UserTypeAssignment) is adm, ok
            $acronym_adm = $currentUTA->userType->acronym === 'adm';
            $course_id_null = $currentUTA->course_id === null;
            if ($acronym_adm && $course_id_null) return true;

            //if no permission
            return false;
        });

        /* define a system diretor user role */
        Gate::define('is-Dir-global', function (User $user) {

            //need to have session UserTypeAssignment active.
            $currentUTA = session('sessionUser')->getCurrentUTA();
            if (!$currentUTA) return false;

            //if currentUTA (UserTypeAssignment) is dir, ok
            $acronym_dir = $currentUTA->userType->acronym === 'dir';
            $course_id_null = $currentUTA->course_id === null;
            if ($acronym_dir && $course_id_null) return true;

            //if no permission
            return false;
        });

        /* define a system Assistant user role */
        Gate::define('is-Ass-global', function (User $user) {

            //need to have session UserTypeAssignment active.
            $currentUTA = session('sessionUser')->getCurrentUTA();
            if (!$currentUTA) return false;

            //if currentUTA (UserTypeAssignment) is ass, ok
            $acronym_ass = $currentUTA->userType->acronym === 'ass';
            $course_id_null = $currentUTA->course_id === null;
            if ($acronym_ass && $course_id_null) return true;

            //if no permission
            return false;
        });

        /* define a system sec user role */
        Gate::define('is-Sec-global', function (User $user) {

            //need to have session UserTypeAssignment active.
            $currentUTA = session('sessionUser')->getCurrentUTA();
            if (!$currentUTA) return false;

            //if currentUTA (UserTypeAssignment) is sec, ok
            $acronym_sec = $currentUTA->userType->acronym === 'sec';
            $course_id_null = $currentUTA->course_id === null;
            if ($acronym_sec && $course_id_null) return true;

            //if no permission
            return false;
        });

        /* define a grantee user role */
        Gate::define('is-Gra-global', function (User $user) {
            //need to have session UserTypeAssignment active.
            $currentUTA = session('sessionUser')->getCurrentUTA();
            if (!$currentUTA) return false;

            //if currentUTA (UserTypeAssignment) is gra, ok
            $acronym_gra = $currentUTA->userType->acronym === 'gra';
            $course_id_null = $currentUTA->course_id === null;
            if ($acronym_gra && $course_id_null) return true;

            //if no permission
            return false;
        });
    }
}
