<?php

namespace App\Observers;

use App\Helpers\SgcLogHelper;
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
        SgcLogHelper::writeLog(target:'Approved', action: __FUNCTION__, model_json: $approved->toJson(JSON_UNESCAPED_UNICODE));
    }

    /**
     * Handle the Approved "updated" event.
     *
     * @param  \App\Models\Approved  $approved
     * @return void
     */
    public function updating(Approved $approved)
    {
        SgcLogHelper::writeLog(target:'Approved', action: __FUNCTION__, model_json:  json_encode($approved->getOriginal(), JSON_UNESCAPED_UNICODE));
    }

    /**
     * Handle the Approved "updated" event.
     *
     * @param  \App\Models\Approved  $approved
     * @return void
     */
    public function updated(Approved $approved)
    {
        SgcLogHelper::writeLog(target:'Approved', action: __FUNCTION__, model_json: $approved->toJson(JSON_UNESCAPED_UNICODE));
    }

    /**
     * Handle the Approved "deleted" event.
     *
     * @param  \App\Models\Approved  $approved
     * @return void
     */
    public function deleted(Approved $approved)
    {
        SgcLogHelper::writeLog(target:'Approved', action: __FUNCTION__, model_json: $approved->toJson(JSON_UNESCAPED_UNICODE));
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
        SgcLogHelper::writeLog(target: 'Approved', action: __FUNCTION__);
    }

    public function fetched(Approved $approved)
    {
        SgcLogHelper::writeLog(target: 'Approved', action: __FUNCTION__, model_json: $approved->toJson(JSON_UNESCAPED_UNICODE));
    }
}
