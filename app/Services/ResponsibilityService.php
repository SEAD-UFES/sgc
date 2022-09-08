<?php

namespace App\Services;

use App\Events\ModelListed;
use App\Events\ModelRead;
use App\Models\Responsibility;
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
        ModelListed::dispatch(Responsibility::class);

        $query = Responsibility::with(['user:id,email', 'userType:id,name', 'course:id,name']);
        $query = $query->AcceptRequest(Responsibility::$accepted_filters)->filter();
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
     * @return Responsibility
     */
    public function create(array $attributes): Responsibility
    {
        // * NULL Course breaks the SQL Unique Constraint ['user_id', 'user_type_id', 'course_id']
        // TODO: Implement Composite Foreign Keys manually, handling NULL Course
        return Responsibility::create($attributes);
    }

    /**
     * Undocumented function
     *
     * @param Responsibility $responsibility
     *
     * @return Responsibility|null
     */
    public function read(Responsibility $responsibility): Responsibility|null
    {
        ModelRead::dispatch($responsibility);

        return Responsibility::with(['user:id,email', 'userType:id,name', 'course:id,name'])->find($responsibility->id);
    }

    /**
     * Undocumented function
     *
     * @param array<string> $attributes
     * @param Responsibility $responsibility
     *
     * @return Responsibility
     */
    public function update(array $attributes, Responsibility $responsibility): Responsibility
    {
        $responsibility->update($attributes);

        return $responsibility;
    }

    /**
     * Undocumented function
     *
     * @param Responsibility $responsibility
     *
     * @return void
     */
    public function delete(Responsibility $responsibility)
    {
        $responsibility->delete();
    }
}
