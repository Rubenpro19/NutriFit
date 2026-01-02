<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Carbon\Carbon;

class AppointmentRescheduledNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $appointment;
    public $oldStartTime;
    public $rescheduleReason;

    /**
     * Create a new notification instance.
     */
    public function __construct(Appointment $appointment, $oldStartTime, ?string $rescheduleReason = null)
    {
        $this->appointment = $appointment;
        $this->oldStartTime = $oldStartTime;
        $this->rescheduleReason = $rescheduleReason;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $oldDate = Carbon::parse($this->oldStartTime);
        $newDate = Carbon::parse($this->appointment->start_time);
        
        $mailMessage = (new MailMessage)
            ->subject('🔄 Tu cita ha sido reagendada - NutriFit')
            ->greeting('¡Hola ' . $notifiable->name . '!')
            ->line('Tu cita con **' . $this->appointment->nutricionista->name . '** ha sido reagendada.')
            ->line('### Fecha y Hora Original:')
            ->line('📅 **' . $oldDate->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY') . '**')
            ->line('🕐 **' . $oldDate->format('h:i A') . '**')
            ->line('### Nueva Fecha y Hora:')
            ->line('📅 **' . $newDate->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY') . '**')
            ->line('🕐 **' . $newDate->format('h:i A') . '**')
            ->line('**Tipo de consulta:** ' . $this->getAppointmentTypeLabel())
            ->line('**Duración:** 45 minutos');

        if ($this->rescheduleReason) {
            $mailMessage->line('**Motivo del cambio:** ' . $this->rescheduleReason);
        }

        $mailMessage->action('Ver Cita', url('/paciente/dashboard'))
            ->line('Si tienes alguna pregunta o necesitas reprogramar, por favor contáctanos.')
            ->line('¡Gracias por confiar en NutriFit!');

        return $mailMessage;
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'appointment_rescheduled',
            'appointment_id' => $this->appointment->id,
            'nutricionista_name' => $this->appointment->nutricionista->name,
            'old_start_time' => Carbon::parse($this->oldStartTime)->toIso8601String(),
            'new_start_time' => $this->appointment->start_time->toIso8601String(),
            'new_date_formatted' => Carbon::parse($this->appointment->start_time)->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY'),
            'new_time_formatted' => Carbon::parse($this->appointment->start_time)->format('h:i A'),
            'appointment_type' => $this->appointment->appointment_type,
            'reschedule_reason' => $this->rescheduleReason,
        ];
    }

    /**
     * Get appointment type label
     */
    private function getAppointmentTypeLabel(): string
    {
        return match($this->appointment->appointment_type) {
            'primera_vez' => 'Primera vez',
            'seguimiento' => 'Seguimiento',
            'control' => 'Control',
            default => 'No especificado'
        };
    }
}
