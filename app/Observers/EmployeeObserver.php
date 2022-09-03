<?php

namespace App\Observers;

use App\Helpers\ModelActivityHelper;
use App\Logging\LoggerInterface;
use App\Models\Employee;
use Spatie\Activitylog\Models\Activity;

class EmployeeObserver
{
    public function __construct(private LoggerInterface $logger, private ModelActivityHelper $modelActivityHelper)
    {
    }

    /**
     * Handle the Employee "created" event.
     *
     * @param  Employee  $employee
     * @return void
     */
    public function created(Employee $employee)
    {
        /** @var Activity $activity */
        $activity = $this->modelActivityHelper->getModelEventActivity('created', $employee);

        $this->logger->logModelEvent($activity);
    }

    /**
     * Handle the Employee "updated" event.
     *
     * @param  Employee  $employee
     * @return void
     */
    public function updating(Employee $employee)
    {
        $this->logger->logModelEvent('updating', $employee);
    }

    /**
     * Handle the Employee "updated" event.
     *
     * @param  Employee  $employee
     * @return void
     */
    public function updated(Employee $employee)
    {
        /** @var Activity $activity */
        $activity = $this->modelActivityHelper->getModelEventActivity('updated', $employee);

        $this->logger->logModelEvent($activity);
    }

    /**
     * Handle the Employee "deleted" event.
     *
     * @param  Employee  $employee
     * @return void
     */
    public function deleted(Employee $employee)
    {
        /** @var Activity $activity */
        $activity = $this->modelActivityHelper->getModelEventActivity('deleted', $employee);

        $this->logger->logModelEvent($activity);
    }

    /**
     * Handle the Employee "restored" event.
     *
     * @param  Employee  $employee
     * @return void
     */
    public function restored(Employee $employee)
    {
    }

    /**
     * Handle the Employee "force deleted" event.
     *
     * @param  Employee  $employee
     * @return void
     */
    public function forceDeleted(Employee $employee)
    {
    }
}
