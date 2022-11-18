<?php

namespace App\Gates;

use App\Models\Responsibility;
use App\Models\User;
use App\Models\UserType;
use Illuminate\Support\Facades\Gate;

class GenericGates
{
    /**
     * @return void
     */
    public static function define(): void
    {
        /* define a system admin user role */
        Gate::define('isAdm', static function ($user) {
            return self::checkGenericRoleUta('adm');
        });

        /* define a director user role */
        Gate::define('isDir', static function ($user) {
            return self::checkGenericRoleUta('dir');
        });

        /* define a grantor assistant user role */
        Gate::define('isAss', static function ($user) {
            return self::checkGenericRoleUta('ass');
        });

        /* define a academic secretary user role */
        Gate::define('isSec', static function ($user) {
            return self::checkGenericRoleUta('sec');
        });

        /* define a ldi user role */
        Gate::define('isLdi', static function ($user) {
            return self::checkGenericRoleUta('ldi');
        });

        /* define a grantee user role */
        Gate::define('isCoord', static function ($user) {
            return self::checkGenericRoleUta('coord');
        });

        /* define a system admin user role */
        Gate::define('isAdm-global', static function (User $user) {
            return self::checkGlobalRoleUta('adm');
        });

        /* define a system diretor user role */
        Gate::define('isDir-global', static function (User $user) {
            return self::checkGlobalRoleUta('dir');
        });

        /* define a system Assistant user role */
        Gate::define('isAss-global', static function (User $user) {
            return self::checkGlobalRoleUta('ass');
        });

        /* define a system sec user role */
        Gate::define('isSec-global', static function (User $user) {
            return self::checkGlobalRoleUta('sec');
        });

        /* define a grantee user role */
        Gate::define('isCoord-global', static function (User $user) {
            return self::checkGlobalRoleUta('coord');
        });

        /* define a grantee user role */
        Gate::define('isLdi-global', static function (User $user) {
            return self::checkGlobalRoleUta('ldi');
        });

        /* define a coord with course_id */
        Gate::define('isCoord-course_id', static function (User $user, int $course_id) {
            /** @var User $currentUser */
            $currentUser = auth()->user();
            //need to have session Responsibility active.
            /** @var Responsibility $currentResponsibility */
            $currentResponsibility = session('loggedInUser.currentResponsibility');
            if (! $currentResponsibility instanceof \App\Models\Responsibility) {
                return false;
            }

            /** @var UserType $currentUserType */
            $currentUserType = $currentResponsibility->userType;
            //if currentResponsibility (Responsibility) is coord, ok
            $is_coord = $currentUserType->acronym === 'coord';
            $course_id_match = $currentResponsibility->course_id === $course_id;

            return $is_coord && $course_id_match;
        });
    }

    private static function checkGenericRoleUta(string $acronym): bool
    {
        //return $user->userType->acronym == $acronym;

        //need to have session Responsibility active.
        /** @var Responsibility $currentResponsibility */
        $currentResponsibility = session('loggedInUser.currentResponsibility');

        if (! $currentResponsibility instanceof \App\Models\Responsibility) {
            return false;
        }

        /** @var UserType $currentUserType */
        $currentUserType = $currentResponsibility->userType;

        //if currentResponsibility (Responsibility) is $acronym, ok
        return $currentUserType->acronym === $acronym;
    }

    private static function checkGlobalRoleUta(string $acronym): bool
    {
        //need to have session Responsibility active.
        /** @var Responsibility $currentResponsibility */
        $currentResponsibility = session('loggedInUser.currentResponsibility');

        if (! $currentResponsibility instanceof \App\Models\Responsibility) {
            return false;
        }

        /** @var UserType $currentUserType */
        $currentUserType = $currentResponsibility->userType;
        //if currentResponsibility (Responsibility) is $acronym, ok
        $acronymMatch = $currentUserType->acronym === $acronym;
        $course_id_null = $currentResponsibility->course_id === null;

        return $acronymMatch && $course_id_null;
    }
}
