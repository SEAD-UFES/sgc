<?php

namespace App\Services;

use App\CustomClasses\SgcLogger;
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
        SgcLogger::writeLog(target: 'userTypeAssignment', action: 'index');

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
        $userTypeAssignment = new UserTypeAssignment();
        $userTypeAssignment->user_id = $attributes['user_id'];
        $userTypeAssignment->user_type_id = $attributes['userType_id'];
        $userTypeAssignment->course_id = $attributes['course_id'];
        $userTypeAssignment->begin = $attributes['begin'];
        $userTypeAssignment->end = $attributes['end'];

        SgcLogger::writeLog(target: $userTypeAssignment, action: 'store');
        $userTypeAssignment->save();

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
        $userTypeAssignment->user_id = $attributes['user_id'];
        $userTypeAssignment->user_type_id = $attributes['userType_id'];
        $userTypeAssignment->course_id = $attributes['course_id'];
        $userTypeAssignment->begin = $attributes['begin'];
        $userTypeAssignment->end = $attributes['end'];

        SgcLogger::writeLog(target: $userTypeAssignment, action: 'update');

        $userTypeAssignment->save();

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
        SgcLogger::writeLog(target: $userTypeAssignment, action: 'destroy');

        $userTypeAssignment->delete();
    }
}
