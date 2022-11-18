<?php

namespace App\Services;

use App\Events\ModelListed;
use App\Events\ModelRead;
use App\Helpers\TextHelper;
use App\Models\Course;
use App\Services\Dto\CourseDto;
use Illuminate\Pagination\LengthAwarePaginator;

class CourseService
{
    /**
     * Undocumented function
     *
     * @return LengthAwarePaginator<Course>
     */
    public function list(): LengthAwarePaginator
    {
        ModelListed::dispatch(Course::class);

        $query = new Course();
        $query = $query->AcceptRequest(Course::$acceptedFilters)->filter();
        $query = $query->sortable(['name' => 'asc']);

        $courses = $query->paginate(10);
        $courses->withQueryString();

        return $courses;
    }

    /**
     * Undocumented function
     *
     * @param CourseDto $storeCourseDto
     *
     * @return Course
     */
    public function create(CourseDto $storeCourseDto): Course
    {
        return Course::create([
            'name' => TextHelper::titleCase($storeCourseDto->name),
            'description' => TextHelper::titleCase($storeCourseDto->description),
            'degree' => $storeCourseDto->degree,
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
     * @param CourseDto $updateCourseDto
     * @param Course $course
     *
     * @return Course
     */
    public function update(CourseDto $updateCourseDto, Course $course): Course
    {
        $course->update([
            'name' => TextHelper::titleCase($updateCourseDto->name),
            'description' => TextHelper::titleCase($updateCourseDto->description),
            'degree' => $updateCourseDto->degree,
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
