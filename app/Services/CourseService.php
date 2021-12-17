<?php

namespace App\Services;

use App\Models\Course;
use Illuminate\Support\Facades\DB;
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
        (new Course)->logListed();

        $query = new Course();
        $query = $query->AcceptRequest(Course::$accepted_filters)->filter();
        $query = $query->sortable(['name' => 'asc'])->with('courseType');
        $courses = $query->paginate(10);
        $courses->withQueryString();

        return $courses;
    }

    /**
     * Undocumented function
     *
     * @param array $attributes
     * @return Course
     */
    public function create(array $attributes): Course
    {
        $course = Course::create($attributes);

        return $course;
    }

    /**
     * Undocumented function
     *
     * @param Course $course
     * @return Course
     */
    public function read(Course $course): Course
    {
        $course->logFetched($course);

        return $course;
    }

    public function update(array $attributes, Course $course): Course
    {
        $course->update($attributes);

        return $course;
    }

    public function delete(Course $course)
    {
        $course->delete();
    }
}
