<?php

namespace App\Observers;

use App\Helpers\ModelActivityHelper;
use App\Logging\LoggerInterface;
use App\Models\UserTypeAssignment;
use Spatie\Activitylog\Models\Activity;

class UserTypeAssignmentObserver
{
    public function __construct(private LoggerInterface $logger, private ModelActivityHelper $modelActivityHelper)
    {
    }

    /**
     * Handle the UserTypeAssignment "created" event.
     *
     * @param  UserTypeAssignment  $responsibility
     * @return void
     */
    public function created(UserTypeAssignment $responsibility)
    {
        /** @var Activity $activity */
        $activity = $this->modelActivityHelper->getModelEventActivity('created', $responsibility);

        $this->logger->logModelEvent($activity);
    }

    /**
     * Handle the UserTypeAssignment "updated" event.
     *
     * @param  UserTypeAssignment  $responsibility
     * @return void
     */
    public function updating(UserTypeAssignment $responsibility)
    {
        $this->logger->logModelEvent('updating', $responsibility);
    }

    /**
     * Handle the UserTypeAssignment "updated" event.
     *
     * @param  UserTypeAssignment  $responsibility
     * @return void
     */
    public function updated(UserTypeAssignment $responsibility)
    {
        /** @var Activity $activity */
        $activity = $this->modelActivityHelper->getModelEventActivity('updated', $responsibility);

        $this->logger->logModelEvent($activity);
    }

    /**
     * Handle the UserTypeAssignment "deleted" event.
     *
     * @param  UserTypeAssignment  $responsibility
     * @return void
     */
    public function deleted(UserTypeAssignment $responsibility)
    {
        /** @var Activity $activity */
        $activity = $this->modelActivityHelper->getModelEventActivity('deleted', $responsibility);

        $this->logger->logModelEvent($activity);
    }

    /**
     * Handle the UserTypeAssignment "restored" event.
     *
     * @param  UserTypeAssignment  $responsibility
     * @return void
     */
    public function restored(UserTypeAssignment $responsibility)
    {
    }

    /**
     * Handle the UserTypeAssignment "force deleted" event.
     *
     * @param  UserTypeAssignment  $responsibility
     * @return void
     */
    public function forceDeleted(UserTypeAssignment $responsibility)
    {
    }
}
