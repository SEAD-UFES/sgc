<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Bond;

class RequestReviewNotification extends Notification/* implements ShouldQueue */ //Queueing disabled while in development
{
    use Queueable;

    protected $bond;
    protected $userName;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($bond)
    {
        $this->bond = Bond::with(['course', 'employee', 'role', 'bondDocuments'])->find($bond->id);
        $this->requesterName = session('sessionUser')->name;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'/* , 'mail' */];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
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
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'bond_id' => $this->bond->id,
            'course_name' => $this->bond->course->name,
            'employee_name' => $this->bond->employee->name,
            'role_name' => $this->bond->role->name,
            'requester_name' => $this->requesterName,
        ];
    }
}