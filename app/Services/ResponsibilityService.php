<?php

namespace App\Services;

use App\Events\ModelListed;
use App\Events\ModelRead;
use App\Models\Responsibility;
use App\Services\Dto\ResponsibilityDto;
use Illuminate\Pagination\LengthAwarePaginator;

class ResponsibilityService
{
    /**
     * Undocumented function
     *
     * @return LengthAwarePaginator<Responsibility>
     */
    public function list(): LengthAwarePaginator
    {
        ModelListed::dispatch(Responsibility::class);

        $query = Responsibility::with(['user:id,login', 'userType:id,name', 'course:id,name']);
        $query = $query->AcceptRequest(Responsibility::$acceptedFilters)->filter();
        $query = $query->sortable(['updated_at' => 'desc']);

        $responsibilities = $query->paginate(10);
        $responsibilities->withQueryString();

        return $responsibilities;
    }

    /**
     * Undocumented function
     *
     * @param ResponsibilityDto $storeResponsibilityDto
     *
     * @return Responsibility
     */
    public function create(ResponsibilityDto $storeResponsibilityDto): Responsibility
    {
        // * NULL Course breaks the SQL DB Unique Constraint ['user_id', 'user_type_id', 'course_id']
        // * Implements Composite Foreign Keys manually, handling NULL Course
        if ($storeResponsibilityDto->courseId < 1 && Responsibility::where('user_id', $storeResponsibilityDto->userId)
            ->where('user_type_id', $storeResponsibilityDto->userTypeId)
            ->where(static function ($q) {
                $q->where('course_id', '')
                    ->orWhereNull('course_id');
            })->exists()) {
            abort(409, 'O usuário já tem essa combinação cadastrada');
        }

        return Responsibility::create([
            'user_id' => $storeResponsibilityDto->userId,
            'user_type_id' => $storeResponsibilityDto->userTypeId,
            'course_id' => $storeResponsibilityDto->courseId > 0 ? $storeResponsibilityDto->courseId : null,
            'begin' => $storeResponsibilityDto->begin,
            'end' => $storeResponsibilityDto->end,
        ]);
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

        return Responsibility::with(['user:id,login', 'userType:id,name', 'course:id,name'])->find($responsibility->id);
    }

    /**
     * Undocumented function
     *
     * @param ResponsibilityDto $updateResponsibilityDto
     * @param Responsibility $responsibility
     *
     * @return Responsibility
     */
    public function update(ResponsibilityDto $updateResponsibilityDto, Responsibility $responsibility): Responsibility
    {
        if ($updateResponsibilityDto->courseId < 1 && Responsibility::where('user_id', $updateResponsibilityDto->userId)
            ->where('user_type_id', $updateResponsibilityDto->userTypeId)
            ->where(static function ($q) {
                $q->where('course_id', '')
                    ->orWhereNull('course_id');
            })->exists()) {
            abort(409, 'O usuário já tem essa combinação cadastrada');
        }

        $responsibility->update([
            'user_id' => $updateResponsibilityDto->userId,
            'user_type_id' => $updateResponsibilityDto->userTypeId,
            'course_id' => $updateResponsibilityDto->courseId > 0 ? $updateResponsibilityDto->courseId : null,
            'begin' => $updateResponsibilityDto->begin,
            'end' => $updateResponsibilityDto->end,
        ]);

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
