<?php

namespace App\Mail;

use App\Models\Bond;
use App\Models\Course;
use App\Models\Employee;
use App\Models\Gender;
use App\Models\InstitutionalDetail;
use App\Models\Pole;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LmsAccessPermissionRequest extends Mailable
{
    use Queueable;
    use SerializesModels;
    private ?Employee $sender;

    private Bond $employeeBond;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(?Employee $sender, Bond $employeeBond)
    {
        $this->sender = $sender;
        $this->employeeBond = $employeeBond;
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
         * @var Employee $employee
         */
        $employee = $this->employeeBond->employee;

        /**
         * @var Gender $employeeGender
         */
        $employeeGender = $employee->gender;

        /**
         * @var string $employeeName
         */
        $employeeName = $employee->name;

        /**
         * @var Pole $pole
         */
        $pole = $this->employeeBond->pole;

        /**
         * @var string $poleName
         */
        $poleName = $pole->name;

        /**
         * @var InstitutionalDetail $institutionalDetail
         */
        $institutionalDetail = $employee->institutionalDetail;

        /**
         * @var string $employeeInstitutionLogin
         */
        $employeeInstitutionLogin = $institutionalDetail->login;

        /**
         * @var string $employeeRoleName
         */
        $employeeRoleName = $this->employeeBond->role?->name;

        /**
         * @var string $employeePersonalEmail
         */
        $employeePersonalEmail = $employee->email;

        /**
         * @var string $employeeInstitutionEmail
         */
        $employeeInstitutionEmail = $institutionalDetail->email;

        /**
         * @var Course $course
         */
        $course = $this->employeeBond->course;

        /**
         * @var string $courseName
         */
        $courseName = $course->name;

        /**
         * @var string $employeePhone
         */
        $employeePhone = $employee->phone;

        /**
         * @var string $employeeMobile
         */
        $employeeMobile = $employee->mobile;

        return $this->subject('Solicitação de Liberação de Acesso à Plataforma Moodle')->from('secretaria.sead@ufes.br', 'Sead - Secretaria Acadêmica')->replyTo('secretaria.sead@ufes.br')->markdown('emails.employeeRegistration.lms-access-permission-request')
            ->with([
                'senderName' => $senderName,
                'courseName' => $courseName,
                'employeeRoleName' => $employeeRoleName,
                'employeeGender' => $employeeGender,
                'employeeName' => $employeeName,
                'poleName' => $poleName,
                'employeeInstitutionLogin' => $employeeInstitutionLogin,
                'employeePersonalEmail' => $employeePersonalEmail,
                'employeeInstitutionEmail' => $employeeInstitutionEmail,
                'employeePhone' => $employeePhone,
                'employeeMobile' => $employeeMobile,
            ]);
    }
}
