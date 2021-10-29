<?php

namespace App\Observers;

use App\CustomClasses\SgcLogger;
use App\Models\Approved;

class ApprovedObserver
{
    /**
     * Handle the Approved "created" event.
     *
     * @param  \App\Models\Approved  $approved
     * @return void
     */
    public function created(Approved $approved)
    {
        SgcLogger::writeLog(target:'Approved', action: __FUNCTION__, model: $approved);
    }

    /**
     * Handle the Approved "updated" event.
     *
     * @param  \App\Models\Approved  $approved
     * @return void
     */
    public function updating(Approved $approved)
    {
        SgcLogger::writeLog(target:'Approved', action: __FUNCTION__, model: $approved);
    }

    /**
     * Handle the Approved "updated" event.
     *
     * @param  \App\Models\Approved  $approved
     * @return void
     */
    public function updated(Approved $approved)
    {
        SgcLogger::writeLog(target:'Approved', action: __FUNCTION__, model: $approved);
    }

    /**
     * Handle the Approved "deleted" event.
     *
     * @param  \App\Models\Approved  $approved
     * @return void
     */
    public function deleted(Approved $approved)
    {
        SgcLogger::writeLog(target:'Approved', action: __FUNCTION__, model: $approved);
    }

    /**
     * Handle the Approved "restored" event.
     *
     * @param  \App\Models\Approved  $approved
     * @return void
     */
    public function restored(Approved $approved)
    {
        //
    }

    /**
     * Handle the Approved "force deleted" event.
     *
     * @param  \App\Models\Approved  $approved
     * @return void
     */
    public function forceDeleted(Approved $approved)
    {
        //
    }

    public function listed()
    {
        SgcLogger::writeLog(target: 'Approved', action: __FUNCTION__);
    }

    public function fetched(Approved $approved)
    {
        SgcLogger::writeLog(target: 'Approved', action: __FUNCTION__, model: $approved);
    }
}
