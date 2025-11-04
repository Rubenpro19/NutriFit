<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class UserStateSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        DB::table('user_states')->insertOrIgnore([
            ['name' => 'activo', 'description' => 'Usuario activo y con acceso al sistema', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'inactivo', 'description' => 'Usuario registrado pero sin acceso', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'suspendido', 'description' => 'Usuario temporalmente bloqueado', 'created_at' => $now, 'updated_at' => $now],
        ]);
    }
}
