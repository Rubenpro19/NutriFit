<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PasswordChangedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct() {}

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
        return (new MailMessage)
            ->subject('🔐 Contraseña Actualizada - NutriFit')
            ->greeting('¡Hola ' . $notifiable->name . '!')
            ->line('Tu contraseña ha sido actualizada exitosamente.')
            ->line('Si no realizaste este cambio, por favor contacta inmediatamente con el administrador.')
            ->line('**Fecha y hora:** ' . now()->format('d/m/Y H:i:s'))
            ->action('Ir a Mi Cuenta', url('/paciente/perfil'))
            ->line('Por tu seguridad, nunca compartas tu contraseña.')
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
            'changed_at' => now()->toDateTimeString(),
        ];
    }
}

