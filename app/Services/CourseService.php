<?php

namespace App\Services;

use App\Events\ModelListed;
use App\Events\ModelRead;
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
        ModelListed::dispatch(Course::class);

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
     * @param array<string, string> $attributes
     *
     * @return Course
     */
    public function create(array $attributes): Course
    {
        $attributes = Arr::map($attributes, static function ($value, $key) {
            return TextHelper::titleCase($value);
        });

        $attributes = Arr::map($attributes, static function ($value, $key) {
            return $key === 'lms_url' ? mb_strtolower($value) : $value;
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
        ModelRead::dispatch($course);

        return $course;
    }

    /**
     * @param array<string, string> $attributes
     * @param Course $course
     *
     * @return Course
     */
    public function update(array $attributes, Course $course): Course
    {
        $attributes = Arr::map($attributes, static function ($value, $key) {
            return TextHelper::titleCase($value);
        });

        $attributes = Arr::map($attributes, static function ($value, $key) {
            return $key === 'lms_url' ? mb_strtolower($value) : $value;
        });

        $attributes = Arr::map($attributes, static function ($value, $key) {
            return $value === '' ? null : $value;
        });

        $course->update($attributes);

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
