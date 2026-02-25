<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Database\Seeders\RoleSeeder;
use Database\Seeders\UserStateSeeder;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        
        // Ejecutar los seeders necesarios para que los tests funcionen
        $this->seed([
            UserStateSeeder::class,
            RoleSeeder::class,
        ]);
    }
}
