<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ContactFormNotification extends Notification
{
    use Queueable;

    protected $contactData;

    /**
     * Create a new notification instance.
     */
    public function __construct(array $contactData)
    {
        $this->contactData = $contactData;
    }

    /**
     * Get the notification's delivery channels.
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
            ->subject('Nuevo mensaje de contacto - ' . $this->contactData['subject'])
            ->greeting('¡Nuevo mensaje de contacto!')
            ->line('Has recibido un nuevo mensaje a través del formulario de contacto de NutriFit.')
            ->line('')
            ->line('**Detalles del mensaje:**')
            ->line('**Nombre:** ' . $this->contactData['name'])
            ->line('**Email:** ' . $this->contactData['email'])
            ->line('**Teléfono:** ' . ($this->contactData['phone'] ?? 'No proporcionado'))
            ->line('**Asunto:** ' . $this->contactData['subject'])
            ->line('')
            ->line('**Mensaje:**')
            ->line($this->contactData['message'])
            ->line('')
            ->line('Por favor, responde a este mensaje lo antes posible.')
            ->salutation('Saludos cordiales, Sistema NutriFit');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'name' => $this->contactData['name'],
            'email' => $this->contactData['email'],
            'subject' => $this->contactData['subject'],
        ];
    }
}
