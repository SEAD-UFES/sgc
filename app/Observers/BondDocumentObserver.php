<?php

namespace App\Observers;

use App\Helpers\ModelActivityHelper;
use App\Logging\LoggerInterface;
use App\Models\BondDocument;
use Spatie\Activitylog\Models\Activity;

class BondDocumentObserver
{
    public function __construct(private LoggerInterface $logger, private ModelActivityHelper $modelActivityHelper)
    {
    }

    /**
     * Handle the BondDocument "created" event.
     *
     * @param  BondDocument  $bondDocument
     * @return void
     */
    public function created(BondDocument $bondDocument)
    {
        /** @var Activity $activity */
        $activity = $this->modelActivityHelper->getModelEventActivity('created', $bondDocument);

        $this->logger->logModelEvent($activity);
    }

    /**
     * Handle the BondDocument "updated" event.
     *
     * @param  BondDocument  $bondDocument
     * @return void
     */
    public function updating(BondDocument $bondDocument)
    {
        $this->logger->logModelEvent('updating', $bondDocument);
    }

    /**
     * Handle the BondDocument "updated" event.
     *
     * @param  BondDocument  $bondDocument
     * @return void
     */
    public function updated(BondDocument $bondDocument)
    {
        /** @var Activity $activity */
        $activity = $this->modelActivityHelper->getModelEventActivity('updated', $bondDocument);

        $this->logger->logModelEvent($activity);
    }

    /**
     * Handle the BondDocument "deleted" event.
     *
     * @param  BondDocument  $bondDocument
     * @return void
     */
    public function deleted(BondDocument $bondDocument)
    {
        /** @var Activity $activity */
        $activity = $this->modelActivityHelper->getModelEventActivity('deleted', $bondDocument);

        $this->logger->logModelEvent($activity);
    }

    /**
     * Handle the BondDocument "restored" event.
     *
     * @param  BondDocument  $bondDocument
     * @return void
     */
    public function restored(BondDocument $bondDocument)
    {
    }

    /**
     * Handle the BondDocument "force deleted" event.
     *
     * @param  BondDocument  $bondDocument
     * @return void
     */
    public function forceDeleted(BondDocument $bondDocument)
    {
    }
}
