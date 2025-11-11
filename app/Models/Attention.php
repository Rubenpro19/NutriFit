<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Attention extends Model
{
    protected $table = 'attentions';

    protected $fillable = [
        'appointment_id',
        'paciente_id',
        'nutricionista_id',
        'diagnosis',
        'recommendations',
    ];

    /**
     * Relación con la cita
     */
    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class, 'appointment_id');
    }

    /**
     * Relación con el paciente
     */
    public function paciente(): BelongsTo
    {
        return $this->belongsTo(User::class, 'paciente_id');
    }

    /**
     * Relación con el nutricionista
     */
    public function nutricionista(): BelongsTo
    {
        return $this->belongsTo(User::class, 'nutricionista_id');
    }

    /**
     * Relación con los datos de atención (medidas antropométricas)
     */
    public function attentionData(): HasOne
    {
        return $this->hasOne(AttentionData::class, 'attention_id');
    }
}
