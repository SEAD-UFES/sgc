<?php

namespace App\Services;

use App\Mail\InstitutionEmployeeLoginCreatedNotice;
use App\Mail\LmsAccessPermissionRequest;
use App\Mail\NewTutorEmploymentNotice;
use App\Models\Bond;
use App\Models\Employee;
use Illuminate\Support\Facades\Mail;

class MailService
{
    private string $educationalDesignTeam = 'dedi.sead@ufes.br';
    private string $tutoringCoordinationEmail = 'tutoria.sead@ufes.br';

    /**
     * @param Employee|null $sender
     * @param Bond $receiverBond
     *
     * @return void
     */
    public function sendInstitutionEmployeeLoginCreatedEmail(?Employee $sender, Bond $receiverBond): void
    {
        /**
         * @var Employee $receiver
         */
        $receiver = $receiverBond->employee;

        $receiverEmailAddress = 'marco.cardoso@ufes.br'; //$receiver->email;
        Mail::to($receiverEmailAddress)->send(new InstitutionEmployeeLoginCreatedNotice($sender, $receiverBond));
    }

    /**
     * @param Employee|null $sender
     * @param Bond $newEmployeeBond
     *
     * @return void
     */
    public function sendLmsAccessPermissionRequestEmail(?Employee $sender, Bond $newEmployeeBond): void
    {
        $receiverEmailAddress = 'marco.cardoso@ufes.br'; //$this->educationalDesignTeam;
        Mail::to($receiverEmailAddress)->send(new LmsAccessPermissionRequest($sender, $newEmployeeBond));
    }

    /**
     * @param Employee|null $sender
     * @param Bond $newEmployeeBond
     *
     * @return void
     */
    public function sendNewTutorEmploymentNoticeEmail(?Employee $sender, Bond $newEmployeeBond): void
    {
        $receiverEmailAddress = 'marco.cardoso@ufes.br'; //$this->tutoringCoordinationEmail;
        Mail::to($receiverEmailAddress)->send(new NewTutorEmploymentNotice($sender, $newEmployeeBond));
    }
}
