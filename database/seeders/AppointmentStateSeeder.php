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
                'description' => 'Cita pendiente de confirmación',
            ],
            [
                'name' => 'confirmada',
                'description' => 'Cita confirmada por el nutricionista',
            ],
            [
                'name' => 'completada',
                'description' => 'Cita completada exitosamente',
            ],
            [
                'name' => 'cancelada',
                'description' => 'Cita cancelada por el paciente o nutricionista',
            ],
        ];

        foreach ($states as $state) {
            AppointmentState::create($state);
        }
    }
}
