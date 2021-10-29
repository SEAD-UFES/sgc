<?php

namespace App\Observers;

use App\CustomClasses\SgcLogger;
use App\Models\State;

class StateObserver
{
    /**
     * Handle the State "created" event.
     *
     * @param  \App\Models\State  $state
     * @return void
     */
    public function created(State $state)
    {
        SgcLogger::writeLog(target: 'State', action: __FUNCTION__, model: $state);
    }

    /**
     * Handle the State "updated" event.
     *
     * @param  \App\Models\State  $state
     * @return void
     */
    public function updating(State $state)
    {
        SgcLogger::writeLog(target: 'State', action: __FUNCTION__, model: $state);
    }

    /**
     * Handle the State "updated" event.
     *
     * @param  \App\Models\State  $state
     * @return void
     */
    public function updated(State $state)
    {
        SgcLogger::writeLog(target: 'State', action: __FUNCTION__, model: $state);
    }

    /**
     * Handle the State "deleted" event.
     *
     * @param  \App\Models\State  $state
     * @return void
     */
    public function deleted(State $state)
    {
        SgcLogger::writeLog(target: 'State', action: __FUNCTION__, model: $state);
    }

    /**
     * Handle the State "restored" event.
     *
     * @param  \App\Models\State  $state
     * @return void
     */
    public function restored(State $state)
    {
        //
    }

    /**
     * Handle the State "force deleted" event.
     *
     * @param  \App\Models\State  $state
     * @return void
     */
    public function forceDeleted(State $state)
    {
        //
    }

    public function listed()
    {
        SgcLogger::writeLog(target: 'State', action: __FUNCTION__);
    }

    public function retrieved(State $approved)
    {
        SgcLogger::writeLog(target: 'State', action: __FUNCTION__, model: $approved);
    }
}
