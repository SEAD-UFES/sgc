<?php

namespace App\Gates;

use Illuminate\Support\Facades\Gate;
use App\Models\User;

class GenericGates
{
    public static function define()
    {

        /* define a system admin user role */
        Gate::define('isAdm', function ($user) {
            //return $user->userType->acronym == 'adm';

            //need to have session UserTypeAssignment active.
            $currentUTA = session('sessionUser')->getCurrentUTA();
            if (!$currentUTA) return false;

            //if currentUTA (UserTypeAssignment) is adm, ok
            $acronym_adm = $currentUTA->userType->acronym === 'adm';
            if ($acronym_adm) return true;

            //if no permission
            return false;
        });

        /* define a director user role */
        Gate::define('isDir', function ($user) {
            //return $user->userType->acronym == 'dir';

            //need to have session UserTypeAssignment active.
            $currentUTA = session('sessionUser')->getCurrentUTA();
            if (!$currentUTA) return false;

            //if currentUTA (UserTypeAssignment) is dir, ok
            $acronym_dir = $currentUTA->userType->acronym === 'dir';
            if ($acronym_dir) return true;

            //if no permission
            return false;
        });

        /* define a grantor assistant user role */
        Gate::define('isAss', function ($user) {
            //return $user->userType->acronym == 'ass';

            //need to have session UserTypeAssignment active.
            $currentUTA = session('sessionUser')->getCurrentUTA();
            if (!$currentUTA) return false;

            //if currentUTA (UserTypeAssignment) is ass, ok
            $acronym_ass = $currentUTA->userType->acronym === 'ass';
            if ($acronym_ass) return true;

            //if no permission
            return false;
        });

        /* define a academic secretary user role */
        Gate::define('isSec', function ($user) {
            //return $user->userType->acronym == 'sec';

            //need to have session UserTypeAssignment active.
            $currentUTA = session('sessionUser')->getCurrentUTA();
            if (!$currentUTA) return false;

            //if currentUTA (UserTypeAssignment) is sec, ok
            $acronym_sec = $currentUTA->userType->acronym === 'sec';
            if ($acronym_sec) return true;

            //if no permission
            return false;
        });

        /* define a ldi user role */
        Gate::define('isLdi', function ($user) {
            //return $user->userType->acronym == 'ldi';
            //need to have session UserTypeAssignment active.
            $currentUTA = session('sessionUser')->getCurrentUTA();
            if (!$currentUTA) return false;

            //if currentUTA (UserTypeAssignment) is gra, ok
            $acronym_ldi = $currentUTA->userType->acronym === 'ldi';
            if ($acronym_ldi) return true;

            //if no permission
            return false;
        });

        /* define a grantee user role */
        Gate::define('isCoord', function ($user) {
            //return $user->userType->acronym == 'gra';

            //need to have session UserTypeAssignment active.
            $currentUTA = session('sessionUser')->getCurrentUTA();
            if (!$currentUTA) return false;

            //if currentUTA (UserTypeAssignment) is gra, ok
            $acronym_gra = $currentUTA->userType->acronym === 'coord';
            if ($acronym_gra) return true;

            //if no permission
            return false;
        });

        /* define a system admin user role */
        Gate::define('isAdm-global', function (User $user) {

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
        Gate::define('isDir-global', function (User $user) {

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
        Gate::define('isAss-global', function (User $user) {

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
        Gate::define('isSec-global', function (User $user) {

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
        Gate::define('isCoord-global', function (User $user) {
            //need to have session UserTypeAssignment active.
            $currentUTA = session('sessionUser')->getCurrentUTA();
            if (!$currentUTA) return false;

            //if currentUTA (UserTypeAssignment) is coord, ok
            $acronym_coord = $currentUTA->userType->acronym === 'coord';
            $course_id_null = $currentUTA->course_id === null;
            if ($acronym_coord && $course_id_null) return true;

            //if no permission
            return false;
        });

        /* define a grantee user role */
        Gate::define('isLdi-global', function (User $user) {
            //need to have session UserTypeAssignment active.
            $currentUTA = session('sessionUser')->getCurrentUTA();
            if (!$currentUTA) return false;

            //if currentUTA (UserTypeAssignment) is ldi, ok
            $acronym_ldi = $currentUTA->userType->acronym === 'ldi';
            $course_id_null = $currentUTA->course_id === null;
            if ($acronym_ldi && $course_id_null) return true;

            //if no permission
            return false;
        });

        /* define a coord with course_id */
        Gate::define('isCoord-course_id', function (User $user, $course_id) {
            //need to have session UserTypeAssignment active.
            $currentUTA = session('sessionUser')->getCurrentUTA();
            if (!$currentUTA) return false;

            //if currentUTA (UserTypeAssignment) is coord, ok
            $is_coord = $currentUTA->userType->acronym === 'coord';
            $course_id_math = $currentUTA->course_id === $course_id;
            if ($is_coord && $course_id_math) return true;

            //if no permission
            return false;
        });
    }
}
