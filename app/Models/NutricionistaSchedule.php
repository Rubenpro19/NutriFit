<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NutricionistaSchedule extends Model
{
    protected $table = 'nutricionista_schedules';

    protected $fillable = [
        'nutricionista_id',
        'day_of_week',
        'start_time',
        'end_time',
        'consultation_duration',
        'is_active',
    ];

    protected $casts = [
        'day_of_week' => 'integer',
        'consultation_duration' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Relación con el nutricionista
     */
    public function nutricionista(): BelongsTo
    {
        return $this->belongsTo(User::class, 'nutricionista_id');
    }

    /**
     * Obtener el nombre del día en español
     */
    public function getDayNameAttribute(): string
    {
        $days = [
            0 => 'Domingo',
            1 => 'Lunes',
            2 => 'Martes',
            3 => 'Miércoles',
            4 => 'Jueves',
            5 => 'Viernes',
            6 => 'Sábado',
        ];

        return $days[$this->day_of_week] ?? 'Desconocido';
    }

    /**
     * Generar slots de tiempo disponibles para este día
     */
    public function generateTimeSlots(): array
    {
        $slots = [];
        $start = \Carbon\Carbon::parse($this->start_time);
        $end = \Carbon\Carbon::parse($this->end_time);
        $duration = $this->consultation_duration;

        while ($start->lt($end)) {
            $slotEnd = $start->copy()->addMinutes($duration);
            
            if ($slotEnd->lte($end)) {
                $slots[] = [
                    'start' => $start->format('H:i'),
                    'end' => $slotEnd->format('H:i'),
                ];
            }
            
            $start->addMinutes($duration);
        }

        return $slots;
    }

    /**
     * Verificar si un horario específico está disponible
     */
    public function isTimeSlotAvailable(string $date, string $time): bool
    {
        // Verificar que el día de la semana coincida
        $requestedDayOfWeek = \Carbon\Carbon::parse($date)->dayOfWeek;
        
        if ($this->day_of_week !== $requestedDayOfWeek) {
            return false;
        }

        // Verificar que esté dentro del rango de horario
        $requestedTime = \Carbon\Carbon::parse($time);
        $start = \Carbon\Carbon::parse($this->start_time);
        $end = \Carbon\Carbon::parse($this->end_time);

        if ($requestedTime->lt($start) || $requestedTime->gte($end)) {
            return false;
        }

        // Construir la fecha y hora completa del slot
        $requestedDateTime = \Carbon\Carbon::parse($date . ' ' . $time);
        
        // Verificar que el horario no haya pasado (debe ser futuro)
        if ($requestedDateTime->isPast()) {
            return false;
        }

        // Verificar que no exista una cita ACTIVA (pendiente) en ese horario
        // Las citas canceladas, completadas o vencidas no bloquean el horario
        $existingAppointment = Appointment::where('nutricionista_id', $this->nutricionista_id)
            ->where('start_time', $requestedDateTime)
            ->whereHas('appointmentState', function($query) {
                $query->where('name', 'pendiente');
            })
            ->exists();

        return !$existingAppointment;
    }
}
