<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use App\Notifications\ContactFormNotification;

class ContactController extends Controller
{
    /**
     * Procesar el formulario de contacto
     */
    public function send(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:1000'],
        ], [
            'name.required' => 'El nombre es obligatorio',
            'email.required' => 'El correo electrónico es obligatorio',
            'email.email' => 'El correo electrónico debe ser válido',
            'subject.required' => 'Debes seleccionar un asunto',
            'message.required' => 'El mensaje es obligatorio',
            'message.max' => 'El mensaje no puede exceder 1000 caracteres',
        ]);

        // Por ahora solo registramos el mensaje en los logs
        // En el futuro puedes configurar el envío por email
        Log::info('Nuevo mensaje de contacto recibido', $validated);

        // Enviar notificación por correo al destinatario predefinido
        try {
            Notification::route('mail', 'nutifit2026@gmail.com')
                ->notify(new ContactFormNotification($validated));
            
            Log::info('Notificación de contacto enviada exitosamente');
        } catch (\Exception $e) {
            Log::error('Error al enviar notificación de contacto: ' . $e->getMessage());
            // Continuar aunque falle el envío del correo
        }

        return back()->with('success', '¡Gracias por contactarnos! Tu mensaje ha sido enviado exitosamente. Nos pondremos en contacto contigo pronto.');
    }
}
