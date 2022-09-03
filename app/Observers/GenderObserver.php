<?php

namespace App\Observers;

use App\Helpers\ModelActivityHelper;
use App\Logging\LoggerInterface;
use App\Models\Gender;
use Spatie\Activitylog\Models\Activity;

class GenderObserver
{
    public function __construct(private LoggerInterface $logger, private ModelActivityHelper $modelActivityHelper)
    {
    }

    /**
     * Handle the Gender "created" event.
     *
     * @param  \App\Models\Gender  $gender
     * @return void
     */
    public function created(Gender $gender)
    {
        /** @var Activity $activity */
        $activity = $this->modelActivityHelper->getModelEventActivity('created', $gender);

        $this->logger->logModelEvent($activity);
    }

    /**
     * Handle the Gender "updated" event.
     *
     * @param  \App\Models\Gender  $gender
     * @return void
     */
    public function updating(Gender $gender)
    {
        $this->logger->logModelEvent('updating', $gender);
    }
    
    /**
     * Handle the Gender "updated" event.
     *
     * @param  \App\Models\Gender  $gender
     * @return void
     */
    public function updated(Gender $gender)
    {
        /** @var Activity $activity */
        $activity = $this->modelActivityHelper->getModelEventActivity('updated', $gender);

        $this->logger->logModelEvent($activity);
    }

    /**
     * Handle the Gender "deleted" event.
     *
     * @param  \App\Models\Gender  $gender
     * @return void
     */
    public function deleted(Gender $gender)
    {
        /** @var Activity $activity */
        $activity = $this->modelActivityHelper->getModelEventActivity('deleted', $gender);

        $this->logger->logModelEvent($activity);
    }

    /**
     * Handle the Gender "restored" event.
     *
     * @param  \App\Models\Gender  $gender
     * @return void
     */
    public function restored(Gender $gender)
    {
    }

    /**
     * Handle the Gender "force deleted" event.
     *
     * @param  \App\Models\Gender  $gender
     * @return void
     */
    public function forceDeleted(Gender $gender)
    {
    }
}
