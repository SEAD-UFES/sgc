<?php

namespace App\Observers;

use App\Helpers\ModelActivityHelper;
use App\Logging\LoggerInterface;
use App\Models\CourseClass;
use Spatie\Activitylog\Models\Activity;

class CourseClassObserver
{
    public function __construct(private LoggerInterface $logger, private ModelActivityHelper $modelActivityHelper)
    {
    }

    /**
     * Handle the CourseClass "created" event.
     *
     * @param  CourseClass  $courseClass
     * @return void
     */
    public function created(CourseClass $courseClass)
    {
        /** @var Activity $activity */
        $activity = $this->modelActivityHelper->getModelEventActivity('created', $courseClass);

        $this->logger->logModelEvent($activity);
    }

    /**
     * Handle the CourseClass "updated" event.
     *
     * @param  CourseClass  $courseClass
     * @return void
     */
    public function updating(CourseClass $courseClass)
    {
        $this->logger->logModelEvent('updating', $courseClass);
    }

    /**
     * Handle the CourseClass "updated" event.
     *
     * @param  CourseClass  $courseClass
     * @return void
     */
    public function updated(CourseClass $courseClass)
    {
        /** @var Activity $activity */
        $activity = $this->modelActivityHelper->getModelEventActivity('updated', $courseClass);

        $this->logger->logModelEvent($activity);
    }

    /**
     * Handle the CourseClass "deleted" event.
     *
     * @param  CourseClass  $courseClass
     * @return void
     */
    public function deleted(CourseClass $courseClass)
    {
        /** @var Activity $activity */
        $activity = $this->modelActivityHelper->getModelEventActivity('deleted', $courseClass);

        $this->logger->logModelEvent($activity);
    }

    /**
     * Handle the CourseClass "restored" event.
     *
     * @param  CourseClass  $courseClass
     * @return void
     */
    public function restored(CourseClass $courseClass)
    {
    }

    /**
     * Handle the CourseClass "force deleted" event.
     *
     * @param  CourseClass  $courseClass
     * @return void
     */
    public function forceDeleted(CourseClass $courseClass)
    {
    }
}
