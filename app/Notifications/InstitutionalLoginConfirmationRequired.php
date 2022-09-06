<?php

namespace App\Notifications;

use App\Models\Employee;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InstitutionalLoginConfirmationRequired extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(protected Employee $employee)
    {
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
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     *
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage())
            ->subject('Lembrete de Confirmação de Login Institucional [' .  $this->employee->name . ']')
            ->from('secretaria.sead@ufes.br', 'Secretaria SEAD')
            ->replyTo('secretaria.sead@ufes.br', 'Secretaria SEAD')
            ->greeting('Olá!')
            ->line('Um novo colaborador foi cadastrado e a criação de seu login institucional precisa ser confirmada.')
            ->line('Colaborador: ' . $this->employee->name)
            ->success()
            ->action('Cadastro do Colaborador', route('employees.show', $this->employee->id))
            ->line('Para confirmar a criação do login, acesse a página: https://senha.ufes.br/sincronia/troubleshooting.')
            ->line('Em seguida, envie os e-mails de confirmação, clicando no respectivo botão na página do vínculo do colaborador.')
            ->salutation('Atenciosamente, SGC');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     *
     * @return array<string, string>
     */
    public function toArray($notifiable)
    {
        return [
            'message' => 'Um novo colaborador foi cadastrado e a criação de seu login institucional precisa ser confirmada.',
            'employeeId' => (string) $this->employee->id,
            'employeeName' => $this->employee->name,
        ];
    }
}
