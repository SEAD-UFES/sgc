<?php

namespace App\Observers;

use App\CustomClasses\SgcLogger;
use App\Models\Document;

class DocumentObserver
{
    /**
     * Handle the Document "created" event.
     *
     * @param  \App\Models\Document  $document
     * @return void
     */
    public function created(Document $document)
    {
        SgcLogger::writeLog(target: 'Document', action: __FUNCTION__, model: $document);
    }

    /**
     * Handle the Document "updated" event.
     *
     * @param  \App\Models\Document  $document
     * @return void
     */
    public function updating(Document $document)
    {
        SgcLogger::writeLog(target: 'Document', action: __FUNCTION__, model: $document);
    }
    /**
     * Handle the Document "updated" event.
     *
     * @param  \App\Models\Document  $document
     * @return void
     */
    public function updated(Document $document)
    {
        SgcLogger::writeLog(target: 'Document', action: __FUNCTION__, model: $document);
    }

    /**
     * Handle the Document "deleted" event.
     *
     * @param  \App\Models\Document  $document
     * @return void
     */
    public function deleted(Document $document)
    {
        SgcLogger::writeLog(target: 'Document', action: __FUNCTION__, model: $document);
    }

    /**
     * Handle the Document "restored" event.
     *
     * @param  \App\Models\Document  $document
     * @return void
     */
    public function restored(Document $document)
    {
        //
    }

    /**
     * Handle the Document "force deleted" event.
     *
     * @param  \App\Models\Document  $document
     * @return void
     */
    public function forceDeleted(Document $document)
    {
        //
    }

    public function listed()
    {
        SgcLogger::writeLog(target: 'Document', action: __FUNCTION__);
    }

    public function fetched(Document $document)
    {
        SgcLogger::writeLog(target: class_basename($document->documentable_type), action: __FUNCTION__, model: $document);
    }
}
