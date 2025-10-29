<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }
}
