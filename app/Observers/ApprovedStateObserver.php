<?php

namespace App\Observers;

use App\CustomClasses\SgcLogger;
use App\Models\ApprovedState;

class ApprovedStateObserver
{
    /**
     * Handle the ApprovedState "created" event.
     *
     * @param  \App\Models\ApprovedState  $approvedState
     * @return void
     */
    public function created(ApprovedState $approvedState)
    {
        SgcLogger::writeLog(target: 'ApprovedState', action: __FUNCTION__, model_json: $approvedState->toJson(JSON_UNESCAPED_UNICODE));
    }

    /**
     * Handle the ApprovedState "updated" event.
     *
     * @param  \App\Models\ApprovedState  $approvedState
     * @return void
     */
    public function updating(ApprovedState $approvedState)
    {
        SgcLogger::writeLog(target: 'ApprovedState', action: __FUNCTION__, model_json:  json_encode($approvedState->getOriginal(), JSON_UNESCAPED_UNICODE));
    }

    /**
     * Handle the ApprovedState "updated" event.
     *
     * @param  \App\Models\ApprovedState  $approvedState
     * @return void
     */
    public function updated(ApprovedState $approvedState)
    {
        SgcLogger::writeLog(target: 'ApprovedState', action: __FUNCTION__, model_json: $approvedState->toJson(JSON_UNESCAPED_UNICODE));
    }

    /**
     * Handle the ApprovedState "deleted" event.
     *
     * @param  \App\Models\ApprovedState  $approvedState
     * @return void
     */
    public function deleted(ApprovedState $approvedState)
    {
        SgcLogger::writeLog(target: 'ApprovedState', action: __FUNCTION__, model_json: $approvedState->toJson(JSON_UNESCAPED_UNICODE));
    }

    /**
     * Handle the ApprovedState "restored" event.
     *
     * @param  \App\Models\ApprovedState  $approvedState
     * @return void
     */
    public function restored(ApprovedState $approvedState)
    {
        //
    }

    /**
     * Handle the ApprovedState "force deleted" event.
     *
     * @param  \App\Models\ApprovedState  $approvedState
     * @return void
     */
    public function forceDeleted(ApprovedState $approvedState)
    {
        //
    }

    public function listed()
    {
        SgcLogger::writeLog(target: 'ApprovedState', action: __FUNCTION__);
    }

    public function fetched(ApprovedState $approvedState)
    {
        SgcLogger::writeLog(target: 'ApprovedState', action: __FUNCTION__, model_json: $approvedState->toJson(JSON_UNESCAPED_UNICODE));
    }
}
