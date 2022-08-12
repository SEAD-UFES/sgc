<?php

namespace App\Observers;

use App\Helpers\SgcLogHelper;
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
        SgcLogHelper::writeLog(target: 'Document', action: __FUNCTION__, model_json: $document->toJson(JSON_UNESCAPED_UNICODE));
    }

    /**
     * Handle the Document "updated" event.
     *
     * @param  \App\Models\Document  $document
     * @return void
     */
    public function updating(Document $document)
    {
        SgcLogHelper::writeLog(target: 'Document', action: __FUNCTION__, model_json: json_encode($document->getOriginal(), JSON_UNESCAPED_UNICODE));
    }

    /**
     * Handle the Document "updated" event.
     *
     * @param  \App\Models\Document  $document
     * @return void
     */
    public function updated(Document $document)
    {
        SgcLogHelper::writeLog(target: 'Document', action: __FUNCTION__, model_json: $document->toJson(JSON_UNESCAPED_UNICODE));
    }

    /**
     * Handle the Document "deleted" event.
     *
     * @param  \App\Models\Document  $document
     * @return void
     */
    public function deleted(Document $document)
    {
        SgcLogHelper::writeLog(target: 'Document', action: __FUNCTION__, model_json: $document->toJson(JSON_UNESCAPED_UNICODE));
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
        SgcLogHelper::writeLog(target: 'Document', action: __FUNCTION__);
    }

    public function fetched(Document $document)
    {
        SgcLogHelper::writeLog(target: class_basename($document->documentable_type), action: __FUNCTION__, model_json: $document->makeHidden('file_data')->toJson(JSON_UNESCAPED_UNICODE));
    }
}
