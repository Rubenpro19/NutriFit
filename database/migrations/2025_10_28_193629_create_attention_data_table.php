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
            
            // Objetivo nutricional
            $table->enum('nutrition_goal', ['deficit', 'maintenance', 'surplus'])->default('maintenance');
            
            // Valores calculados - Índices corporales
            $table->decimal('bmi', 5, 2)->nullable();
            $table->decimal('body_fat', 5, 2)->nullable();
            $table->decimal('tmb', 6, 2)->nullable(); // Tasa Metabólica Basal
            $table->decimal('tdee', 6, 2)->nullable(); // Gasto Energético Total
            $table->decimal('whr', 5, 3)->nullable(); // Índice Cintura-Cadera
            $table->decimal('wht', 5, 3)->nullable(); // Índice Cintura-Altura
            $table->decimal('frame_index', 5, 2)->nullable(); // Índice de complexión (Frisancho)
            
            // Macronutrientes y calorías objetivo
            $table->decimal('target_calories', 6, 2)->nullable(); // Calorías objetivo según nutrition_goal
            $table->decimal('protein_grams', 6, 2)->nullable(); // Gramos de proteína diarios
            $table->decimal('fat_grams', 6, 2)->nullable(); // Gramos de grasa diarios
            $table->decimal('carbs_grams', 6, 2)->nullable(); // Gramos de carbohidratos diarios
            
            // Porcentajes de macronutrientes
            $table->decimal('protein_percentage', 5, 2)->nullable(); // % de proteínas
            $table->decimal('fat_percentage', 5, 2)->nullable(); // % de grasas
            $table->decimal('carbs_percentage', 5, 2)->nullable(); // % de carbohidratos
            
            // Distribución de equivalentes
            $table->decimal('eq_cereales', 5, 2)->nullable()->default(0);
            $table->decimal('eq_verduras', 5, 2)->nullable()->default(0);
            $table->decimal('eq_frutas', 5, 2)->nullable()->default(0);
            $table->decimal('eq_lacteo', 5, 2)->nullable()->default(0);
            $table->decimal('eq_animal', 5, 2)->nullable()->default(0);
            $table->decimal('eq_aceites', 5, 2)->nullable()->default(0);
            $table->decimal('eq_grasas_prot', 5, 2)->nullable()->default(0);
            $table->decimal('eq_leguminosas', 5, 2)->nullable()->default(0);
            $table->decimal('total_calories_equivalents', 6, 2)->nullable(); // Total de calorías de equivalentes
            
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
