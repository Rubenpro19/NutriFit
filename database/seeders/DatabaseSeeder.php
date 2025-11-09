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
        ]);

        // 🔹 Obtenemos los roles
        $adminRole = Role::where('name', 'administrador')->first();
        $nutricionistaRole = Role::where('name', 'nutricionista')->first();
        $pacienteRole = Role::where('name', 'paciente')->first();

        // Obtener estado activo
        $activoState = UserState::where('name', 'activo')->first();

        // 🔹 Obtener estado inactivo
        $inactivoState = UserState::where('name', 'inactivo')->first();

        // // 🔹 Obtener estado suspendido
        // $suspendidoState = UserState::where('name', 'suspendido')->first();

        // 🔹 Usuario administrador (contraseña desde .env (opcional))
        User::factory()->create([
            'name' => 'Administrador',
            'email' => 'admin@gmail.com',
            'password' => Hash::make(env('ADMIN_PASSWORD', 'admin123')),
            'role_id' => $adminRole?->id,
            'user_state_id' => $activoState?->id,
        ]);

        // 🔹 Usuario nutricionista específico
        User::factory()->create([
            'name' => 'Ruben',
            'email' => 'ruben@gmail.com',
            'password' => Hash::make('ruben123'),
            'role_id' => $nutricionistaRole?->id,
            'user_state_id' => $activoState?->id,
        ]);

        // 🔹 Usuario paciente específico
        User::factory()->create([
            'name' => 'Luis',
            'email' => 'luis@gmail.com',
            'password' => Hash::make('luis123'),
            'role_id' => $pacienteRole?->id,
            'user_state_id' => $activoState?->id,
        ]);

        // 🔹 Algunos nutricionistas
        User::factory(3)->create([
            'role_id' => $nutricionistaRole?->id,
            'password' => Hash::make('nutri123'),
            'user_state_id' => $activoState?->id,
        ]);

        // 🔹 Algunos pacientes
        User::factory(5)->create([
            'role_id' => $pacienteRole?->id,
            'password' => Hash::make('paciente123'),
            'user_state_id' => $activoState?->id,
        ]);
    }
}
