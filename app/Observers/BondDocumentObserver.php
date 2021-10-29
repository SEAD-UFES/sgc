<?php

namespace App\Observers;

use App\CustomClasses\SgcLogger;
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
        SgcLogger::writeLog(target: 'BondDocument', action: __FUNCTION__, model: $bondDocument);
    }

    /**
     * Handle the BondDocument "updated" event.
     *
     * @param  \App\Models\BondDocument  $bondDocument
     * @return void
     */
    public function updating(BondDocument $bondDocument)
    {
        SgcLogger::writeLog(target: 'BondDocument', action: __FUNCTION__, model: $bondDocument);
    }

    /**
     * Handle the BondDocument "updated" event.
     *
     * @param  BondDocument  $bondDocument
     * @return void
     */
    public function updated(BondDocument $bondDocument)
    {
        SgcLogger::writeLog(target: 'BondDocument', action: __FUNCTION__, model: $bondDocument);
    }

    /**
     * Handle the BondDocument "deleted" event.
     *
     * @param  \App\Models\BondDocument  $bondDocument
     * @return void
     */
    public function deleted(BondDocument $bondDocument)
    {
        SgcLogger::writeLog(target: 'BondDocument', action: __FUNCTION__, model: $bondDocument);
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
        SgcLogger::writeLog(target: 'BondDocument', action: __FUNCTION__);
    }

    public function fetched(BondDocument $approvedState)
    {
        SgcLogger::writeLog(target: 'BondDocument', action: __FUNCTION__, model: $approvedState);
    }
}
