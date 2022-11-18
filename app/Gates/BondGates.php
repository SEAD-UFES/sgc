<?php

namespace App\Gates;

use App\Models\Bond;
use App\Models\Course;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

class BondGates
{
    /**
     * @return void
     */
    public static function define(): void
    {
        Gate::define('bond-list', static function ($user) {
            //who can do it (global).
            if (Gate::forUser($user)->any(['isAdm-global', 'isDir-global', 'isAss-global', 'isSec-global'])) {
                return true;
            }

            //coord on any course
            return Gate::forUser($user)->any(['isCoord']);
        });

        Gate::define('bond-show', static function ($user) {
            //who can do it (global).
            return Gate::forUser($user)->any(['isAdm-global', 'isDir-global', 'isAss-global', 'isSec-global']);
        });

        Gate::define('bond-create', static function ($user) {
            //who can do it (global).
            if (Gate::forUser($user)->any(['isAdm-global', 'isDir-global', 'isAss-global', 'isSec-global'])) {
                return true;
            }

            //coord on any course
            return Gate::forUser($user)->any(['isCoord']);
        });

        Gate::define('bond-store-course_id', static function (User $user, ?int $course_id) {
            //who can do it (global).
            if (Gate::forUser($user)->any(['isAdm-global', 'isDir-global', 'isAss-global', 'isSec-global'])) {
                return true;
            }

            if (is_null($course_id)) {
                return false;
            }

            //coord on this course then ok.
            return Gate::forUser($user)->any(['isCoord-course_id'], $course_id);
        });

        Gate::define('bond-update', static function (User $user, Bond $bond) {
            //who can do it (global).
            if (Gate::forUser($user)->any(['isAdm-global', 'isDir-global', 'isAss-global', 'isSec-global'])) {
                return true;
            }

            //coord on this course then ok.
            /** @var Course|null $bondCourse */
            $bondCourse = $bond->getAttribute('course');
            return Gate::forUser($user)->any(['isCoord-course_id'], $bondCourse?->getAttribute('id'));
        });

        Gate::define('bond-updateTo', static function (User $user, Bond $bond, ?int $course_id) {
            //who can do it (global).
            if (Gate::forUser($user)->any(['isAdm-global', 'isDir-global', 'isAss-global', 'isSec-global'])) {
                return true;
            }

            if (is_null($course_id)) {
                return false;
            }

            //coord on this course then ok.
            /** @var Course|null $bondCourse */
            $bondCourse = $bond->getAttribute('course');
            return Gate::forUser($user)->any(['isCoord-course_id'], $bondCourse?->getAttribute('id')) && Gate::forUser($user)->any(['isCoord-course_id'], $course_id);
        });

        Gate::define('bond-destroy', static function ($user) {
            //who can do it (global).
            return Gate::forUser($user)->any(['isAdm-global']);
        });

        Gate::define('bond-requestReview', static function ($user) {
            //who can do it (global).
            return Gate::forUser($user)->any(['isAdm-global', 'isDir-global', 'isSec-global']);
        });

        Gate::define('bond-review', static function ($user) {
            //who can do it (global).
            return Gate::forUser($user)->any(['isAdm-global', 'isDir-global', 'isAss-global']);
        });
    }
}
