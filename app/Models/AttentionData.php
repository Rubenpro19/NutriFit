<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AttentionData extends Model
{
    protected $table = 'attention_data';

    protected $fillable = [
        'attention_id',
        // Medidas básicas
        'weight',
        'height',
        // Medidas corporales
        'waist',
        'hip',
        'neck',
        'wrist',
        'arm_contracted',
        'arm_relaxed',
        'thigh',
        'calf',
        // Nivel de actividad
        'activity_level',
        // Objetivo nutricional
        'nutrition_goal',
        // Valores calculados - Índices corporales
        'bmi',
        'body_fat',
        'tmb',
        'tdee',
        'whr',
        'wht',
        'frame_index',
        // Macronutrientes y calorías
        'target_calories',
        'protein_grams',
        'fat_grams',
        'carbs_grams',
        // Porcentajes de macronutrientes
        'protein_percentage',
        'fat_percentage',
        'carbs_percentage',
        // Distribución de equivalentes
        'eq_cereales',
        'eq_verduras',
        'eq_frutas',
        'eq_lacteo',
        'eq_animal',
        'eq_aceites',
        'eq_grasas_prot',
        'eq_leguminosas',
        'total_calories_equivalents',
    ];

    protected $casts = [
        'weight' => 'decimal:2',
        'height' => 'decimal:2',
        'waist' => 'decimal:2',
        'hip' => 'decimal:2',
        'neck' => 'decimal:2',
        'wrist' => 'decimal:2',
        'arm_contracted' => 'decimal:2',
        'arm_relaxed' => 'decimal:2',
        'thigh' => 'decimal:2',
        'calf' => 'decimal:2',
        'bmi' => 'decimal:2',
        'body_fat' => 'decimal:2',
        'tmb' => 'decimal:2',
        'tdee' => 'decimal:2',
        'whr' => 'decimal:3',
        'wht' => 'decimal:3',
        'frame_index' => 'decimal:2',
        'target_calories' => 'decimal:2',
        'protein_grams' => 'decimal:2',
        'fat_grams' => 'decimal:2',
        'carbs_grams' => 'decimal:2',
        'protein_percentage' => 'decimal:2',
        'fat_percentage' => 'decimal:2',
        'carbs_percentage' => 'decimal:2',
        'eq_cereales' => 'decimal:2',
        'eq_verduras' => 'decimal:2',
        'eq_frutas' => 'decimal:2',
        'eq_lacteo' => 'decimal:2',
        'eq_animal' => 'decimal:2',
        'eq_aceites' => 'decimal:2',
        'eq_grasas_prot' => 'decimal:2',
        'eq_leguminosas' => 'decimal:2',
        'total_calories_equivalents' => 'decimal:2',
    ];

    public function attention(): BelongsTo
    {
        return $this->belongsTo(Attention::class, 'attention_id');
    }
}
