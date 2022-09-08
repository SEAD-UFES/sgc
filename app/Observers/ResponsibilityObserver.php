<?php

namespace App\Observers;

use App\Helpers\ModelActivityHelper;
use App\Logging\LoggerInterface;
use App\Models\Responsibility;
use Spatie\Activitylog\Models\Activity;

class ResponsibilityObserver
{
    public function __construct(private LoggerInterface $logger, private ModelActivityHelper $modelActivityHelper)
    {
    }

    /**
     * Handle the Responsibility "created" event.
     *
     * @param  Responsibility  $responsibility
     * @return void
     */
    public function created(Responsibility $responsibility)
    {
        /** @var Activity $activity */
        $activity = $this->modelActivityHelper->getModelEventActivity('created', $responsibility);

        $this->logger->logModelEvent($activity);
    }

    /**
     * Handle the Responsibility "updated" event.
     *
     * @param  Responsibility  $responsibility
     * @return void
     */
    public function updating(Responsibility $responsibility)
    {
        $this->logger->logModelEvent('updating', $responsibility);
    }

    /**
     * Handle the Responsibility "updated" event.
     *
     * @param  Responsibility  $responsibility
     * @return void
     */
    public function updated(Responsibility $responsibility)
    {
        /** @var Activity $activity */
        $activity = $this->modelActivityHelper->getModelEventActivity('updated', $responsibility);

        $this->logger->logModelEvent($activity);
    }

    /**
     * Handle the Responsibility "deleted" event.
     *
     * @param  Responsibility  $responsibility
     * @return void
     */
    public function deleted(Responsibility $responsibility)
    {
        /** @var Activity $activity */
        $activity = $this->modelActivityHelper->getModelEventActivity('deleted', $responsibility);

        $this->logger->logModelEvent($activity);
    }

    /**
     * Handle the Responsibility "restored" event.
     *
     * @param  Responsibility  $responsibility
     * @return void
     */
    public function restored(Responsibility $responsibility)
    {
    }

    /**
     * Handle the Responsibility "force deleted" event.
     *
     * @param  Responsibility  $responsibility
     * @return void
     */
    public function forceDeleted(Responsibility $responsibility)
    {
    }
}
