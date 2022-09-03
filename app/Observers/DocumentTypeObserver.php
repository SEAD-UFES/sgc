<?php

namespace App\Observers;

use App\Helpers\ModelActivityHelper;
use App\Logging\LoggerInterface;
use App\Models\DocumentType;
use Spatie\Activitylog\Models\Activity;

class DocumentTypeObserver
{
    public function __construct(private LoggerInterface $logger, private ModelActivityHelper $modelActivityHelper)
    {
    }

    /**
     * Handle the DocumentType "created" event.
     *
     * @param  DocumentType  $documentType
     * @return void
     */
    public function created(DocumentType $documentType)
    {
        /** @var Activity $activity */
        $activity = $this->modelActivityHelper->getModelEventActivity('created', $documentType);

        $this->logger->logModelEvent($activity);
    }

    /**
     * Handle the DocumentType "updated" event.
     *
     * @param  DocumentType  $documentType
     * @return void
     */
    public function updating(DocumentType $documentType)
    {
        $this->logger->logModelEvent('updating', $documentType);
    }

    /**
     * Handle the DocumentType "updated" event.
     *
     * @param  DocumentType  $documentType
     * @return void
     */
    public function updated(DocumentType $documentType)
    {
        /** @var Activity $activity */
        $activity = $this->modelActivityHelper->getModelEventActivity('updated', $documentType);

        $this->logger->logModelEvent($activity);
    }

    /**
     * Handle the DocumentType "deleted" event.
     *
     * @param  DocumentType  $documentType
     * @return void
     */
    public function deleted(DocumentType $documentType)
    {
        /** @var Activity $activity */
        $activity = $this->modelActivityHelper->getModelEventActivity('deleted', $documentType);

        $this->logger->logModelEvent($activity);
    }

    /**
     * Handle the DocumentType "restored" event.
     *
     * @param  DocumentType  $documentType
     * @return void
     */
    public function restored(DocumentType $documentType)
    {
    }

    /**
     * Handle the DocumentType "force deleted" event.
     *
     * @param  DocumentType  $documentType
     * @return void
     */
    public function forceDeleted(DocumentType $documentType)
    {
    }
}
