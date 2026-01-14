<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NutricionistaSettings extends Model
{
    protected $table = 'nutricionista_settings';

    protected $fillable = [
        'user_id',
        'consultation_price',
    ];

    protected $casts = [
        'consultation_price' => 'decimal:2',
    ];

    /**
     * Relación con el usuario (nutricionista)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
