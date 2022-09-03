<?php

namespace App\Observers;

use App\Helpers\ModelActivityHelper;
use App\Logging\LoggerInterface;
use App\Models\UserType;
use Spatie\Activitylog\Models\Activity;

class UserTypeObserver
{
    public function __construct(private LoggerInterface $logger, private ModelActivityHelper $modelActivityHelper)
    {
    }

    /**
     * Handle the UserType "created" event.
     *
     * @param  \App\Models\UserType  $userType
     * @return void
     */
    public function created(UserType $userType)
    {
        /** @var Activity $activity */
        $activity = $this->modelActivityHelper->getModelEventActivity('created', $userType);

        $this->logger->logModelEvent($activity);
    }

    /**
     * Handle the UserType "updated" event.
     *
     * @param  \App\Models\UserType  $userType
     * @return void
     */
    public function updating(UserType $userType)
    {
        $this->logger->logModelEvent('updating', $userType);
    }

    /**
     * Handle the UserType "updated" event.
     *
     * @param  \App\Models\UserType  $userType
     * @return void
     */
    public function updated(UserType $userType)
    {
        /** @var Activity $activity */
        $activity = $this->modelActivityHelper->getModelEventActivity('updated', $userType);

        $this->logger->logModelEvent($activity);
    }

    /**
     * Handle the UserType "deleted" event.
     *
     * @param  \App\Models\UserType  $userType
     * @return void
     */
    public function deleted(UserType $userType)
    {
        /** @var Activity $activity */
        $activity = $this->modelActivityHelper->getModelEventActivity('deleted', $userType);

        $this->logger->logModelEvent($activity);
    }

    /**
     * Handle the UserType "restored" event.
     *
     * @param  \App\Models\UserType  $userType
     * @return void
     */
    public function restored(UserType $userType)
    {
    }

    /**
     * Handle the UserType "force deleted" event.
     *
     * @param  \App\Models\UserType  $userType
     * @return void
     */
    public function forceDeleted(UserType $userType)
    {
    }
}
