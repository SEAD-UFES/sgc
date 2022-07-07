<?php

namespace App\Observers;

use App\Helpers\SgcLogHelper;
use App\Models\BondDocument;

class BondDocumentObserver
{
    /**
     * Handle the BondDocument "created" event.
     *
     * @param  \App\Models\BondDocument  $bondDocument
     * @return void
     */
    public function created(BondDocument $bondDocument)
    {
        SgcLogHelper::writeLog(target: 'BondDocument', action: __FUNCTION__, model_json: $bondDocument->toJson(JSON_UNESCAPED_UNICODE));
    }

    /**
     * Handle the BondDocument "updated" event.
     *
     * @param  \App\Models\BondDocument  $bondDocument
     * @return void
     */
    public function updating(BondDocument $bondDocument)
    {
        SgcLogHelper::writeLog(target: 'BondDocument', action: __FUNCTION__, model_json:  json_encode($bondDocument->getOriginal(), JSON_UNESCAPED_UNICODE));
    }

    /**
     * Handle the BondDocument "updated" event.
     *
     * @param  BondDocument  $bondDocument
     * @return void
     */
    public function updated(BondDocument $bondDocument)
    {
        SgcLogHelper::writeLog(target: 'BondDocument', action: __FUNCTION__, model_json: $bondDocument->toJson(JSON_UNESCAPED_UNICODE));
    }

    /**
     * Handle the BondDocument "deleted" event.
     *
     * @param  \App\Models\BondDocument  $bondDocument
     * @return void
     */
    public function deleted(BondDocument $bondDocument)
    {
        SgcLogHelper::writeLog(target: 'BondDocument', action: __FUNCTION__, model_json: $bondDocument->toJson(JSON_UNESCAPED_UNICODE));
    }

    /**
     * Handle the BondDocument "restored" event.
     *
     * @param  \App\Models\BondDocument  $bondDocument
     * @return void
     */
    public function restored(BondDocument $bondDocument)
    {
        //
    }

    /**
     * Handle the BondDocument "force deleted" event.
     *
     * @param  \App\Models\BondDocument  $bondDocument
     * @return void
     */
    public function forceDeleted(BondDocument $bondDocument)
    {
        //
    }

    public function listed()
    {
        SgcLogHelper::writeLog(target: 'BondDocument', action: __FUNCTION__);
    }

    public function fetched(BondDocument $bondDocument)
    {
        SgcLogHelper::writeLog(target: 'BondDocument', action: __FUNCTION__, model_json: $bondDocument->toJson(JSON_UNESCAPED_UNICODE));
    }
}
