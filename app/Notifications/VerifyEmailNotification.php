<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class VerifyEmailNotification extends VerifyEmail
{
    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable): MailMessage
    {
        $verificationUrl = $this->verificationUrl($notifiable);

        return (new MailMessage)
            ->subject('✅ Verifica tu Correo Electrónico - NutriFit')
            ->greeting('¡Hola ' . $notifiable->name . '!')
            ->line('Gracias por registrarte en NutriFit. Solo falta un paso más para completar tu registro.')
            ->line('Por favor, haz clic en el botón de abajo para verificar tu dirección de correo electrónico:')
            ->action('Verificar Correo Electrónico', $verificationUrl)
            ->line('Este enlace de verificación expirará en 60 minutos.')
            ->line('Si no creaste una cuenta en NutriFit, puedes ignorar este correo de forma segura.')
            ->salutation('¡Te esperamos! ' . config('app.name'));
    }
}
