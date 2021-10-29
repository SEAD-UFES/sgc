<?php

namespace App\Observers;

use App\CustomClasses\SgcLogger;
use App\Models\Bond;

class BondObserver
{
    /**
     * Handle the Bond "created" event.
     *
     * @param  \App\Models\Bond  $bond
     * @return void
     */
    public function created(Bond $bond)
    {
        SgcLogger::writeLog(target: 'Bond', action: __FUNCTION__, model: $bond);
    }

    /**
     * Handle the Bond "updated" event.
     *
     * @param  \App\Models\Bond  $bond
     * @return void
     */
    public function updating(Bond $bond)
    {
        SgcLogger::writeLog(target: 'Bond', action: __FUNCTION__, model: $bond);
    }

    /**
     * Handle the Bond "updated" event.
     *
     * @param  \App\Models\Bond  $bond
     * @return void
     */
    public function updated(Bond $bond)
    {
        SgcLogger::writeLog(target: 'Bond', action: __FUNCTION__, model: $bond);
    }

    /**
     * Handle the Bond "deleted" event.
     *
     * @param  \App\Models\Bond  $bond
     * @return void
     */
    public function deleted(Bond $bond)
    {
        SgcLogger::writeLog(target: 'Bond', action: __FUNCTION__, model: $bond);
    }

    /**
     * Handle the Bond "restored" event.
     *
     * @param  \App\Models\Bond  $bond
     * @return void
     */
    public function restored(Bond $bond)
    {
        //
    }

    /**
     * Handle the Bond "force deleted" event.
     *
     * @param  \App\Models\Bond  $bond
     * @return void
     */
    public function forceDeleted(Bond $bond)
    {
        //
    }

    public function listed()
    {
        SgcLogger::writeLog(target: 'Bond', action: __FUNCTION__);
    }

    public function fetched(Bond $approved)
    {
        SgcLogger::writeLog(target: 'Bond', action: __FUNCTION__, model: $approved);
    }
}
