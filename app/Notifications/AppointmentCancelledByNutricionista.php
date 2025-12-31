<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AppointmentCancelledByNutricionista extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public Appointment $appointment,
        public string $recipientType // 'paciente' o 'nutricionista'
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

        if ($this->recipientType === 'nutricionista') {
            // Notificación para el nutricionista que canceló
            $pacienteName = $this->appointment->paciente->name;
            
            return (new MailMessage)
                ->subject('⚠️ Cita Cancelada - NutriFit')
                ->greeting('¡Hola ' . $notifiable->name . '!')
                ->line('Has cancelado exitosamente la cita con el paciente.')
                ->line('**Detalles de la cita cancelada:**')
                ->line('📅 Fecha: ' . $appointmentDate)
                ->line('🕐 Hora: ' . $appointmentTime)
                ->line('👤 Paciente: ' . $pacienteName)
                ->line('El paciente ha sido notificado sobre la cancelación.')
                ->action('Ver mis Citas', url('/nutricionista/citas'))
                ->line('¡Gracias por tu dedicación!');
        } else {
            // Notificación para el paciente
            $nutricionistaName = $this->appointment->nutricionista->name;
            
            return (new MailMessage)
                ->subject('⚠️ Cita Cancelada por Nutricionista - NutriFit')
                ->greeting('¡Hola ' . $notifiable->name . '!')
                ->line('Tu nutricionista **' . $nutricionistaName . '** ha cancelado tu cita.')
                ->line('**Detalles de la cita cancelada:**')
                ->line('📅 Fecha: ' . $appointmentDate)
                ->line('🕐 Hora: ' . $appointmentTime)
                ->line('👨‍⚕️ Nutricionista: ' . $nutricionistaName)
                ->line('Lamentamos las molestias. Puedes agendar una nueva cita cuando desees.')
                ->action('Agendar Nueva Cita', url('/paciente/dashboard'))
                ->line('¡Gracias por tu comprensión!');
        }
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
