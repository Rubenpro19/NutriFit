<?php

namespace Database\Seeders;

use App\Models\SystemSetting;
use Illuminate\Database\Seeder;

class SystemSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SystemSetting::firstOrCreate(
            ['id' => 1],
            [
                'telefono' => '593984668389',
                'email_contacto' => 'nutifit2026@gmail.com',
                'direccion' => 'Santa Ana, Manabí',
                'latitud' => -1.205192,
                'longitud' => -80.372294,
            ]
        );
    }
}
