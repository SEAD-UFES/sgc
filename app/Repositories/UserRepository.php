<?php

namespace App\Repositories;

use App\Helpers\ModelSortValidator;
use App\Models\User;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class UserRepository
{
    /**
     * @param string $sort
     * @param string $direction
     *
     * @return LengthAwarePaginator<Model>
     */
    public static function getAllUsersPaginated(string $sort = 'users.id', string $direction = 'desc'): LengthAwarePaginator
    {
        $sort = ModelSortValidator::validateSort(User::class, $sort);
        $direction = ModelSortValidator::validateDirection($direction);

        /** @var Builder $query */
        $query = self::allUsersQuery($sort, $direction);

        return $query->paginate(10);
    }

    /**
     * @param int $userTypeId
     * @param string $sort
     * @param string $direction
     *
     * @return Collection<int, Model>
     */
    public function getActiveUsersOfActiveTypeId(int $userTypeId, string $sort = 'users.id', string $direction = 'desc'): Collection
    {
        $sort = ModelSortValidator::validateSort(User::class, $sort);
        $direction = ModelSortValidator::validateDirection($direction);

        /**
         * @var Builder $query
         */
        $query = $this->allActiveUsersOfActiveTypeIdQuery($userTypeId, $sort, $direction);

        return $query->get();
    }

    /**
     * @param int $userTypeId
     * @param int $courseId
     * @param string $sort
     * @param string $direction
     *
     * @return Collection<int, Model>
     */
    public function getActiveUsersOfActiveTypeIdOfCourseId(int $userTypeId, int $courseId, string $sort = 'users.id', string $direction = 'desc'): Collection
    {
        $sort = ModelSortValidator::validateSort(User::class, $sort);
        $direction = ModelSortValidator::validateDirection($direction);

        /**
         * @var Builder $query
         */
        $query = $this->allActiveUsersOfActiveTypeIdOfCourseIdQuery($userTypeId, $courseId, $sort, $direction);

        return $query->get();
    }

    /**
     * Undocumented function
     *
     * @param string $sort
     * @param string $direction
     *
     * @return Builder
     */
    private static function allUsersQuery(string $sort = 'users.id', string $direction = 'desc'): Builder
    {
        return User::select([
            'users.id',
            'users.login',
            'users.active',
            'users.created_at',
            'users.updated_at',
        ])
            ->orderBy($sort, $direction);
    }

    /**
     * @param string $sort
     * @param string $direction
     *
     * @return Builder
     */
    private function allActiveUsersQuery(string $sort = 'users.id', string $direction = 'desc'): Builder
    {
        return self::allUsersQuery($sort, $direction)
            ->where('users.active', true);
    }

    /**
     * @param int $userTypeId
     * @param string $sort
     * @param string $direction
     *
     * @return Builder
     */
    private function allActiveUsersOfTypeIdQuery(int $userTypeId, string $sort = 'users.id', string $direction = 'desc'): Builder
    {
        return $this->allActiveUsersQuery($sort, $direction)
            ->join('responsibilities', 'responsibilities.user_id', '=', 'users.id')
            ->where('responsibilities.user_type_id', $userTypeId);
    }

    /**
     * @param int $userTypeId
     * @param string $sort
     * @param string $direction
     *
     * @return Builder
     */
    private function allActiveUsersOfActiveTypeIdQuery(int $userTypeId, string $sort = 'users.id', string $direction = 'desc'): Builder
    {
        return $this->allActiveUsersOfTypeIdQuery($userTypeId, $sort, $direction)
            ->where('responsibilities.begin', '<=', Carbon::today()->toDateString())
            ->where(static function ($q) {
                $q->where('responsibilities.end', '>=', Carbon::today()->toDateString())
                    ->orWhereNull('responsibilities.end');
            });
    }

    /**
     * @param int $userTypeId
     * @param int $courseId
     * @param string $sort
     * @param string $direction
     *
     * @return Builder
     */
    private function allActiveUsersOfActiveTypeIdOfCourseIdQuery(int $userTypeId, int $courseId, string $sort = 'users.id', string $direction = 'desc'): Builder
    {
        return $this->allActiveUsersOfActiveTypeIdQuery($userTypeId, $sort, $direction)
            ->where('responsibilities.course_id', $courseId);
    }
}
