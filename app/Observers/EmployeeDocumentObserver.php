<?php

namespace App\Observers;

use App\Helpers\ModelActivityHelper;
use App\Logging\LoggerInterface;
use App\Models\EmployeeDocument;
use Spatie\Activitylog\Models\Activity;

class EmployeeDocumentObserver
{
    public function __construct(private LoggerInterface $logger, private ModelActivityHelper $modelActivityHelper)
    {
    }

    /**
     * Handle the EmployeeDocument "created" event.
     *
     * @param  \App\Models\EmployeeDocument  $employeeDocument
     * @return void
     */
    public function created(EmployeeDocument $employeeDocument)
    {
        /** @var Activity $activity */
        $activity = $this->modelActivityHelper->getModelEventActivity('created', $employeeDocument);

        $this->logger->logModelEvent($activity);
    }

    /**
     * Handle the EmployeeDocument "updated" event.
     *
     * @param  \App\Models\EmployeeDocument  $employeeDocument
     * @return void
     */
    public function updating(EmployeeDocument $employeeDocument)
    {
        $this->logger->logModelEvent('updating', $employeeDocument);
    }

    /**
     * Handle the EmployeeDocument "updated" event.
     *
     * @param  \App\Models\EmployeeDocument  $employeeDocument
     * @return void
     */
    public function updated(EmployeeDocument $employeeDocument)
    {
        /** @var Activity $activity */
        $activity = $this->modelActivityHelper->getModelEventActivity('updated', $employeeDocument);

        $this->logger->logModelEvent($activity);
    }

    /**
     * Handle the EmployeeDocument "deleted" event.
     *
     * @param  \App\Models\EmployeeDocument  $employeeDocument
     * @return void
     */
    public function deleted(EmployeeDocument $employeeDocument)
    {
        /** @var Activity $activity */
        $activity = $this->modelActivityHelper->getModelEventActivity('deleted', $employeeDocument);

        $this->logger->logModelEvent($activity);
    }

    /**
     * Handle the EmployeeDocument "restored" event.
     *
     * @param  \App\Models\EmployeeDocument  $employeeDocument
     * @return void
     */
    public function restored(EmployeeDocument $employeeDocument)
    {
    }

    /**
     * Handle the EmployeeDocument "force deleted" event.
     *
     * @param  \App\Models\EmployeeDocument  $employeeDocument
     * @return void
     */
    public function forceDeleted(EmployeeDocument $employeeDocument)
    {
    }
}
