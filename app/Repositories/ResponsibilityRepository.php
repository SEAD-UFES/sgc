<?php

namespace App\Repositories;

use App\Models\Responsibility;
use App\Models\User;

class ResponsibilityRepository
{
    // /**
    //  * @param string $sort
    //  * @param string $direction
    //  *
    //  * @return LengthAwarePaginator
    //  */
    // public static function getAllResponsibilities(string $sort = 'responsibilities.id', string $direction = 'desc'): LengthAwarePaginator
    // {
    //     $sort = ResponsibilityRepositoryHelper::validateSort(Responsibility::class, $sort);
    //     $direction = ResponsibilityRepositoryHelper::validateDirection($direction);

    //     /**
    //      * @var Builder<Responsibility> $query
    //      */
    //     $query = Responsibility::select([
    //         'responsibilities.id',
    //         'responsibilities.name',
    //         'responsibilities.description',
    //         'responsibilities.created_at',
    //         'responsibilities.updated_at',
    //     ])
    //         ->orderBy($sort, $direction);

    //     return $query->paginate(10);
    // }

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
            $userResponsibility = Responsibility::where('id', $responsibilityId)->where('begin', '<=', date('Y-m-d'))->where(function ($query) {
                $query->where('end', '>=', date('Y-m-d'))->orWhereNull('end');
            })->first();

            return $userResponsibility;
        }

        return null;
    }
}
