<?php

namespace App\Observers;

use App\CustomClasses\SgcLogger;
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
        SgcLogger::writeLog(target: 'GrantType', action: __FUNCTION__, model: $grantType);
    }

    /**
     * Handle the GrantType "updated" event.
     *
     * @param  \App\Models\GrantType  $grantType
     * @return void
     */
    public function updating(GrantType $grantType)
    {
        SgcLogger::writeLog(target: 'GrantType', action: __FUNCTION__, model: $grantType);
    }

    /**
     * Handle the GrantType "updated" event.
     *
     * @param  \App\Models\GrantType  $grantType
     * @return void
     */
    public function updated(GrantType $grantType)
    {
        SgcLogger::writeLog(target: 'GrantType', action: __FUNCTION__, model: $grantType);
    }

    /**
     * Handle the GrantType "deleted" event.
     *
     * @param  \App\Models\GrantType  $grantType
     * @return void
     */
    public function deleted(GrantType $grantType)
    {
        SgcLogger::writeLog(target: 'GrantType', action: __FUNCTION__, model: $grantType);
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
        SgcLogger::writeLog(target: 'GrantType', action: __FUNCTION__);
    }

    public function fetched(GrantType $approved)
    {
        SgcLogger::writeLog(target: 'GrantType', action: __FUNCTION__, model: $approved);
    }
}
