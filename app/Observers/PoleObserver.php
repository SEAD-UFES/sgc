<?php

namespace App\Observers;

use App\Helpers\SgcLogHelper;
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
        SgcLogHelper::writeLog(target: 'Pole', action: __FUNCTION__, model_json: $pole->toJson(JSON_UNESCAPED_UNICODE));
    }

    /**
     * Handle the Pole "updated" event.
     *
     * @param  \App\Models\Pole  $pole
     * @return void
     */
    public function updating(Pole $pole)
    {
        SgcLogHelper::writeLog(target: 'Pole', action: __FUNCTION__, model_json: json_encode($pole->getOriginal(), JSON_UNESCAPED_UNICODE));
    }

    /**
     * Handle the Pole "updated" event.
     *
     * @param  \App\Models\Pole  $pole
     * @return void
     */
    public function updated(Pole $pole)
    {
        SgcLogHelper::writeLog(target: 'Pole', action: __FUNCTION__, model_json: $pole->toJson(JSON_UNESCAPED_UNICODE));
    }

    /**
     * Handle the Pole "deleted" event.
     *
     * @param  \App\Models\Pole  $pole
     * @return void
     */
    public function deleted(Pole $pole)
    {
        SgcLogHelper::writeLog(target: 'Pole', action: __FUNCTION__, model_json: $pole->toJson(JSON_UNESCAPED_UNICODE));
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
        SgcLogHelper::writeLog(target: 'Pole', action: __FUNCTION__);
    }

    public function fetched(Pole $pole)
    {
        SgcLogHelper::writeLog(target: 'Pole', action: __FUNCTION__, model_json: $pole->toJson(JSON_UNESCAPED_UNICODE));
    }
}
