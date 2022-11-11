<?php

namespace App\Services;

use App\Events\ModelListed;
use App\Events\ModelRead;
use App\Helpers\TextHelper;
use App\Models\Course;
use App\Services\Dto\StoreCourseDto;
use App\Services\Dto\UpdateCourseDto;
use Illuminate\Pagination\LengthAwarePaginator;

class CourseService
{
    /**
     * Undocumented function
     *
     * @return LengthAwarePaginator
     */
    public function list(): LengthAwarePaginator
    {
        ModelListed::dispatch(Course::class);

        $query = new Course();
        $query = $query->AcceptRequest(Course::$accepted_filters)->filter();
        $query = $query->sortable(['name' => 'asc']);

        $courses = $query->paginate(10);
        $courses->withQueryString();

        return $courses;
    }

    /**
     * Undocumented function
     *
     * @param StoreCourseDto $storeCourseDto
     *
     * @return Course
     */
    public function create(StoreCourseDto $storeCourseDto): Course
    {
        return Course::create([
            'name' => TextHelper::titleCase($storeCourseDto->name),
            'description' => TextHelper::titleCase($storeCourseDto->description),
            'course_type_id' => $storeCourseDto->courseTypeId,
            'begin' => $storeCourseDto->begin,
            'end' => $storeCourseDto->end,
            'lms_url' => mb_strtolower($storeCourseDto->lmsUrl),
        ]);
    }

    /**
     * Undocumented function
     *
     * @param Course $course
     *
     * @return Course
     */
    public function read(Course $course): Course
    {
        ModelRead::dispatch($course);

        return $course;
    }

    /**
     * @param UpdateCourseDto $updateCourseDto
     * @param Course $course
     *
     * @return Course
     */
    public function update(UpdateCourseDto $updateCourseDto, Course $course): Course
    {
        $course->update([
            'name' => TextHelper::titleCase($updateCourseDto->name),
            'description' => TextHelper::titleCase($updateCourseDto->description),
            'course_type_id' => $updateCourseDto->courseTypeId,
            'begin' => $updateCourseDto->begin,
            'end' => $updateCourseDto->end,
            'lms_url' => mb_strtolower($updateCourseDto->lmsUrl),
        ]);

        return $course;
    }

    /**
     * @param Course $course
     *
     * @return void
     */
    public function delete(Course $course): void
    {
        $course->delete();
    }
}
