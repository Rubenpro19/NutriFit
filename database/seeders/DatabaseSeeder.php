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
        ]);

        // 🔹 Obtenemos los roles
        $adminRole = Role::where('name', 'administrador')->first();
        $nutricionistaRole = Role::where('name', 'nutricionista')->first();
        $pacienteRole = Role::where('name', 'paciente')->first();

        // Obtener estado activo
        $activoState = UserState::where('name', 'activo')->first();

        // 🔹 Obtener estado inactivo
        $inactivoState = UserState::where('name', 'inactivo')->first();

        // 🔹 Algunos pacientes
        User::factory(3)->create([
            'role_id' => $pacienteRole?->id,
            'password' => Hash::make('paciente123'),
            'user_state_id' => $activoState?->id,
        ]);

        // 🔹 Usuario administrador (contraseña desde .env)
        User::factory()->create([
            'name' => 'Administrador',
            'email' => 'admin@gmail.com',
            'password' => Hash::make(env('ADMIN_PASSWORD', 'admin123')),
            'role_id' => $adminRole?->id,
            'user_state_id' => $activoState?->id,
        ]);

        // 🔹 Usuario nutricionista específico
        User::factory()->create([
            'name' => 'Ruben Mera',
            'email' => 'ruben@gmail.com',
            'password' => Hash::make('ruben123'),
            'role_id' => $nutricionistaRole?->id,
            'user_state_id' => $activoState?->id,
        ]);

        // 🔹 Usuario nutricionista específico
        User::factory()->create([
            'name' => 'Rossy Vélez',
            'email' => 'rossy@gmail.com',
            'password' => Hash::make('rossy123'),
            'role_id' => $nutricionistaRole?->id,
            'user_state_id' => $activoState?->id,
        ]);

        // 🔹 Usuario paciente específico
        User::factory()->create([
            'name' => 'Luis Bravo',
            'email' => 'luis@gmail.com',
            'password' => Hash::make('luis123'),
            'role_id' => $pacienteRole?->id,
            'user_state_id' => $activoState?->id,
        ]);

        // 🔹 Usuario paciente específico
        User::factory()->create([
            'name' => 'Pepe Gonzales',
            'email' => 'pepe@gmail.com',
            'password' => Hash::make('pepe123'),
            'role_id' => $pacienteRole?->id,
            'user_state_id' => $activoState?->id,
        ]);
    }
}
