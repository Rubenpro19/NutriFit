<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AppointmentReminderNotification extends Notification implements ShouldQueue
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
        
        // Determinar si es para paciente o nutricionista
        $isPaciente = $notifiable->role->name === 'paciente';
        $otherPerson = $isPaciente 
            ? 'Dr(a). ' . $this->appointment->nutricionista->name
            : $this->appointment->paciente->name;

        return (new MailMessage)
            ->subject('⏰ Recordatorio: Cita Mañana - NutriFit')
            ->greeting('¡Hola ' . $notifiable->name . '!')
            ->line('Te recordamos que tienes una cita mañana.')
            ->line('**' . ($isPaciente ? 'Nutricionista' : 'Paciente') . ':** ' . $otherPerson)
            ->line('**Fecha:** ' . $appointmentDate)
            ->line('**Hora:** ' . $appointmentTime)
            ->action('Ver Detalles', url($isPaciente ? '/paciente/citas' : '/nutricionista/dashboard'))
            ->line('Por favor, llega puntual a tu cita.')
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
            'date' => $this->appointment->start_time->format('Y-m-d'),
            'time' => $this->appointment->start_time->format('H:i'),
        ];
    }
}

