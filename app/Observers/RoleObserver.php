<?php

namespace App\Observers;

use App\Helpers\ModelActivityHelper;
use App\Logging\LoggerInterface;
use App\Models\Role;
use Spatie\Activitylog\Models\Activity;

class RoleObserver
{
    public function __construct(private LoggerInterface $logger, private ModelActivityHelper $modelActivityHelper)
    {
    }

    /**
     * Handle the Role "created" event.
     *
     * @param  Role  $role
     * @return void
     */
    public function created(Role $role)
    {
        /** @var Activity $activity */
        $activity = $this->modelActivityHelper->getModelEventActivity('created', $role);

        $this->logger->logModelEvent($activity);
    }

    /**
     * Handle the Role "updated" event.
     *
     * @param  Role  $role
     * @return void
     */
    public function updating(Role $role)
    {
        $this->logger->logModelEvent('updating', $role);
    }

    /**
     * Handle the Role "updated" event.
     *
     * @param  Role  $role
     * @return void
     */
    public function updated(Role $role)
    {
        /** @var Activity $activity */
        $activity = $this->modelActivityHelper->getModelEventActivity('updated', $role);

        $this->logger->logModelEvent($activity);
    }

    /**
     * Handle the Role "deleted" event.
     *
     * @param  Role  $role
     * @return void
     */
    public function deleted(Role $role)
    {
        /** @var Activity $activity */
        $activity = $this->modelActivityHelper->getModelEventActivity('deleted', $role);

        $this->logger->logModelEvent($activity);
    }

    /**
     * Handle the Role "restored" event.
     *
     * @param  Role  $role
     * @return void
     */
    public function restored(Role $role)
    {
    }

    /**
     * Handle the Role "force deleted" event.
     *
     * @param  Role  $role
     * @return void
     */
    public function forceDeleted(Role $role)
    {
    }
}
