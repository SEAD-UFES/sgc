<?php

namespace App\Observers;

use App\Helpers\ModelActivityHelper;
use App\Logging\LoggerInterface;
use App\Models\Pole;
use Spatie\Activitylog\Models\Activity;

class PoleObserver
{
    public function __construct(private LoggerInterface $logger, private ModelActivityHelper $modelActivityHelper)
    {
    }

    /**
     * Handle the Pole "created" event.
     *
     * @param  \App\Models\Pole  $pole
     * @return void
     */
    public function created(Pole $pole)
    {
        /** @var Activity $activity */
        $activity = $this->modelActivityHelper->getModelEventActivity('created', $pole);

        $this->logger->logModelEvent($activity);
    }

    /**
     * Handle the Pole "updated" event.
     *
     * @param  \App\Models\Pole  $pole
     * @return void
     */
    public function updating(Pole $pole)
    {
        $this->logger->logModelEvent('updating', $pole);
    }

    /**
     * Handle the Pole "updated" event.
     *
     * @param  \App\Models\Pole  $pole
     * @return void
     */
    public function updated(Pole $pole)
    {
        /** @var Activity $activity */
        $activity = $this->modelActivityHelper->getModelEventActivity('updated', $pole);

        $this->logger->logModelEvent($activity);
    }

    /**
     * Handle the Pole "deleted" event.
     *
     * @param  \App\Models\Pole  $pole
     * @return void
     */
    public function deleted(Pole $pole)
    {
        /** @var Activity $activity */
        $activity = $this->modelActivityHelper->getModelEventActivity('deleted', $pole);

        $this->logger->logModelEvent($activity);
    }

    /**
     * Handle the Pole "restored" event.
     *
     * @param  \App\Models\Pole  $pole
     * @return void
     */
    public function restored(Pole $pole)
    {
    }

    /**
     * Handle the Pole "force deleted" event.
     *
     * @param  \App\Models\Pole  $pole
     * @return void
     */
    public function forceDeleted(Pole $pole)
    {
    }
}
