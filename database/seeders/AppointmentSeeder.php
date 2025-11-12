<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\Appointment;
use App\Models\AppointmentState;
use Carbon\Carbon;

class AppointmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener roles
        $roleNutricionista = Role::where('name', 'nutricionista')->first();
        $rolePaciente = Role::where('name', 'paciente')->first();

        // Obtener un nutricionista (Ruben)
        $nutricionista = User::where('email', 'ruben@gmail.com')->first();
        
        // Obtener pacientes
        $pacientes = User::where('role_id', $rolePaciente->id)->take(5)->get();

        // Obtener estados
        $pendiente = AppointmentState::where('name', 'pendiente')->first();
        $confirmada = AppointmentState::where('name', 'confirmada')->first();

        // Crear citas para hoy
        $today = Carbon::today();
        $appointments = [
            [
                'nutricionista_id' => $nutricionista->id,
                'paciente_id' => $pacientes[0]->id,
                'start_time' => $today->copy()->setTime(9, 0, 0),
                'end_time' => $today->copy()->setTime(9, 45, 0),
                'appointment_state_id' => $confirmada->id,
                'reason' => 'Primera consulta nutricional',
                'appointment_type' => 'primera_vez',
                'price' => 150.00,
            ],
            [
                'nutricionista_id' => $nutricionista->id,
                'paciente_id' => $pacientes[1]->id,
                'start_time' => $today->copy()->setTime(10, 0, 0),
                'end_time' => $today->copy()->setTime(10, 45, 0),
                'appointment_state_id' => $confirmada->id,
                'reason' => 'Seguimiento de dieta',
                'appointment_type' => 'seguimiento',
                'price' => 120.00,
            ],
            [
                'nutricionista_id' => $nutricionista->id,
                'paciente_id' => $pacientes[2]->id,
                'start_time' => $today->copy()->setTime(15, 0, 0),
                'end_time' => $today->copy()->setTime(15, 45, 0),
                'appointment_state_id' => $pendiente->id,
                'reason' => 'Control de peso',
                'appointment_type' => 'control',
                'price' => 100.00,
            ],
            [
                'nutricionista_id' => $nutricionista->id,
                'paciente_id' => $pacientes[3]->id,
                'start_time' => $today->copy()->setTime(16, 0, 0),
                'end_time' => $today->copy()->setTime(16, 45, 0),
                'appointment_state_id' => $confirmada->id,
                'reason' => 'Consulta general',
                'appointment_type' => 'primera_vez',
                'price' => 150.00,
            ],
        ];

        // Crear citas para mañana
        $tomorrow = Carbon::tomorrow();
        $appointments[] = [
            'nutricionista_id' => $nutricionista->id,
            'paciente_id' => $pacientes[4]->id,
            'start_time' => $tomorrow->copy()->setTime(9, 0, 0),
            'end_time' => $tomorrow->copy()->setTime(9, 45, 0),
            'appointment_state_id' => $pendiente->id,
            'reason' => 'Evaluación inicial',
            'appointment_type' => 'primera_vez',
            'price' => 150.00,
        ];

        // Crear citas para esta semana
        $nextMonday = Carbon::now()->next(Carbon::MONDAY);
        $appointments[] = [
            'nutricionista_id' => $nutricionista->id,
            'paciente_id' => $pacientes[0]->id,
            'start_time' => $nextMonday->copy()->setTime(11, 0, 0),
            'end_time' => $nextMonday->copy()->setTime(11, 45, 0),
            'appointment_state_id' => $confirmada->id,
            'reason' => 'Revisión de avances',
            'appointment_type' => 'seguimiento',
            'price' => 120.00,
        ];

        foreach ($appointments as $appointmentData) {
            Appointment::create($appointmentData);
        }
    }
}
