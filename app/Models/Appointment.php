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
        'reason',
        'appointment_type',
        'price',
        'notes',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'price' => 'decimal:2',
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

    /**
     * Marcar citas vencidas automáticamente
     */
    public static function markExpiredAppointments(): int
    {
        $vencidaState = AppointmentState::where('name', 'vencida')->first();
        
        if (!$vencidaState) {
            return 0;
        }

        // Actualizar citas que ya pasaron y aún están pendientes
        return self::whereHas('appointmentState', fn($q) => $q->where('name', 'pendiente'))
            ->where('end_time', '<', now())
            ->update(['appointment_state_id' => $vencidaState->id]);
    }

    /**
     * Verificar si una cita está vencida
     */
    public function isExpired(): bool
    {
        return $this->end_time < now() && $this->appointmentState->name === 'pendiente';
    }
}
