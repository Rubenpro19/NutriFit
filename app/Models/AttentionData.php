<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AttentionData extends Model
{
    protected $table = 'attention_data';

    protected $fillable = [
        'attention_id',
        'weight',
        'height',
        'bmi',
        'waist',
        'hip',
        'body_fat',
        'blood_pressure',
    ];

    protected $casts = [
        'weight' => 'decimal:2',
        'height' => 'decimal:2',
        'bmi' => 'decimal:2',
        'waist' => 'decimal:2',
        'hip' => 'decimal:2',
        'body_fat' => 'decimal:2',
    ];

    public function attention(): BelongsTo
    {
        return $this->belongsTo(Attention::class, 'attention_id');
    }
}
