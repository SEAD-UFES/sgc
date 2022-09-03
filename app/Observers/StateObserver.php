<?php

namespace App\Observers;

use App\Helpers\ModelActivityHelper;
use App\Logging\LoggerInterface;
use App\Models\State;
use Spatie\Activitylog\Models\Activity;

class StateObserver
{
    public function __construct(private LoggerInterface $logger, private ModelActivityHelper $modelActivityHelper)
    {
    }

    /**
     * Handle the State "created" event.
     *
     * @param  State  $state
     * @return void
     */
    public function created(State $state)
    {
        /** @var Activity $activity */
        $activity = $this->modelActivityHelper->getModelEventActivity('created', $state);

        $this->logger->logModelEvent($activity);
    }

    /**
     * Handle the State "updated" event.
     *
     * @param  State  $state
     * @return void
     */
    public function updating(State $state)
    {
        $this->logger->logModelEvent('updating', $state);
    }

    /**
     * Handle the State "updated" event.
     *
     * @param  State  $state
     * @return void
     */
    public function updated(State $state)
    {
        /** @var Activity $activity */
        $activity = $this->modelActivityHelper->getModelEventActivity('updated', $state);

        $this->logger->logModelEvent($activity);
    }

    /**
     * Handle the State "deleted" event.
     *
     * @param  State  $state
     * @return void
     */
    public function deleted(State $state)
    {
        /** @var Activity $activity */
        $activity = $this->modelActivityHelper->getModelEventActivity('deleted', $state);

        $this->logger->logModelEvent($activity);
    }

    /**
     * Handle the State "restored" event.
     *
     * @param  State  $state
     * @return void
     */
    public function restored(State $state)
    {
    }

    /**
     * Handle the State "force deleted" event.
     *
     * @param  State  $state
     * @return void
     */
    public function forceDeleted(State $state)
    {
    }
}
