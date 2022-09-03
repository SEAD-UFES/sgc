<?php

namespace App\Observers;

use App\Helpers\ModelActivityHelper;
use App\Logging\LoggerInterface;
use App\Models\MaritalStatus;
use Spatie\Activitylog\Models\Activity;

class MaritalStatusObserver
{
    public function __construct(private LoggerInterface $logger, private ModelActivityHelper $modelActivityHelper)
    {
    }

    /**
     * Handle the MaritalStatus "created" event.
     *
     * @param  MaritalStatus  $maritalStatus
     * @return void
     */
    public function created(MaritalStatus $maritalStatus)
    {
        /** @var Activity $activity */
        $activity = $this->modelActivityHelper->getModelEventActivity('created', $maritalStatus);

        $this->logger->logModelEvent($activity);
    }

    /**
     * Handle the MaritalStatus "updated" event.
     *
     * @param  MaritalStatus  $maritalStatus
     * @return void
     */
    public function updating(MaritalStatus $maritalStatus)
    {
        $this->logger->logModelEvent('updating', $maritalStatus);
    }

    /**
     * Handle the MaritalStatus "updated" event.
     *
     * @param  MaritalStatus  $maritalStatus
     * @return void
     */
    public function updated(MaritalStatus $maritalStatus)
    {
        /** @var Activity $activity */
        $activity = $this->modelActivityHelper->getModelEventActivity('updated', $maritalStatus);

        $this->logger->logModelEvent($activity);
    }

    /**
     * Handle the MaritalStatus "deleted" event.
     *
     * @param  MaritalStatus  $maritalStatus
     * @return void
     */
    public function deleted(MaritalStatus $maritalStatus)
    {
        /** @var Activity $activity */
        $activity = $this->modelActivityHelper->getModelEventActivity('deleted', $maritalStatus);

        $this->logger->logModelEvent($activity);
    }

    /**
     * Handle the MaritalStatus "restored" event.
     *
     * @param  MaritalStatus  $maritalStatus
     * @return void
     */
    public function restored(MaritalStatus $maritalStatus)
    {
    }

    /**
     * Handle the MaritalStatus "force deleted" event.
     *
     * @param  MaritalStatus  $maritalStatus
     * @return void
     */
    public function forceDeleted(MaritalStatus $maritalStatus)
    {
    }
}
