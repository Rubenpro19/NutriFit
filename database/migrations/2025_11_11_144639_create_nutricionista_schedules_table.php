<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('nutricionista_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nutricionista_id')->constrained('users')->onDelete('cascade');
            $table->tinyInteger('day_of_week'); // 0=Domingo, 1=Lunes, 2=Martes, 3=Miércoles, 4=Jueves, 5=Viernes, 6=Sábado
            $table->time('start_time'); // Hora de inicio (ej: 09:00:00)
            $table->time('end_time'); // Hora de fin (ej: 17:00:00)
            $table->integer('consultation_duration')->default(45); // Duración de consulta en minutos
            $table->boolean('is_active')->default(true); // Si el horario está activo
            $table->timestamps();
            
            // Índice para búsquedas rápidas (sin unique para permitir múltiples rangos por día)
            $table->index(['nutricionista_id', 'day_of_week']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nutricionista_schedules');
    }
};
