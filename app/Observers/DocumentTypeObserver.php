<?php

namespace App\Observers;

use App\CustomClasses\SgcLogger;
use App\Models\DocumentType;

class DocumentTypeObserver
{
    /**
     * Handle the DocumentType "created" event.
     *
     * @param  \App\Models\DocumentType  $documentType
     * @return void
     */
    public function created(DocumentType $documentType)
    {
        SgcLogger::writeLog(target: 'DocumentType', action: 'created', model: $documentType);
    }

    /**
     * Handle the DocumentType "updated" event.
     *
     * @param  \App\Models\DocumentType  $documentType
     * @return void
     */
    public function updated(DocumentType $documentType)
    {
        SgcLogger::writeLog(target: 'DocumentType', action: 'updated', model: $documentType);
    }

    /**
     * Handle the DocumentType "deleted" event.
     *
     * @param  \App\Models\DocumentType  $documentType
     * @return void
     */
    public function deleted(DocumentType $documentType)
    {
        SgcLogger::writeLog(target: 'DocumentType', action: 'deleted', model: $documentType);
    }

    /**
     * Handle the DocumentType "restored" event.
     *
     * @param  \App\Models\DocumentType  $documentType
     * @return void
     */
    public function restored(DocumentType $documentType)
    {
        //
    }

    /**
     * Handle the DocumentType "force deleted" event.
     *
     * @param  \App\Models\DocumentType  $documentType
     * @return void
     */
    public function forceDeleted(DocumentType $documentType)
    {
        //
    }
}
