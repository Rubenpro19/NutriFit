<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WelcomeNotification extends Notification implements ShouldQueue
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
            ->subject('🎉 ¡Bienvenido a NutriFit!')
            ->greeting('¡Hola ' . $notifiable->name . '!')
            ->line('¡Gracias por registrarte en NutriFit! Nos alegra que formes parte de nuestra comunidad.')
            ->line('Tu cuenta ha sido creada exitosamente y ya puedes empezar a usar todas nuestras funcionalidades:')
            ->line('✅ Agendar citas con nutricionistas profesionales')
            ->line('✅ Mantener tu historial clínico organizado')
            ->line('✅ Recibir seguimiento continuo de tu progreso')
            ->action('Explorar NutriFit', url('/paciente/dashboard'))
            ->line('Si tienes alguna pregunta o necesitas ayuda, no dudes en contactarnos.')
            ->salutation('¡Bienvenido al equipo! ' . config('app.name'));
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
