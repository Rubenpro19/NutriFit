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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('appointment_state_id')->constrained('appointment_states');
            $table->foreignId('paciente_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('nutricionista_id')->constrained('users')->onDelete('cascade');
            $table->timestamp('start_time');
            $table->timestamp('end_time')->nullable();
            $table->text('reason')->nullable(); // Motivo de la consulta
            $table->enum('appointment_type', ['primera_vez', 'seguimiento', 'control'])->default('primera_vez'); // Tipo de consulta
            $table->decimal('price', 8, 2)->nullable(); // Precio de la consulta
            $table->text('notes')->nullable(); // Notas adicionales
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
