<?php

namespace App\Observers;

use App\Helpers\ModelActivityHelper;
use App\Logging\LoggerInterface;
use App\Models\Approved;
use Spatie\Activitylog\Models\Activity;

class ApprovedObserver
{
    public function __construct(private LoggerInterface $logger, private ModelActivityHelper $modelActivityHelper)
    {
    }

    /**
     * Handle the Approved "created" event.
     *
     * @param  Approved  $approved
     * @return void
     */
    public function created(Approved $approved)
    {
        /** @var Activity $activity */
        $activity = $this->modelActivityHelper->getModelEventActivity('created', $approved);

        $this->logger->logModelEvent($activity);
    }

    /**
     * Handle the Approved "updated" event.
     *
     * @param  Approved  $approved
     * @return void
     */
    public function updating(Approved $approved)
    {
        $this->logger->logModelEvent('updating', $approved);
    }

    /**
     * Handle the Approved "updated" event.
     *
     * @param  Approved  $approved
     * @return void
     */
    public function updated(Approved $approved)
    {
        /** @var Activity $activity */
        $activity = $this->modelActivityHelper->getModelEventActivity('updated', $approved);

        $this->logger->logModelEvent($activity);
    }

    /**
     * Handle the Approved "deleted" event.
     *
     * @param  Approved  $approved
     * @return void
     */
    public function deleted(Approved $approved)
    {
        /** @var Activity $activity */
        $activity = $this->modelActivityHelper->getModelEventActivity('deleted', $approved);

        $this->logger->logModelEvent($activity);
    }

    /**
     * Handle the Approved "restored" event.
     *
     * @param  Approved  $approved
     * @return void
     */
    public function restored(Approved $approved)
    {
    }

    /**
     * Handle the Approved "force deleted" event.
     *
     * @param  Approved  $approved
     * @return void
     */
    public function forceDeleted(Approved $approved)
    {
    }
}
