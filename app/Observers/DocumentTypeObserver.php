<?php

namespace App\Observers;

use App\Helpers\SgcLogHelper;
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
        SgcLogHelper::writeLog(target: 'DocumentType', action: __FUNCTION__, model_json: $documentType->toJson(JSON_UNESCAPED_UNICODE));
    }

    /**
     * Handle the DocumentType "updated" event.
     *
     * @param  \App\Models\DocumentType  $documentType
     * @return void
     */
    public function updating(DocumentType $documentType)
    {
        SgcLogHelper::writeLog(target: 'DocumentType', action: __FUNCTION__, model_json: json_encode($documentType->getOriginal(), JSON_UNESCAPED_UNICODE));
    }

    /**
     * Handle the DocumentType "updated" event.
     *
     * @param  \App\Models\DocumentType  $documentType
     * @return void
     */
    public function updated(DocumentType $documentType)
    {
        SgcLogHelper::writeLog(target: 'DocumentType', action: __FUNCTION__, model_json: $documentType->toJson(JSON_UNESCAPED_UNICODE));
    }

    /**
     * Handle the DocumentType "deleted" event.
     *
     * @param  \App\Models\DocumentType  $documentType
     * @return void
     */
    public function deleted(DocumentType $documentType)
    {
        SgcLogHelper::writeLog(target: 'DocumentType', action: __FUNCTION__, model_json: $documentType->toJson(JSON_UNESCAPED_UNICODE));
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
        SgcLogHelper::writeLog(target: 'DocumentType', action: __FUNCTION__);
    }

    public function fetched(DocumentType $documentType)
    {
        SgcLogHelper::writeLog(target: 'DocumentType', action: __FUNCTION__, model_json: $documentType->toJson(JSON_UNESCAPED_UNICODE));
    }
}
