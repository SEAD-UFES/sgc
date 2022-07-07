<?php

namespace App\Observers;

use App\Helpers\SgcLogHelper;
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
        SgcLogHelper::writeLog(target: 'State', action: __FUNCTION__, model_json: $state->toJson(JSON_UNESCAPED_UNICODE));
    }

    /**
     * Handle the State "updated" event.
     *
     * @param  \App\Models\State  $state
     * @return void
     */
    public function updating(State $state)
    {
        SgcLogHelper::writeLog(target: 'State', action: __FUNCTION__, model_json: json_encode($state->getOriginal(), JSON_UNESCAPED_UNICODE));
    }

    /**
     * Handle the State "updated" event.
     *
     * @param  \App\Models\State  $state
     * @return void
     */
    public function updated(State $state)
    {
        SgcLogHelper::writeLog(target: 'State', action: __FUNCTION__, model_json: $state->toJson(JSON_UNESCAPED_UNICODE));
    }

    /**
     * Handle the State "deleted" event.
     *
     * @param  \App\Models\State  $state
     * @return void
     */
    public function deleted(State $state)
    {
        SgcLogHelper::writeLog(target: 'State', action: __FUNCTION__, model_json: $state->toJson(JSON_UNESCAPED_UNICODE));
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
        SgcLogHelper::writeLog(target: 'State', action: __FUNCTION__);
    }

    public function fetched(State $state)
    {
        SgcLogHelper::writeLog(target: 'State', action: __FUNCTION__, model_json: $state->toJson(JSON_UNESCAPED_UNICODE));
    }
}
