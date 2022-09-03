<?php

namespace App\Observers;

use App\Helpers\ModelActivityHelper;
use App\Logging\LoggerInterface;
use App\Models\Bond;
use Spatie\Activitylog\Models\Activity;

class BondObserver
{
    public function __construct(private LoggerInterface $logger, private ModelActivityHelper $modelActivityHelper)
    {
    }

    /**
     * Handle the Bond "created" event.
     *
     * @param  Bond  $bond
     * @return void
     */
    public function created(Bond $bond)
    {
        /** @var Activity $activity */
        $activity = $this->modelActivityHelper->getModelEventActivity('created', $bond);

        $this->logger->logModelEvent($activity);
    }

    /**
     * Handle the Bond "updated" event.
     *
     * @param  Bond  $bond
     * @return void
     */
    public function updating(Bond $bond)
    {
        $this->logger->logModelEvent('updating', $bond);
    }

    /**
     * Handle the Bond "updated" event.
     *
     * @param  Bond  $bond
     * @return void
     */
    public function updated(Bond $bond)
    {
        /** @var Activity $activity */
        $activity = $this->modelActivityHelper->getModelEventActivity('updated', $bond);

        $this->logger->logModelEvent($activity);
    }

    /**
     * Handle the Bond "deleted" event.
     *
     * @param  Bond  $bond
     * @return void
     */
    public function deleted(Bond $bond)
    {
        /** @var Activity $activity */
        $activity = $this->modelActivityHelper->getModelEventActivity('deleted', $bond);

        $this->logger->logModelEvent($activity);
    }

    /**
     * Handle the Bond "restored" event.
     *
     * @param  Bond  $bond
     * @return void
     */
    public function restored(Bond $bond)
    {
    }

    /**
     * Handle the Bond "force deleted" event.
     *
     * @param  Bond  $bond
     * @return void
     */
    public function forceDeleted(Bond $bond)
    {
    }
}
