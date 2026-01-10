<?php

namespace App\Listeners;

use App\Notifications\WelcomeNotification;
use Illuminate\Auth\Events\Verified;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendWelcomeNotification implements ShouldQueue
{
    /**
     * Handle the event.
     */
    public function handle(Verified $event): void
    {
        // Enviar correo de bienvenida cuando el usuario verifica su email
        $event->user->notify(new WelcomeNotification());
    }
}
