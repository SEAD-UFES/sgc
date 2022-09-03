<?php

namespace App\Observers;

use App\Helpers\ModelActivityHelper;
use App\Logging\LoggerInterface;
use App\Models\Course;
use Spatie\Activitylog\Models\Activity;

class CourseObserver
{
    public function __construct(private LoggerInterface $logger, private ModelActivityHelper $modelActivityHelper)
    {
    }

    /**
     * Handle the Course "created" event.
     *
     * @param  Course  $course
     * @return void
     */
    public function created(Course $course)
    {
        /** @var Activity $activity */
        $activity = $this->modelActivityHelper->getModelEventActivity('created', $course);

        $this->logger->logModelEvent($activity);
    }

    /**
     * Handle the Course "updated" event.
     *
     * @param  Course  $course
     * @return void
     */
    public function updating(Course $course)
    {
        $this->logger->logModelEvent('updating', $course);
    }

    /**
     * Handle the Course "updated" event.
     *
     * @param  Course  $course
     * @return void
     */
    public function updated(Course $course)
    {
        /** @var Activity $activity */
        $activity = $this->modelActivityHelper->getModelEventActivity('updated', $course);

        $this->logger->logModelEvent($activity);
    }

    /**
     * Handle the Course "deleted" event.
     *
     * @param  Course  $course
     * @return void
     */
    public function deleted(Course $course)
    {
        /** @var Activity $activity */
        $activity = $this->modelActivityHelper->getModelEventActivity('deleted', $course);

        $this->logger->logModelEvent($activity);
    }

    /**
     * Handle the Course "restored" event.
     *
     * @param  Course  $course
     * @return void
     */
    public function restored(Course $course)
    {
    }

    /**
     * Handle the Course "force deleted" event.
     *
     * @param  Course  $course
     * @return void
     */
    public function forceDeleted(Course $course)
    {
    }
}
