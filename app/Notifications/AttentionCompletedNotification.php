<?php

namespace App\Notifications;

use App\Models\Attention;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AttentionCompletedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public Attention $attention
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
        $appointmentDate = $this->attention->appointment->start_time->format('d/m/Y');
        $appointmentTime = $this->attention->appointment->start_time->format('H:i');
        $nutricionistaName = $this->attention->nutricionista->name;

        // Obtener algunos datos clave de la atención
        $weight = $this->attention->attentionData->weight ?? 'N/A';
        $bmi = $this->attention->attentionData->bmi ?? 'N/A';

        return (new MailMessage)
            ->subject('✅ Atención Nutricional Completada - NutriFit')
            ->greeting('¡Hola ' . $notifiable->name . '!')
            ->line('Tu atención nutricional ha sido completada exitosamente.')
            ->line('**Nutricionista:** Dr(a). ' . $nutricionistaName)
            ->line('**Fecha de atención:** ' . $appointmentDate . ' a las ' . $appointmentTime)
            ->line('**Peso registrado:** ' . $weight . ' kg')
            ->line('**IMC:** ' . number_format($bmi, 2))
            ->line('Se ha generado un plan nutricional personalizado con diagnóstico y recomendaciones específicas para ti.')
            ->action('Ver Reporte Completo', url('/paciente/citas/' . $this->attention->appointment_id))
            ->line('Recuerda seguir las recomendaciones de tu nutricionista para alcanzar tus objetivos de salud.')
            ->line('Si tienes alguna duda sobre tu plan nutricional, no dudes en contactar a tu nutricionista.')
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
            'attention_id' => $this->attention->id,
            'appointment_id' => $this->attention->appointment_id,
            'nutricionista_name' => $this->attention->nutricionista->name,
            'date' => $this->attention->appointment->start_time->format('Y-m-d'),
            'time' => $this->attention->appointment->start_time->format('H:i'),
        ];
    }
}
