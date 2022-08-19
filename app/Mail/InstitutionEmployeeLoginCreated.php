<?php

namespace App\Mail;

use App\Models\Bond;
use App\Models\Employee;
use App\Models\Gender;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InstitutionEmployeeLoginCreated extends Mailable
{
    use Queueable, SerializesModels;

    private ?Employee $sender;
    private Bond $receiverBond;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(?Employee $sender, Bond $receiverBond)
    {
        $this->sender = $sender;
        $this->receiverBond = $receiverBond;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        /**
         * @var string $senderName
         */
        $senderName = $this->sender?->name;

        /**
         * @var Employee $receiver
         */
        $receiver = $this->receiverBond->employee;

        /**
         * @var Gender $receiverGender
         */
        $receiverGender = $receiver->gender;

        /**
         * @var string $receiverName
         */
        $receiverName = $receiver->name;

        /**
         * @var string $receiverEmail
         */
        $receiverEmail = $receiver->email;

        /**
         * @var string $receiverInstitutionLogin
         */
        //$receiverInstitutionLogin = $receiver->institution_login;
        $receiverInstitutionLogin = 'LOGIN UNICO';

        /**
         * @var string $receiverRoleName
         */
        $receiverRoleName = $this->receiverBond->role?->name;

        /**
         * @var string $receiverRoleEmailDomain
         */
        //$receiverRoleEmailDomain = $this->receiverBond->role?->emailDomain;
        $receiverRoleEmailDomain = '@unico.com.br';

        /**
         * @var string $lmsUrl
         */
        //$lmsUrl = $this->receiverBond->course->lms_url;
        $lmsUrl = 'https://lms.com.br';

        return $this->view('emails.employeeRegistration.institution-employee-login-created')
            ->with([
                'senderName' => $senderName,
                'receiverGender' => $receiverGender,
                'receiverName' => $receiverName,
                'receiverEmail' => $receiverEmail,
                'receiverInstitutionLogin' => $receiverInstitutionLogin,
                'receiverRoleName' => $receiverRoleName,
                'receiverRoleEmailDomain' => $receiverRoleEmailDomain,
                'lmsUrl' => $lmsUrl,
            ]);
    }
}
