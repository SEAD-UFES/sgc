<?php

namespace App\Listeners;

use App\Events\BondCreated;
use App\Events\BondImpeded;
use App\Events\BondLiberated;
use App\Events\BondReviewRequested;
use App\Events\EmployeeDesignated;
use App\Events\InstitutionalLoginConfirmationRequired as InstitutionalLoginConfirmationRequiredEvent;
use App\Events\InstitutionalLoginConfirmed;
use App\Events\RightsDocumentArchived;
use App\Logging\LoggerInterface;
use App\Models\Employee;
use App\Models\User;
use App\Models\UserType;
use App\Notifications\BondCreated as BondCreatedNotification;
use App\Notifications\BondImpeded as BondImpededNotification;
use App\Notifications\BondReviewRequested as BondReviewRequestedNotification;
use App\Notifications\InstitutionalLoginConfirmationRequired as InstitutionalLoginConfirmationRequiredNotification;
use App\Notifications\RightsDocumentArchived as RightsDocumentArchivedNotification;
use App\Services\MailService;
use Illuminate\Support\Facades\Notification;

class DomainEventSubscriber
{
    public function __construct(private LoggerInterface $logger, private MailService $mailService)
    {
    }

    /**
     * Handle employee designated events.
     *
     * @param  EmployeeDesignated  $event
     *
     * @return void
     */
    public function handleEmployeeDesignated(EmployeeDesignated $event)
    {
        $this->logger->logDomainEvent(
            'employee_designated',
            $event->employee
        );
    }

    /**
     * Handle bond liberated events.
     *
     * @param  BondLiberated  $event
     *
     * @return void
     */
    public function handleBondLiberated(BondLiberated $event)
    {
        $this->logger->logDomainEvent(
            'bond_liberated',
            $event->bond
        );
    }

    /**
     * Handle bond review requested events.
     *
     * @param  BondReviewRequested  $event
     *
     * @return void
     */
    public function handleBondReviewRequested(BondReviewRequested $event)
    {
        $sec_UT = UserType::firstWhere('acronym', 'sec');
        $sec_users = User::where('active', true)->whereActiveUserType($sec_UT?->id)->get();

        $coord_UT = UserType::firstWhere('acronym', 'coord');
        $course_id = $event->bond->course?->id;
        $coord_users = User::where('active', true)->whereActiveUserType($coord_UT?->id)->whereUtaCourseId($course_id)->get();

        $ass_UT = UserType::firstWhere('acronym', 'ass');
        $ass_users = User::where('active', true)->whereActiveUserType($ass_UT?->id)->get();

        $users = $sec_users->merge($coord_users)->merge($ass_users);

        Notification::send($users, new BondReviewRequestedNotification($event->bond));
        $this->logger->logDomainEvent(
            'bond_review_requested',
            $event->bond
        );
    }

    /**
     * Handle bond impeded events.
     *
     * @param  BondImpeded  $event
     *
     * @return void
     */
    public function handleBondImpeded(BondImpeded $event)
    {
        $sec_UT = UserType::firstWhere('acronym', 'sec');
        $sec_users = User::where('active', true)->whereActiveUserType($sec_UT?->id)->get();

        $coord_UT = UserType::firstWhere('acronym', 'coord');
        $course_id = $event->bond->course?->id;
        $coord_users = User::where('active', true)->whereActiveUserType($coord_UT?->id)->whereUtaCourseId($course_id)->get();

        $users = $sec_users->merge($coord_users);

        Notification::send($users, new BondImpededNotification($event->bond));
        $this->logger->logDomainEvent(
            'bond_impeded',
            $event->bond
        );
    }

    /**
     * Handle bond created events.
     *
     * @param  BondCreated  $event
     *
     * @return void
     */
    public function handleBondCreated(BondCreated $event)
    {
        //Notify grantor assistants
        $ass_UT = UserType::firstWhere('acronym', 'ass');
        $coordOrAssistants = User::where('active', true)->whereActiveUserType($ass_UT?->id)->get();

        Notification::send($coordOrAssistants, new BondCreatedNotification($event->bond));

        $this->logger->logDomainEvent(
            'bond_created',
            $event->bond
        );
    }

    /**
     * Handle rights document archived events.
     *
     * @param  RightsDocumentArchived  $event
     *
     * @return void
     */
    public function handleRightsDocumentArchived(RightsDocumentArchived $event)
    {
        $ldi_UT = UserType::firstWhere('acronym', 'ldi');
        $ldi_users = User::where('active', true)->whereActiveUserType($ldi_UT?->id)->get();

        Notification::send($ldi_users, new RightsDocumentArchivedNotification($event->bond));

        $this->logger->logDomainEvent(
            'rights_document_archived',
            $event->bond
        );
    }

    /**
     * Handle institutional login confirmed events.
     *
     * @param  InstitutionalLoginConfirmed  $event
     *
     * @return void
     */
    public function handleInstitutionalLoginConfirmed(InstitutionalLoginConfirmed $event)
    {
        $this->logger->logDomainEvent(
            'institutional_login_confirmed',
            $event->bond
        );

        $this->mailService->sendNewEmployeeEmails($event->bond);
    }

    /**
     * Handle institutional login confirmation required events.
     *
     * @param  InstitutionalLoginConfirmationRequiredEvent  $event
     *
     * @return void
     */
    public function handleInstitutionalLoginConfirmationRequired(InstitutionalLoginConfirmationRequiredEvent $event): void
    {
        /**
         * @var User $user
         */
        $user = $event->user;
        /**
         * @var Employee $employee
         */
        $employee = $event->employee;

        $this->logger->logDomainEvent(
            'institutional_login_confirmation_required',
            $employee
        );

        Notification::send($user, new InstitutionalLoginConfirmationRequiredNotification($employee));
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param  \Illuminate\Events\Dispatcher  $events
     *
     * @return array<string, string>
     */
    public function subscribe($events): array
    {
        return [
            EmployeeDesignated::class => 'handleEmployeeDesignated',
            BondCreated::class => 'handleBondCreated',
            BondLiberated::class => 'handleBondLiberated',
            BondImpeded::class => 'handleBondImpeded',
            BondReviewRequested::class => 'handleBondReviewRequested',
            RightsDocumentArchived::class => 'handleRightsDocumentArchived',
            InstitutionalLoginConfirmed::class => 'handleInstitutionalLoginConfirmed',
            InstitutionalLoginConfirmationRequiredEvent::class => 'handleInstitutionalLoginConfirmationRequired',
        ];
    }
}
