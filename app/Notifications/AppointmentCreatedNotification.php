<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AppointmentCreatedNotification extends Notification implements ShouldQueue
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
        $patientName = $this->appointment->paciente->name;

        return (new MailMessage)
            ->subject('🗓️ Nueva Cita Agendada - NutriFit')
            ->greeting('¡Hola Dr(a). ' . $notifiable->name . '!')
            ->line('Se ha agendado una nueva cita en tu calendario.')
            ->line('**Paciente:** ' . $patientName)
            ->line('**Fecha:** ' . $appointmentDate)
            ->line('**Hora:** ' . $appointmentTime)
            ->action('Ver Detalles', url('/nutricionista/dashboard'))
            ->line('Recuerda prepararte para la consulta.')
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
            'patient_name' => $this->appointment->paciente->name,
            'date' => $this->appointment->start_time->format('Y-m-d'),
            'time' => $this->appointment->start_time->format('H:i'),
        ];
    }
}

