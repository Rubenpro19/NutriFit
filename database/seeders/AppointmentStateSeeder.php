<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AppointmentState;

class AppointmentStateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $states = [
            [
                'name' => 'pendiente',
                'description' => 'Cita pendiente de atención',
            ],
            [
                'name' => 'completada',
                'description' => 'Cita completada exitosamente',
            ],
            [
                'name' => 'cancelada',
                'description' => 'Cita cancelada por el paciente o nutricionista',
            ],
            [
                'name' => 'vencida',
                'description' => 'Cita vencida por no haber sido atendida',
            ],
        ];

        foreach ($states as $state) {
            AppointmentState::updateOrCreate(
                ['name' => $state['name']],
                $state
            );
        }
    }
}
