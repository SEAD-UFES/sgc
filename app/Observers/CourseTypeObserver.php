<?php

namespace App\Observers;

use App\Helpers\ModelActivityHelper;
use App\Logging\LoggerInterface;
use App\Models\CourseType;
use Spatie\Activitylog\Models\Activity;

class CourseTypeObserver
{
    public function __construct(private LoggerInterface $logger, private ModelActivityHelper $modelActivityHelper)
    {
    }

    /**
     * Handle the CourseType "created" event.
     *
     * @param  CourseType  $courseType
     * @return void
     */
    public function created(CourseType $courseType)
    {
        /** @var Activity $activity */
        $activity = $this->modelActivityHelper->getModelEventActivity('created', $courseType);

        $this->logger->logModelEvent($activity);
    }

    /**
     * Handle the CourseType "updated" event.
     *
     * @param  CourseType  $courseType
     * @return void
     */
    public function updating(CourseType $courseType)
    {
        $this->logger->logModelEvent('updating', $courseType);
    }

    /**
     * Handle the CourseType "updated" event.
     *
     * @param  CourseType  $courseType
     * @return void
     */
    public function updated(CourseType $courseType)
    {
        /** @var Activity $activity */
        $activity = $this->modelActivityHelper->getModelEventActivity('updated', $courseType);

        $this->logger->logModelEvent($activity);
    }

    /**
     * Handle the CourseType "deleted" event.
     *
     * @param  CourseType  $courseType
     * @return void
     */
    public function deleted(CourseType $courseType)
    {
        /** @var Activity $activity */
        $activity = $this->modelActivityHelper->getModelEventActivity('deleted', $courseType);

        $this->logger->logModelEvent($activity);
    }

    /**
     * Handle the CourseType "restored" event.
     *
     * @param  CourseType  $courseType
     * @return void
     */
    public function restored(CourseType $courseType)
    {
    }

    /**
     * Handle the CourseType "force deleted" event.
     *
     * @param  CourseType  $courseType
     * @return void
     */
    public function forceDeleted(CourseType $courseType)
    {
    }
}
