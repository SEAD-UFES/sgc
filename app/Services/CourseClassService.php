<?php

namespace App\Services;

use App\Events\ModelListed;
use App\Events\ModelRead;
use App\Models\CourseClass;
use App\Models\User;
use App\Services\Dto\CourseClassDto;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\Activitylog\Models\Activity;

class CourseClassService
{
    /**
     * @return LengthAwarePaginator<CourseClass>
     */
    public function list(): LengthAwarePaginator
    {
        ModelListed::dispatch(CourseClass::class);

        $query = CourseClass::select([
            'course_classes.id',
            'course_classes.course_id',
            'course_classes.code',
            'course_classes.name',
            'course_classes.cpp',
            'course_classes.created_at',
            'course_classes.updated_at',
        ])
            ->with('course')
            ->AcceptRequest(CourseClass::$acceptedFilters)->filter()
            ->sortable()
            ->orderByDesc('course_classes.updated_at');

        return $query->paginate(10)
            ->withQueryString();
    }

    public function create(CourseClassDto $dto): CourseClass
    {
        $courseClass = new CourseClass([
            'course_id' => $dto->courseId,
            'code' => $dto->code,
            'name' => $dto->name,
            'cpp' => $dto->cpp,
        ]);
        $courseClass->save();

        return $courseClass;
    }

    public function update(CourseClassDto $dto, CourseClass $courseClass): CourseClass
    {
        $courseClass->course_id = $dto->courseId;
        $courseClass->code = $dto->code;
        $courseClass->name = $dto->name;
        $courseClass->cpp = $dto->cpp;
        $courseClass->save();

        return $courseClass;
    }

    public function read(CourseClass $courseClass): CourseClass
    {
        ModelRead::dispatch($courseClass);
        $courseClass->load('course');

        $activity = $this->getActivity($courseClass);

        foreach ($activity as $property => $value) {
            $courseClass->$property = $value;
        }

        return $courseClass;
    }

    public function delete(CourseClass $courseClass): void
    {
        $courseClass->delete();
    }

    /**
     * Undocumented function
     *
     * @param CourseClass $courseClass
     *
     * @return array<string, User|Carbon|null>
     */
    private function getActivity(CourseClass $courseClass): array
    {
        $activityCreated = Activity::select('activity_log.causer_id', 'activity_log.created_at')
            ->where('activity_log.subject_id', $courseClass->id)
            ->where('activity_log.subject_type', CourseClass::class)
            ->where('activity_log.event', 'created')
            ->where('activity_log.causer_type', User::class)
            ->orderBy('activity_log.id', 'desc')
            ->first();

        $createdBy = User::find($activityCreated?->causer_id);
        $createdOn = $activityCreated?->created_at;

        $activityUpdated = Activity::select('activity_log.causer_id', 'activity_log.created_at')
            ->where('activity_log.subject_id', $courseClass->id)
            ->where('activity_log.subject_type', CourseClass::class)
            ->where('activity_log.event', 'updated')
            ->where('activity_log.causer_type', User::class)
            ->orderBy('activity_log.id', 'desc')
            ->first();

        $updatedBy = User::find($activityUpdated?->causer_id);
        $updatedOn = $activityUpdated?->created_at;

        return [
            'createdBy' => $createdBy,
            'createdOn' => $createdOn,
            'updatedBy' => $updatedBy,
            'updatedOn' => $updatedOn,
        ];
    }
}
