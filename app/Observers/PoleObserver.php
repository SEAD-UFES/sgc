<?php

namespace App\Observers;

use App\CustomClasses\SgcLogger;
use App\Models\Pole;

class PoleObserver
{
    /**
     * Handle the Pole "created" event.
     *
     * @param  \App\Models\Pole  $pole
     * @return void
     */
    public function created(Pole $pole)
    {
        SgcLogger::writeLog(target: 'Pole', action: __FUNCTION__, model: $pole);
    }

    /**
     * Handle the Pole "updated" event.
     *
     * @param  \App\Models\Pole  $pole
     * @return void
     */
    public function updating(Pole $pole)
    {
        SgcLogger::writeLog(target: 'Pole', action: __FUNCTION__, model: $pole);
    }

    /**
     * Handle the Pole "updated" event.
     *
     * @param  \App\Models\Pole  $pole
     * @return void
     */
    public function updated(Pole $pole)
    {
        SgcLogger::writeLog(target: 'Pole', action: __FUNCTION__, model: $pole);
    }

    /**
     * Handle the Pole "deleted" event.
     *
     * @param  \App\Models\Pole  $pole
     * @return void
     */
    public function deleted(Pole $pole)
    {
        SgcLogger::writeLog(target: 'Pole', action: __FUNCTION__, model: $pole);
    }

    /**
     * Handle the Pole "restored" event.
     *
     * @param  \App\Models\Pole  $pole
     * @return void
     */
    public function restored(Pole $pole)
    {
        //
    }

    /**
     * Handle the Pole "force deleted" event.
     *
     * @param  \App\Models\Pole  $pole
     * @return void
     */
    public function forceDeleted(Pole $pole)
    {
        //
    }

    public function listed()
    {
        SgcLogger::writeLog(target: 'Pole', action: __FUNCTION__);
    }

    public function fetched(Pole $pole)
    {
        SgcLogger::writeLog(target: 'Pole', action: __FUNCTION__, model: $pole);
    }
}
