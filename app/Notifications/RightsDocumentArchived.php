<?php

namespace App\Notifications;

use App\Models\Bond;
use App\Models\BondDocument;
use App\Models\Course;
use App\Models\Document;
use App\Models\DocumentType;
use App\Models\Employee;
use App\Models\Role;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class RightsDocumentArchived extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * @var Document
     */
    protected $document;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(protected Bond $bond)
    {
        /**
         * @var DocumentType $documentType
         */
        $documentType = DocumentType::where('name', 'Ficha de Inscrição - Termos e Licença')->first();
        /**
         * @var Document $rightsDocument
         */
        $rightsDocument = Document::where('document_type_id', $documentType->id)->whereHasMorph('documentable', BondDocument::class, function ($query) {
            $query->where('bond_id', $this->bond->id);
        })->first();
        $this->document = $rightsDocument;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     *
     * @return array<int, string>
     */
    public function via($notifiable): array
    {
        return ['database'/* , 'mail' */];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     *
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    /* public function toMail($notifiable)
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    } */

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     *
     * @return array<string, string>
     */
    public function toArray($notifiable): array
    {
        /**
         * @var Course $course
         */
        $course = $this->bond->course;

        /**
         * @var Employee $employee
         */
        $employee = $this->bond->employee;

        /**
         * @var Role $role
         */
        $role = $this->bond->role;

        return [
            'bond_id' => (string) $this->bond->id,
            'course_name' => $course->name,
            'employee_name' => $employee->name,
            'role_name' => $role->name,
            'document_id' => (string) $this->document->id,
            'document_name' => $this->document->original_name,
        ];
    }
}
