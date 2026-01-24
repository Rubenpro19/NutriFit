<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserAccountDisabledNotification extends Notification implements ShouldQueue
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
            ->subject('⚠️ Tu cuenta ha sido deshabilitada - NutriFit')
            ->greeting('Hola ' . $notifiable->name . ',')
            ->line('Te informamos que tu cuenta en NutriFit ha sido **deshabilitada** por un administrador.')
            ->line('**¿Qué significa esto?**')
            ->line('• No podrás iniciar sesión en la plataforma')
            ->line('• No podrás agendar nuevas citas')
            ->line('• Tus citas pendientes podrían verse afectadas')
            ->line('Si crees que esto es un error o necesitas más información, por favor contacta al administrador del sistema.')
            ->action('Contactar Soporte', url('/contacto'))
            ->line('Gracias por tu comprensión.')
            ->salutation('Atentamente, ' . config('app.name'));
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
