<?php

namespace App\Observers;

use App\Helpers\SgcLogHelper;
use App\Models\Course;

class CourseObserver
{
    /**
     * Handle the Course "created" event.
     *
     * @param  \App\Models\Course  $course
     * @return void
     */
    public function created(Course $course)
    {
        SgcLogHelper::writeLog(target: 'Course', action: __FUNCTION__, model_json: $course->toJson(JSON_UNESCAPED_UNICODE));
    }

    /**
     * Handle the Course "updated" event.
     *
     * @param  \App\Models\Course  $course
     * @return void
     */
    public function updating(Course $course)
    {
        SgcLogHelper::writeLog(target: 'Course', action: __FUNCTION__, model_json: json_encode($course->getOriginal(), JSON_UNESCAPED_UNICODE));
    }

    /**
     * Handle the Course "updated" event.
     *
     * @param  \App\Models\Course  $course
     * @return void
     */
    public function updated(Course $course)
    {
        SgcLogHelper::writeLog(target: 'Course', action: __FUNCTION__, model_json: $course->toJson(JSON_UNESCAPED_UNICODE));
    }

    /**
     * Handle the Course "deleted" event.
     *
     * @param  \App\Models\Course  $course
     * @return void
     */
    public function deleted(Course $course)
    {
        SgcLogHelper::writeLog(target: 'Course', action: __FUNCTION__, model_json: $course->toJson(JSON_UNESCAPED_UNICODE));
    }

    /**
     * Handle the Course "restored" event.
     *
     * @param  \App\Models\Course  $course
     * @return void
     */
    public function restored(Course $course)
    {
        //
    }

    /**
     * Handle the Course "force deleted" event.
     *
     * @param  \App\Models\Course  $course
     * @return void
     */
    public function forceDeleted(Course $course)
    {
        //
    }

    public function listed()
    {
        SgcLogHelper::writeLog(target: 'Course', action: __FUNCTION__);
    }

    public function fetched(Course $course)
    {
        SgcLogHelper::writeLog(target: 'Course', action: __FUNCTION__, model_json: $course->toJson(JSON_UNESCAPED_UNICODE));
    }
}
