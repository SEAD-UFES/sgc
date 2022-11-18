<?php

namespace App\Repositories;

use App\Models\Responsibility;
use App\Models\User;

class ResponsibilityRepository
{
    /**
     * @param int $id
     *
     * @return array<int, Responsibility>
     */
    public static function getResponsibilitiesByUserId(int $id): ?array
    {
        $user = User::find($id);

        if ($user === null) {
            return null;
        }

        $userResponsibilities = Responsibility::where('user_id', $id)->get();

        return $userResponsibilities->all();
    }

    /**
     * @param int $id
     *
     * @return array<int, Responsibility>
     */
    public static function getActiveResponsibilitiesByUserId(int $id): ?array
    {
        $user = User::find($id);

        if ($user === null) {
            return null;
        }

        $userResponsibilities = Responsibility::where('user_id', $id)->where('begin', '<=', date('Y-m-d'))->where(function ($query) {
            $query->where('end', '>=', date('Y-m-d'))->orWhereNull('end');
        })->get();

        return $userResponsibilities->all();
    }

    /**
     * @param int $id
     *
     * @return Responsibility|null
     */
    public static function getFirstActiveResponsibilityByUserId(int $id): ?Responsibility
    {
        $userResponsibilityArray = self::getActiveResponsibilitiesByUserId($id);

        if (empty($userResponsibilityArray)) {
            return null;
        }

        return $userResponsibilityArray[0];
    }

    /**
     * @param int $responsibilityId
     * @param int $userId
     *
     * @return Responsibility|null
     */
    public static function getActiveResponsibilityByIdAssertUserId(int $responsibilityId, int $userId): ?Responsibility
    {
        if (Responsibility::where('id', $responsibilityId)->where('user_id', $userId)->exists()) {
            return Responsibility::where('id', $responsibilityId)->where('begin', '<=', date('Y-m-d'))->where(function ($query) {
                $query->where('end', '>=', date('Y-m-d'))->orWhereNull('end');
            })->first();
        }

        return null;
    }
}
