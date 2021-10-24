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
        SgcLogger::writeLog(target:'Approved', action:'created', model: $approved);
    }

    /**
     * Handle the Approved "updated" event.
     *
     * @param  \App\Models\Approved  $approved
     * @return void
     */
    public function updated(Approved $approved)
    {
        SgcLogger::writeLog(target:'Approved', action:'updated', model: $approved);
    }

    /**
     * Handle the Approved "deleted" event.
     *
     * @param  \App\Models\Approved  $approved
     * @return void
     */
    public function deleted(Approved $approved)
    {
        SgcLogger::writeLog(target:'Approved', action:'deleted', model: $approved);
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
}
