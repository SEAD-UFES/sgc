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
        SgcLogger::writeLog(target: 'DocumentType', action: __FUNCTION__, model: $documentType);
    }

    /**
     * Handle the DocumentType "updated" event.
     *
     * @param  \App\Models\DocumentType  $documentType
     * @return void
     */
    public function updating(DocumentType $documentType)
    {
        SgcLogger::writeLog(target: 'DocumentType', action: __FUNCTION__, model: $documentType);
    }

    /**
     * Handle the DocumentType "updated" event.
     *
     * @param  \App\Models\DocumentType  $documentType
     * @return void
     */
    public function updated(DocumentType $documentType)
    {
        SgcLogger::writeLog(target: 'DocumentType', action: __FUNCTION__, model: $documentType);
    }

    /**
     * Handle the DocumentType "deleted" event.
     *
     * @param  \App\Models\DocumentType  $documentType
     * @return void
     */
    public function deleted(DocumentType $documentType)
    {
        SgcLogger::writeLog(target: 'DocumentType', action: __FUNCTION__, model: $documentType);
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

    public function listed()
    {
        SgcLogger::writeLog(target: 'DocumentType', action: __FUNCTION__);
    }

    public function retrieved(DocumentType $approved)
    {
        SgcLogger::writeLog(target: 'DocumentType', action: __FUNCTION__, model: $approved);
    }
}
