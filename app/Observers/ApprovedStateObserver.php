<?php

namespace App\Observers;

use App\Helpers\ModelActivityHelper;
use App\Logging\LoggerInterface;
use App\Models\ApprovedState;
use Spatie\Activitylog\Models\Activity;

class ApprovedStateObserver
{
    public function __construct(private LoggerInterface $logger, private ModelActivityHelper $modelActivityHelper)
    {
    }

    /**
     * Handle the ApprovedState "created" event.
     *
     * @param  ApprovedState  $approvedState
     * @return void
     */
    public function created(ApprovedState $approvedState)
    {
        /** @var Activity $activity */
        $activity = $this->modelActivityHelper->getModelEventActivity('created', $approvedState);

        $this->logger->logModelEvent($activity);
    }

    /**
     * Handle the ApprovedState "updated" event.
     *
     * @param  ApprovedState  $approvedState
     * @return void
     */
    public function updating(ApprovedState $approvedState)
    {
        $this->logger->logModelEvent('updating', $approvedState);
    }

    /**
     * Handle the ApprovedState "updated" event.
     *
     * @param  ApprovedState  $approvedState
     * @return void
     */
    public function updated(ApprovedState $approvedState)
    {
        /** @var Activity $activity */
        $activity = $this->modelActivityHelper->getModelEventActivity('updated', $approvedState);

        $this->logger->logModelEvent($activity);
    }

    /**
     * Handle the ApprovedState "deleted" event.
     *
     * @param  ApprovedState  $approvedState
     * @return void
     */
    public function deleted(ApprovedState $approvedState)
    {
        /** @var Activity $activity */
        $activity = $this->modelActivityHelper->getModelEventActivity('deleted', $approvedState);

        $this->logger->logModelEvent($activity);
    }

    /**
     * Handle the ApprovedState "restored" event.
     *
     * @param  ApprovedState  $approvedState
     * @return void
     */
    public function restored(ApprovedState $approvedState)
    {
    }

    /**
     * Handle the ApprovedState "force deleted" event.
     *
     * @param  ApprovedState  $approvedState
     * @return void
     */
    public function forceDeleted(ApprovedState $approvedState)
    {
    }
}
