<?php

namespace App\Services;

use App\Events\ModelListed;
use App\Events\ModelRead;
use App\Models\UserTypeAssignment;
use Illuminate\Pagination\LengthAwarePaginator;

class ResponsibilityService
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

        $responsibilities = $query->paginate(10);
        $responsibilities->withQueryString();

        return $responsibilities;
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
     * @param UserTypeAssignment $responsibility
     *
     * @return UserTypeAssignment|null
     */
    public function read(UserTypeAssignment $responsibility): UserTypeAssignment|null
    {
        ModelRead::dispatch($responsibility);

        return UserTypeAssignment::with(['user:id,email', 'userType:id,name', 'course:id,name'])->find($responsibility->id);
    }

    /**
     * Undocumented function
     *
     * @param array<string> $attributes
     * @param UserTypeAssignment $responsibility
     *
     * @return UserTypeAssignment
     */
    public function update(array $attributes, UserTypeAssignment $responsibility): UserTypeAssignment
    {
        $responsibility->update($attributes);

        return $responsibility;
    }

    /**
     * Undocumented function
     *
     * @param UserTypeAssignment $responsibility
     *
     * @return void
     */
    public function delete(UserTypeAssignment $responsibility)
    {
        $responsibility->delete();
    }
}
