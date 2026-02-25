<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;
use Database\Seeders\RoleSeeder;
use App\Models\UserState;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 🔹 Se crean los roles y los estados de usuario
        $this->call([
            RoleSeeder::class,
            UserStateSeeder::class,
            AppointmentStateSeeder::class,
            SystemSettingSeeder::class,
        ]);

        // 🔹 Obtenemos los roles
        $adminRole = Role::where('name', 'administrador')->first();
        $nutricionistaRole = Role::where('name', 'nutricionista')->first();
        $pacienteRole = Role::where('name', 'paciente')->first();

        // Obtener estado activo
        $activoState = UserState::where('name', 'activo')->first();

        // 🔹 Obtener estado inactivo
        $inactivoState = UserState::where('name', 'inactivo')->first();

        // Administrador
        User::create([
            'name' => 'Administrador',
            'email' => 'nutifit2026@gmail.com',
            'password' => Hash::make(env('ADMIN_PASSWORD', 'NutriAdmin123')),
            'role_id' => $adminRole->id,
            'user_state_id' => $activoState->id,
            'email_verified_at' => now(),
        ]);

        // 🔹 Historial clínico de demostración para Ruben Mera
        // $this->call(RubenMeraHistorySeeder::class);
    }
}
