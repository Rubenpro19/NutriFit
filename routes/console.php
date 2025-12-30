<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Programar recordatorios de citas para enviar diariamente a las 9:00 AM
Schedule::command('appointments:send-reminders')->dailyAt('09:00');
