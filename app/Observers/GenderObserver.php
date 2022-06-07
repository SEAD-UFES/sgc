<?php

namespace App\Observers;

use App\CustomClasses\SgcLogger;
use App\Models\Gender;

class GenderObserver
{
    /**
     * Handle the Gender "created" event.
     *
     * @param  \App\Models\Gender  $gender
     * @return void
     */
    public function created(Gender $gender)
    {
        SgcLogger::writeLog(target: 'Gender', action: __FUNCTION__, model_json: $gender->toJson(JSON_UNESCAPED_UNICODE));
    }

    /**
     * Handle the Gender "updated" event.
     *
     * @param  \App\Models\Gender  $gender
     * @return void
     */
    public function updating(Gender $gender)
    {
        SgcLogger::writeLog(target: 'Gender', action: __FUNCTION__, model_json: json_encode($gender->getOriginal(), JSON_UNESCAPED_UNICODE));
    }
    
    /**
     * Handle the Gender "updated" event.
     *
     * @param  \App\Models\Gender  $gender
     * @return void
     */
    public function updated(Gender $gender)
    {
        SgcLogger::writeLog(target: 'Gender', action: __FUNCTION__, model_json: $gender->toJson(JSON_UNESCAPED_UNICODE));
    }

    /**
     * Handle the Gender "deleted" event.
     *
     * @param  \App\Models\Gender  $gender
     * @return void
     */
    public function deleted(Gender $gender)
    {
        SgcLogger::writeLog(target: 'Gender', action: __FUNCTION__, model_json: $gender->toJson(JSON_UNESCAPED_UNICODE));
    }

    /**
     * Handle the Gender "restored" event.
     *
     * @param  \App\Models\Gender  $gender
     * @return void
     */
    public function restored(Gender $gender)
    {
        //
    }

    /**
     * Handle the Gender "force deleted" event.
     *
     * @param  \App\Models\Gender  $gender
     * @return void
     */
    public function forceDeleted(Gender $gender)
    {
        //
    }

    public function listed()
    {
        SgcLogger::writeLog(target: 'Gender', action: __FUNCTION__);
    }

    public function fetched(Gender $gender)
    {
        SgcLogger::writeLog(target: 'Gender', action: __FUNCTION__, model_json: $gender->toJson(JSON_UNESCAPED_UNICODE));
    }
}
