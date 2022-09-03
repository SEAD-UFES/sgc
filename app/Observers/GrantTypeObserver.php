<?php

namespace App\Observers;

use App\Helpers\ModelActivityHelper;
use App\Logging\LoggerInterface;
use App\Models\GrantType;
use Spatie\Activitylog\Models\Activity;

class GrantTypeObserver
{
    public function __construct(private LoggerInterface $logger, private ModelActivityHelper $modelActivityHelper)
    {
    }

    /**
     * Handle the GrantType "created" event.
     *
     * @param  GrantType  $grantType
     * @return void
     */
    public function created(GrantType $grantType)
    {
        /** @var Activity $activity */
        $activity = $this->modelActivityHelper->getModelEventActivity('created', $grantType);

        $this->logger->logModelEvent($activity);
    }

    /**
     * Handle the GrantType "updated" event.
     *
     * @param  GrantType  $grantType
     * @return void
     */
    public function updating(GrantType $grantType)
    {
        $this->logger->logModelEvent('updating', $grantType);
    }

    /**
     * Handle the GrantType "updated" event.
     *
     * @param  GrantType  $grantType
     * @return void
     */
    public function updated(GrantType $grantType)
    {
        /** @var Activity $activity */
        $activity = $this->modelActivityHelper->getModelEventActivity('updated', $grantType);

        $this->logger->logModelEvent($activity);
    }

    /**
     * Handle the GrantType "deleted" event.
     *
     * @param  GrantType  $grantType
     * @return void
     */
    public function deleted(GrantType $grantType)
    {
        /** @var Activity $activity */
        $activity = $this->modelActivityHelper->getModelEventActivity('deleted', $grantType);

        $this->logger->logModelEvent($activity);
    }

    /**
     * Handle the GrantType "restored" event.
     *
     * @param  GrantType  $grantType
     * @return void
     */
    public function restored(GrantType $grantType)
    {
    }

    /**
     * Handle the GrantType "force deleted" event.
     *
     * @param  GrantType  $grantType
     * @return void
     */
    public function forceDeleted(GrantType $grantType)
    {
    }
}
