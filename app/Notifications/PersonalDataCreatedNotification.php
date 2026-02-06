<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PersonalDataCreatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public User $nutricionista
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
        $nutricionistaName = $this->nutricionista->name;

        return (new MailMessage)
            ->subject('✅ Datos Personales Registrados - NutriFit')
            ->greeting('¡Hola ' . $notifiable->name . '!')
            ->line('Tu nutricionista **Dr(a). ' . $nutricionistaName . '** ha completado el registro de tus datos personales en el sistema.')
            ->line('Ahora tu perfil está completo y puedes acceder a todas las funcionalidades de la plataforma.')
            ->line('📸 **¡Ya puedes personalizar tu perfil!**')
            ->line('Te invitamos a agregar una foto de perfil para que tu experiencia sea más personal. Puedes subir imágenes de hasta 5MB.')
            ->action('Ir a Mi Perfil', url('/paciente/perfil'))
            ->line('Desde tu perfil podrás:')
            ->line('• Agregar o cambiar tu foto de perfil')
            ->line('• Actualizar tu información de contacto')
            ->line('• Ver tus datos personales')
            ->line('¡Gracias por confiar en NutriFit para tu salud nutricional!')
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
            'nutricionista_id' => $this->nutricionista->id,
            'nutricionista_name' => $this->nutricionista->name,
            'action' => 'personal_data_created',
        ];
    }
}
