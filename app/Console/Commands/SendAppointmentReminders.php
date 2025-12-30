<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Appointment;
use App\Notifications\AppointmentReminderNotification;
use Carbon\Carbon;

class SendAppointmentReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'appointments:send-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envía recordatorios de citas que serán mañana';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Buscando citas para mañana...');

        // Obtener citas para mañana (confirmadas o pendientes)
        $tomorrow = Carbon::tomorrow()->toDateString();
        
        $appointments = Appointment::whereDate('start_time', $tomorrow)
            ->whereHas('appointmentState', function($query) {
                $query->whereIn('name', ['confirmada', 'pendiente']);
            })
            ->with(['paciente', 'nutricionista'])
            ->get();

        if ($appointments->isEmpty()) {
            $this->info('No hay citas programadas para mañana.');
            return 0;
        }

        $this->info("Se encontraron {$appointments->count()} citas para mañana.");

        foreach ($appointments as $appointment) {
            // Notificar al paciente
            $appointment->paciente->notify(new AppointmentReminderNotification($appointment));
            $this->info("✓ Recordatorio enviado a paciente: {$appointment->paciente->name}");

            // Notificar al nutricionista
            $appointment->nutricionista->notify(new AppointmentReminderNotification($appointment));
            $this->info("✓ Recordatorio enviado a nutricionista: {$appointment->nutricionista->name}");
        }

        $this->info('¡Recordatorios enviados exitosamente!');
        return 0;
    }
}

