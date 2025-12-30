<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AppointmentConfirmedForPatient extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public Appointment $appointment
    ) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $appointmentDate = $this->appointment->start_time->format('d/m/Y');
        $appointmentTime = $this->appointment->start_time->format('H:i');
        $nutricionistaName = $this->appointment->nutricionista->name;

        return (new MailMessage)
            ->subject('✅ Cita Agendada Exitosamente - NutriFit')
            ->greeting('¡Hola ' . $notifiable->name . '!')
            ->line('Tu cita ha sido agendada exitosamente.')
            ->line('**Nutricionista:** Dr(a). ' . $nutricionistaName)
            ->line('**Fecha:** ' . $appointmentDate)
            ->line('**Hora:** ' . $appointmentTime)
            ->line('**Estado:** Pendiente de confirmación')
            ->action('Ver Mis Citas', url('/paciente/citas'))
            ->line('El nutricionista confirmará tu cita pronto. Te enviaremos una notificación.')
            ->salutation('Saludos, ' . config('app.name'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'appointment_id' => $this->appointment->id,
            'nutricionista_name' => $this->appointment->nutricionista->name,
            'date' => $this->appointment->start_time->format('Y-m-d'),
            'time' => $this->appointment->start_time->format('H:i'),
        ];
    }
}

