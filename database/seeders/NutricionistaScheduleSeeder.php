<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\NutricionistaSchedule;

class NutricionistaScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener todos los nutricionistas
        $roleNutricionista = Role::where('name', 'nutricionista')->first();
        $nutricionistas = User::where('role_id', $roleNutricionista->id)->get();

        foreach ($nutricionistas as $nutricionista) {
            // Configurar horario de Lunes a Viernes (9:00 AM - 5:00 PM)
            for ($day = 1; $day <= 5; $day++) {
                NutricionistaSchedule::create([
                    'nutricionista_id' => $nutricionista->id,
                    'day_of_week' => $day,
                    'start_time' => '09:00:00',
                    'end_time' => '17:00:00',
                    'consultation_duration' => 45, // 45 minutos por consulta
                    'is_active' => true,
                ]);
            }

            // Configurar Sábado (9:00 AM - 1:00 PM) - medio día
            NutricionistaSchedule::create([
                'nutricionista_id' => $nutricionista->id,
                'day_of_week' => 6,
                'start_time' => '09:00:00',
                'end_time' => '13:00:00',
                'consultation_duration' => 45,
                'is_active' => true,
            ]);
        }
    }
}
