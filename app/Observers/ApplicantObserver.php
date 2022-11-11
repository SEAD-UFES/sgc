<?php

namespace App\Observers;

use App\Helpers\ModelActivityHelper;
use App\Logging\LoggerInterface;
use App\Models\Applicant;
use Spatie\Activitylog\Models\Activity;

class ApplicantObserver
{
    public function __construct(private LoggerInterface $logger, private ModelActivityHelper $modelActivityHelper)
    {
    }

    /**
     * Handle the Applicant "created" event.
     *
     * @param  Applicant  $Applicant
     * @return void
     */
    public function created(Applicant $Applicant)
    {
        /** @var Activity $activity */
        $activity = $this->modelActivityHelper->getModelEventActivity('created', $Applicant);

        $this->logger->logModelEvent($activity);
    }

    /**
     * Handle the Applicant "updated" event.
     *
     * @param  Applicant  $Applicant
     * @return void
     */
    public function updating(Applicant $Applicant)
    {
        $this->logger->logModelEvent('updating', $Applicant);
    }

    /**
     * Handle the Applicant "updated" event.
     *
     * @param  Applicant  $Applicant
     * @return void
     */
    public function updated(Applicant $Applicant)
    {
        /** @var Activity $activity */
        $activity = $this->modelActivityHelper->getModelEventActivity('updated', $Applicant);

        $this->logger->logModelEvent($activity);
    }

    /**
     * Handle the Applicant "deleted" event.
     *
     * @param  Applicant  $Applicant
     * @return void
     */
    public function deleted(Applicant $Applicant)
    {
        /** @var Activity $activity */
        $activity = $this->modelActivityHelper->getModelEventActivity('deleted', $Applicant);

        $this->logger->logModelEvent($activity);
    }

    /**
     * Handle the Applicant "restored" event.
     *
     * @param  Applicant  $Applicant
     * @return void
     */
    public function restored(Applicant $Applicant)
    {
    }

    /**
     * Handle the Applicant "force deleted" event.
     *
     * @param  Applicant  $Applicant
     * @return void
     */
    public function forceDeleted(Applicant $Applicant)
    {
    }
}
