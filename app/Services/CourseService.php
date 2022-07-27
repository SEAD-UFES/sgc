<?php

namespace App\Services;

use App\Helpers\TextHelper;
use App\Models\Course;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;

class CourseService
{
    /**
     * Undocumented function
     *
     * @return LengthAwarePaginator
     */
    public function list(): LengthAwarePaginator
    {
        (new Course())->logListed();

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
     *
     * @return Course
     */
    public function create(array $attributes): Course
    {
        $attributes = Arr::map($attributes, static function ($value, $key) {
            return TextHelper::titleCase($value);
        });

        $attributes = Arr::map($attributes, static function ($value, $key) {
            return $value === '' ? null : $value;
        });

        return Course::create($attributes);
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
        $course->logFetched($course);

        return $course;
    }

    public function update(array $attributes, Course $course): Course
    {
        $attributes = Arr::map($attributes, static function ($value, $key) {
            return TextHelper::titleCase($value);
        });

        $attributes = Arr::map($attributes, static function ($value, $key) {
            return $value === '' ? null : $value;
        });

        $course->update($attributes);

        return $course;
    }

    public function delete(Course $course)
    {
        $course->delete();
    }
}
