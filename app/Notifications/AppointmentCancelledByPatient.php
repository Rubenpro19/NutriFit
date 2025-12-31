<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AppointmentCancelledByPatient extends Notification implements ShouldQueue
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

        if ($this->recipientType === 'paciente') {
            // Notificación para el paciente que canceló
            $nutricionistaName = $this->appointment->nutricionista->name;
            
            return (new MailMessage)
                ->subject('⚠️ Cita Cancelada - NutriFit')
                ->greeting('¡Hola ' . $notifiable->name . '!')
                ->line('Tu cita ha sido cancelada exitosamente.')
                ->line('**Detalles de la cita cancelada:**')
                ->line('📅 Fecha: ' . $appointmentDate)
                ->line('🕐 Hora: ' . $appointmentTime)
                ->line('👨‍⚕️ Nutricionista: ' . $nutricionistaName)
                ->line('Si deseas agendar una nueva cita, puedes hacerlo desde tu dashboard.')
                ->action('Ir a mi Dashboard', url('/paciente/dashboard'))
                ->line('¡Gracias por usar NutriFit!');
        } else {
            // Notificación para el nutricionista
            $pacienteName = $this->appointment->paciente->name;
            
            return (new MailMessage)
                ->subject('⚠️ Cita Cancelada por Paciente - NutriFit')
                ->greeting('¡Hola ' . $notifiable->name . '!')
                ->line('El paciente **' . $pacienteName . '** ha cancelado su cita.')
                ->line('**Detalles de la cita cancelada:**')
                ->line('📅 Fecha: ' . $appointmentDate)
                ->line('🕐 Hora: ' . $appointmentTime)
                ->line('👤 Paciente: ' . $pacienteName)
                ->line('Este horario ahora está disponible para otros pacientes.')
                ->action('Ver mis Citas', url('/nutricionista/citas'))
                ->line('¡Gracias por tu dedicación!');
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
