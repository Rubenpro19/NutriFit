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
        Schema::create('attention_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attention_id')->constrained('attentions')->onDelete('cascade');
            
            // Medidas básicas
            $table->decimal('weight', 5, 2)->nullable();
            $table->decimal('height', 5, 2)->nullable();
            
            // Medidas corporales
            $table->decimal('waist', 5, 2)->nullable();
            $table->decimal('hip', 5, 2)->nullable();
            $table->decimal('neck', 5, 2)->nullable();
            $table->decimal('wrist', 5, 2)->nullable();
            $table->decimal('arm_contracted', 5, 2)->nullable();
            $table->decimal('arm_relaxed', 5, 2)->nullable();
            $table->decimal('thigh', 5, 2)->nullable();
            $table->decimal('calf', 5, 2)->nullable();
            
            // Nivel de actividad física
            $table->enum('activity_level', ['sedentary', 'light', 'moderate', 'active', 'very_active'])->default('moderate');
            
            // Valores calculados
            $table->decimal('bmi', 5, 2)->nullable();
            $table->decimal('body_fat', 5, 2)->nullable();
            $table->decimal('tmb', 6, 2)->nullable(); // Tasa Metabólica Basal
            $table->decimal('tdee', 6, 2)->nullable(); // Gasto Energético Total
            $table->decimal('whr', 5, 3)->nullable(); // Índice Cintura-Cadera
            $table->decimal('wht', 5, 3)->nullable(); // Índice Cintura-Altura
            $table->decimal('frame_index', 5, 2)->nullable(); // Índice de complexión (Frisancho)
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attention_data');
    }
};
