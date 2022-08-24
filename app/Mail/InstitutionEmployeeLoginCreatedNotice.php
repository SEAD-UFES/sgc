<?php

namespace App\Mail;

use App\Models\Bond;
use App\Models\Course;
use App\Models\Employee;
use App\Models\Gender;
use App\Models\InstitutionalDetail;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InstitutionEmployeeLoginCreatedNotice extends Mailable
{
    use Queueable;
    use SerializesModels;

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
         * @var InstitutionalDetail $institutionalDetail
         */
        $institutionalDetail = $receiver->institutionalDetail;

        /**
         * @var string $receiverInstitutionLogin
         */
        $receiverInstitutionLogin = $institutionalDetail->login;

        /**
         * @var string $receiverRoleName
         */
        $receiverRoleName = $this->receiverBond->role?->name;

        /**
         * @var string $receiverInstitutionEmail
         */
        $receiverInstitutionEmail = $institutionalDetail->email;

        /**
         * @var Course $receiverCourse
         */
        $receiverCourse = $this->receiverBond->course;

        /**
         * @var string $lmsUrl
         */
        $lmsUrl = $receiverCourse->lms_url;

        return $this->subject('Informação Sobre Criação de Login de Acesso')->from('secretaria.sead@ufes.br', 'Sead - Secretaria Acadêmica')->replyTo('secretaria.sead@ufes.br')->markdown('emails.employeeRegistration.institution-employee-login-created-notice')
            ->with([
                'senderName' => $senderName,
                'receiverGender' => $receiverGender,
                'receiverName' => $receiverName,
                'receiverInstitutionLogin' => $receiverInstitutionLogin,
                'receiverRoleName' => $receiverRoleName,
                'receiverInstitutionEmail' => $receiverInstitutionEmail,
                'lmsUrl' => $lmsUrl,
            ]);
    }
}
