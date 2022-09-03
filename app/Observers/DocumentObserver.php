<?php

namespace App\Observers;

use App\Helpers\ModelActivityHelper;
use App\Logging\LoggerInterface;
use App\Models\Document;
use Spatie\Activitylog\Models\Activity;

class DocumentObserver
{
    public function __construct(private LoggerInterface $logger, private ModelActivityHelper $modelActivityHelper)
    {
    }

    /**
     * Handle the Document "created" event.
     *
     * @param  Document  $document
     * @return void
     */
    public function created(Document $document)
    {
        /** @var Activity $activity */
        $activity = $this->modelActivityHelper->getModelEventActivity('created', $document);

        $this->logger->logModelEvent($activity);
    }

    /**
     * Handle the Document "updated" event.
     *
     * @param  Document  $document
     * @return void
     */
    public function updating(Document $document)
    {
        $this->logger->logModelEvent('updating', $document);
    }

    /**
     * Handle the Document "updated" event.
     *
     * @param  Document  $document
     * @return void
     */
    public function updated(Document $document)
    {
        /** @var Activity $activity */
        $activity = $this->modelActivityHelper->getModelEventActivity('updated', $document);

        $this->logger->logModelEvent($activity);
    }

    /**
     * Handle the Document "deleted" event.
     *
     * @param  Document  $document
     * @return void
     */
    public function deleted(Document $document)
    {
        /** @var Activity $activity */
        $activity = $this->modelActivityHelper->getModelEventActivity('deleted', $document);

        $this->logger->logModelEvent($activity);
    }

    /**
     * Handle the Document "restored" event.
     *
     * @param  Document  $document
     * @return void
     */
    public function restored(Document $document)
    {
    }

    /**
     * Handle the Document "force deleted" event.
     *
     * @param  Document  $document
     * @return void
     */
    public function forceDeleted(Document $document)
    {
    }
}
