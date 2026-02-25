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

        // 🔹 Pacientes de prueba
        $pacientes = [
            ['name' => 'Ana Torres',     'email' => 'ana.torres@gmail.com',     'password' => 'ana123'],
            ['name' => 'Carlos Mendoza', 'email' => 'carlos.mendoza@gmail.com', 'password' => 'carlos123'],
            ['name' => 'María López',    'email' => 'maria.lopez@gmail.com',    'password' => 'maria123'],
            ['name' => 'Jorge Ramírez',  'email' => 'jorge.ramirez@gmail.com',  'password' => 'jorge123'],
            ['name' => 'Sofía Castillo', 'email' => 'sofia.castillo@gmail.com', 'password' => 'sofia123'],
            ['name' => 'Ruben Mera', 'email' => 'dariomera05@gmail.com', 'password' => 'ruben1905'],
        ];

        foreach ($pacientes as $paciente) {
            User::create([
                'name'              => $paciente['name'],
                'email'             => $paciente['email'],
                'password'          => Hash::make($paciente['password']),
                'role_id'           => $pacienteRole->id,
                'user_state_id'     => $activoState->id,
                'email_verified_at' => now(),
            ]);
        }

        $nutricionista = [
            ['name' => 'Steven Bravo', 'email' => 'dariomera911@gmail.com', 'password' => 'ruben1905'],
        ];

        foreach ($nutricionista as $nutri) {
            User::create([
                'name'              => $nutri['name'],
                'email'             => $nutri['email'],
                'password'          => Hash::make($nutri['password']),
                'role_id'           => $nutricionistaRole->id,
                'user_state_id'     => $activoState->id,
                'email_verified_at' => now(),
            ]);
        }

        // 🔹 Historial clínico de demostración para Ruben Mera
        $this->call(RubenMeraHistorySeeder::class);
    }
}
