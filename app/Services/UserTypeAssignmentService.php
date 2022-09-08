<?php

namespace App\Services;

use App\Events\ModelListed;
use App\Events\ModelRead;
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
        ModelListed::dispatch(UserTypeAssignment::class);

        $query = UserTypeAssignment::with(['user:id,email', 'userType:id,name', 'course:id,name']);
        $query = $query->AcceptRequest(UserTypeAssignment::$accepted_filters)->filter();
        $query = $query->sortable(['updated_at' => 'desc']);

        $userTypeAssignments = $query->paginate(10);
        $userTypeAssignments->withQueryString();

        return $userTypeAssignments;
    }

    /**
     * Undocumented function
     *
     * @param array<string> $attributes
     *
     * @return UserTypeAssignment
     */
    public function create(array $attributes): UserTypeAssignment
    {
        // * NULL Course breaks the SQL Unique Constraint ['user_id', 'user_type_id', 'course_id']
        // TODO: Implement Composite Foreign Keys manually, handling NULL Course
        return UserTypeAssignment::create($attributes);
    }

    /**
     * Undocumented function
     *
     * @param UserTypeAssignment $userTypeAssignment
     *
     * @return UserTypeAssignment|null
     */
    public function read(UserTypeAssignment $userTypeAssignment): UserTypeAssignment|null
    {
        ModelRead::dispatch($userTypeAssignment);

        return UserTypeAssignment::with(['user:id,email', 'userType:id,name', 'course:id,name'])->find($userTypeAssignment->id);
    }

    /**
     * Undocumented function
     *
     * @param array<string> $attributes
     * @param UserTypeAssignment $userTypeAssignment
     *
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
     *
     * @return void
     */
    public function delete(UserTypeAssignment $userTypeAssignment)
    {
        $userTypeAssignment->delete();
    }
}
