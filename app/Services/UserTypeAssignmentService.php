<?php

namespace App\Services;

use App\Models\UserTypeAssignment;
use Illuminate\Pagination\LengthAwarePaginator;

class UserTypeAssignmentService
{
    /**
     * Undocumented function
     *
     * @return LengthAwarePaginator
     */
    public function list(): LengthAwarePaginator
    {
        (new UserTypeAssignment)->logListed();

        $query = new UserTypeAssignment();
        $query = $query->AcceptRequest(UserTypeAssignment::$accepted_filters)->filter();
        $query = $query->sortable(['updated_at' => 'desc']);
        $userTypeAssignments = $query->paginate(10);
        $userTypeAssignments->withQueryString();

        return $userTypeAssignments;
    }

    /**
     * Undocumented function
     *
     * @param array $attributes
     * @return UserTypeAssignment
     */
    public function create(array $attributes): UserTypeAssignment
    {
        $userTypeAssignment = UserTypeAssignment::create($attributes);

        return $userTypeAssignment;
    }

    /**
     * Undocumented function
     * 
     * @param UserTypeAssignment $user
     * @return UserTypeAssignment
     */
    public function read(UserTypeAssignment $userTypeAssignment): UserTypeAssignment
    {
        $userTypeAssignment->logViewed($userTypeAssignment);

        return $userTypeAssignment;
    }

    /**
     * Undocumented function
     *
     * @param array $attributes
     * @param UserTypeAssignment $userTypeAssignment
     * @return UserTypeAssignment
     */
    public function update(array $attributes, UserTypeAssignment $userTypeAssignment): UserTypeAssignment
    {
        $userTypeAssignment->update($attributes);

        return $userTypeAssignment;
    }

    /**
     * Undocumented function
     *
     * @param UserTypeAssignment $userTypeAssignment
     * @return void
     */
    public function delete(UserTypeAssignment $userTypeAssignment)
    {
        $userTypeAssignment->delete();
    }
}
