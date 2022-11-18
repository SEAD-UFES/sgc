<?php

namespace App\Services;

use App\Mail\InstitutionEmployeeLoginCreatedNotice;
use App\Mail\LmsAccessPermissionRequest;
use App\Mail\NewTutorEmploymentNotice;
use App\Models\Bond;
use App\Models\Employee;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class MailService
{
    private string $educationalDesignTeam = 'dedi.sead@ufes.br';

    private string $tutoringCoordinationEmail = 'tutoria.sead@ufes.br';

    /**
     * @param User|null $sender
     * @param Bond $receiverBond
     *
     * @return void
     */
    public function sendInstitutionEmployeeLoginCreatedEmail(?User $sender, Bond $receiverBond): void
    {
        /**
         * @var Employee $receiver
         */
        $receiver = $receiverBond->employee;

        $receiverEmailAddress = ['marco.cardoso@ufes.br', 'sonia.clarindo@ufes.br']; //$receiver->email;
        Mail::to($receiverEmailAddress)->send(new InstitutionEmployeeLoginCreatedNotice($sender, $receiverBond));
    }

    /**
     * @param User|null $sender
     * @param Bond $newEmployeeBond
     *
     * @return void
     */
    public function sendLmsAccessPermissionRequestEmail(?User $sender, Bond $newEmployeeBond): void
    {
        $receiverEmailAddress = ['marco.cardoso@ufes.br', 'sonia.clarindo@ufes.br']; //$this->educationalDesignTeam;
        Mail::to($receiverEmailAddress)->send(new LmsAccessPermissionRequest($sender, $newEmployeeBond));
    }

    /**
     * @param User|null $sender
     * @param Bond $newEmployeeBond
     *
     * @return void
     */
    public function sendNewTutorEmploymentNoticeEmail(?User $sender, Bond $newEmployeeBond): void
    {
        $receiverEmailAddress = ['marco.cardoso@ufes.br', 'sonia.clarindo@ufes.br']; //$this->tutoringCoordinationEmail;
        Mail::to($receiverEmailAddress)->send(new NewTutorEmploymentNotice($sender, $newEmployeeBond));
    }

    /**
     * @param Bond $bond
     *
     * @return void
     */
    public function sendNewEmployeeEmails(Bond $bond): void
    {
        /**
         * @var User $loggedInUser
         */
        $loggedInUser = auth()->user();

        /**
         * @var Role $employeeRole
         */
        $employeeRole = $bond->role;
        $this->sendInstitutionEmployeeLoginCreatedEmail($loggedInUser, $bond);
        $this->sendLmsAccessPermissionRequestEmail($loggedInUser, $bond);
        if (str_starts_with($employeeRole->name, 'Tutor')) {
            $this->sendNewTutorEmploymentNoticeEmail($loggedInUser, $bond);
        }
    }
}
