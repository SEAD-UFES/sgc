<?php

namespace App\Observers;

use App\Helpers\SgcLogHelper;
use App\Models\GrantType;

class GrantTypeObserver
{
    /**
     * Handle the GrantType "created" event.
     *
     * @param  \App\Models\GrantType  $grantType
     * @return void
     */
    public function created(GrantType $grantType)
    {
        SgcLogHelper::writeLog(target: 'GrantType', action: __FUNCTION__, model_json: $grantType->toJson(JSON_UNESCAPED_UNICODE));
    }

    /**
     * Handle the GrantType "updated" event.
     *
     * @param  \App\Models\GrantType  $grantType
     * @return void
     */
    public function updating(GrantType $grantType)
    {
        SgcLogHelper::writeLog(target: 'GrantType', action: __FUNCTION__, model_json: json_encode($grantType->getOriginal(), JSON_UNESCAPED_UNICODE));
    }

    /**
     * Handle the GrantType "updated" event.
     *
     * @param  \App\Models\GrantType  $grantType
     * @return void
     */
    public function updated(GrantType $grantType)
    {
        SgcLogHelper::writeLog(target: 'GrantType', action: __FUNCTION__, model_json: $grantType->toJson(JSON_UNESCAPED_UNICODE));
    }

    /**
     * Handle the GrantType "deleted" event.
     *
     * @param  \App\Models\GrantType  $grantType
     * @return void
     */
    public function deleted(GrantType $grantType)
    {
        SgcLogHelper::writeLog(target: 'GrantType', action: __FUNCTION__, model_json: $grantType->toJson(JSON_UNESCAPED_UNICODE));
    }

    /**
     * Handle the GrantType "restored" event.
     *
     * @param  \App\Models\GrantType  $grantType
     * @return void
     */
    public function restored(GrantType $grantType)
    {
        //
    }

    /**
     * Handle the GrantType "force deleted" event.
     *
     * @param  \App\Models\GrantType  $grantType
     * @return void
     */
    public function forceDeleted(GrantType $grantType)
    {
        //
    }

    public function listed()
    {
        SgcLogHelper::writeLog(target: 'GrantType', action: __FUNCTION__);
    }

    public function fetched(GrantType $grantType)
    {
        SgcLogHelper::writeLog(target: 'GrantType', action: __FUNCTION__, model_json: $grantType->toJson(JSON_UNESCAPED_UNICODE));
    }
}
