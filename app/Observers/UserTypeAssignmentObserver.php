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
     * @param  UserTypeAssignment  $userTypeAssignment
     * @return void
     */
    public function created(UserTypeAssignment $userTypeAssignment)
    {
        /** @var Activity $activity */
        $activity = $this->modelActivityHelper->getModelEventActivity('created', $userTypeAssignment);

        $this->logger->logModelEvent($activity);
    }

    /**
     * Handle the UserTypeAssignment "updated" event.
     *
     * @param  UserTypeAssignment  $userTypeAssignment
     * @return void
     */
    public function updating(UserTypeAssignment $userTypeAssignment)
    {
        $this->logger->logModelEvent('updating', $userTypeAssignment);
    }

    /**
     * Handle the UserTypeAssignment "updated" event.
     *
     * @param  UserTypeAssignment  $userTypeAssignment
     * @return void
     */
    public function updated(UserTypeAssignment $userTypeAssignment)
    {
        /** @var Activity $activity */
        $activity = $this->modelActivityHelper->getModelEventActivity('updated', $userTypeAssignment);

        $this->logger->logModelEvent($activity);
    }

    /**
     * Handle the UserTypeAssignment "deleted" event.
     *
     * @param  UserTypeAssignment  $userTypeAssignment
     * @return void
     */
    public function deleted(UserTypeAssignment $userTypeAssignment)
    {
        /** @var Activity $activity */
        $activity = $this->modelActivityHelper->getModelEventActivity('deleted', $userTypeAssignment);

        $this->logger->logModelEvent($activity);
    }

    /**
     * Handle the UserTypeAssignment "restored" event.
     *
     * @param  UserTypeAssignment  $userTypeAssignment
     * @return void
     */
    public function restored(UserTypeAssignment $userTypeAssignment)
    {
    }

    /**
     * Handle the UserTypeAssignment "force deleted" event.
     *
     * @param  UserTypeAssignment  $userTypeAssignment
     * @return void
     */
    public function forceDeleted(UserTypeAssignment $userTypeAssignment)
    {
    }
}
