<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserAccountEnabledNotification extends Notification implements ShouldQueue
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
        $dashboardUrl = match($notifiable->role?->name) {
            'administrador' => url('/admin/dashboard'),
            'nutricionista' => url('/nutricionista/dashboard'),
            'paciente' => url('/paciente/dashboard'),
            default => url('/dashboard')
        };

        return (new MailMessage)
            ->subject('✅ Tu cuenta ha sido habilitada - NutriFit')
            ->greeting('¡Hola ' . $notifiable->name . '!')
            ->line('Te informamos que tu cuenta en NutriFit ha sido **habilitada** nuevamente.')
            ->line('**Ya puedes:**')
            ->line('✅ Iniciar sesión en la plataforma')
            ->line('✅ Acceder a todas las funcionalidades')
            ->line('✅ Continuar usando tu cuenta con normalidad')
            ->action('Ir a mi Dashboard', $dashboardUrl)
            ->line('¡Nos alegra tenerte de vuelta!')
            ->salutation('Bienvenido nuevamente, ' . config('app.name'));
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
