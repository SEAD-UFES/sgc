<?php

namespace App\Services;

use App\Models\Course;
use App\CustomClasses\SgcLogger;
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
        SgcLogger::writeLog(target: 'Course', action: 'index');

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
        $course = new Course;

        $course->name = $attributes['name'];
        $course->description = $attributes['description'];
        $course->course_type_id = $attributes['courseTypes'];
        $course->begin = $attributes['begin'];
        $course->end = $attributes['end'];

        SgcLogger::writeLog(target: $course, action: 'store');

        $course->save();
        
        return $course;
    }

    public function update(array $attributes, Course $course): Course
    {
        $course->name = $attributes['name'];
        $course->description =  $attributes['description'];
        $course->course_type_id = $attributes['courseTypes'];
        $course->begin = $attributes['begin'];
        $course->end = $attributes['end'];
        
        SgcLogger::writeLog(target: $course, action: 'update');
        
        DB::transaction(function () use ($course) {
            $course->save();
        });

        return $course;
    }

    public function delete(Course $course)
    {
        SgcLogger::writeLog(target: $course, action: 'destroy');

        $course->delete();
    }

}