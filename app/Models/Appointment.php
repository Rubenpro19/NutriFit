<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Appointment extends Model
{
    protected $table = 'appointments';

    protected $fillable = [
        'appointment_state_id',
        'paciente_id',
        'nutricionista_id',
        'start_time',
        'end_time',
        'notes',
    ];

    public function appointmentState(): BelongsTo
    {
        return $this->belongsTo(AppointmentState::class, 'appointment_state_id');
    }

    public function paciente(): BelongsTo
    {
        return $this->belongsTo(User::class, 'paciente_id');
    }

    public function nutricionista(): BelongsTo
    {
        return $this->belongsTo(User::class, 'nutricionista_id');
    }

    public function attention(): HasOne
    {
        return $this->hasOne(Attention::class, 'appointment_id', 'id');
    }
}
