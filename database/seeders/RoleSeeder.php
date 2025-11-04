<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['name' => 'administrador', 'description' => 'Acceso total'],
            ['name' => 'nutricionista', 'description' => 'Gestiona citas y atenciones'],
            ['name' => 'paciente', 'description' => 'Agenda citas y consulta historial'],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(
                ['name' => $role['name']],
                [
                    'description' => $role['description'],
                    'updated_at' => Carbon::now(),
                ]
            );
        }
    }
}
