<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Appointment;
use App\Models\NutricionistaSchedule;

class NutricionistaController extends Controller
{
    public function index()
    {
        $nutricionista = auth()->user();
        $today = now();

        // Estadísticas del nutricionista
        $stats = [
            // Citas de hoy
            'appointments_today' => Appointment::where('nutricionista_id', $nutricionista->id)
                ->whereDate('start_time', $today)
                ->count(),
            
            // Citas de esta semana
            'appointments_this_week' => Appointment::where('nutricionista_id', $nutricionista->id)
                ->whereBetween('start_time', [$today->copy()->startOfWeek(), $today->copy()->endOfWeek()])
                ->count(),
        ];

        // Próxima cita (la más cercana desde ahora)
        $nextAppointment = Appointment::where('nutricionista_id', $nutricionista->id)
            ->where('start_time', '>=', now())
            ->with(['paciente', 'appointmentState'])
            ->orderBy('start_time', 'asc')
            ->first();

        // Agenda de hoy (todas las citas de hoy)
        $todayAppointments = Appointment::where('nutricionista_id', $nutricionista->id)
            ->whereDate('start_time', $today)
            ->with(['paciente', 'appointmentState'])
            ->orderBy('start_time', 'asc')
            ->get();

        return view('nutricionista.dashboard', compact(
            'stats',
            'nextAppointment',
            'todayAppointments'
        ));
    }

    public function showAppointment(Appointment $appointment)
    {
        // Verificar que la cita pertenece al nutricionista autenticado
        if ($appointment->nutricionista_id !== auth()->id()) {
            abort(403, 'No tienes permiso para ver esta cita.');
        }

        $appointment->load(['paciente.personalData', 'appointmentState', 'attention.attentionData']);

        return view('nutricionista.appointments.show', compact('appointment'));
    }

    /**
     * Mostrar el gestor de horarios del nutricionista
     */
    public function schedules()
    {
        $nutricionista = auth()->user();
        
        // Obtener todos los horarios del nutricionista organizados por día
        $schedules = NutricionistaSchedule::where('nutricionista_id', $nutricionista->id)
            ->orderBy('day_of_week')
            ->get()
            ->groupBy('day_of_week');

        // Crear estructura de días de la semana
        $daysOfWeek = [
            1 => 'Lunes',
            2 => 'Martes',
            3 => 'Miércoles',
            4 => 'Jueves',
            5 => 'Viernes',
            6 => 'Sábado',
            0 => 'Domingo',
        ];

        // Duración de consulta (en minutos)
        $consultationDuration = 45;

        return view('nutricionista.schedules.index', compact('schedules', 'daysOfWeek', 'consultationDuration'));
    }

    /**
     * Guardar o actualizar horarios del nutricionista
     */
    public function saveSchedules(Request $request)
    {
        $nutricionista = auth()->user();
        
        // Eliminar horarios anteriores del nutricionista siempre
        NutricionistaSchedule::where('nutricionista_id', $nutricionista->id)->delete();

        // Si no hay horarios seleccionados, simplemente redirigir
        if (!$request->has('time_slots') || empty($request->time_slots)) {
            return redirect()->route('nutricionista.schedules.index')
                ->with('success', 'Horarios actualizados correctamente (sin disponibilidad configurada)');
        }

        // Validar que los slots tengan el formato correcto
        $request->validate([
            'time_slots' => 'array',
            'time_slots.*' => 'string',
        ]);

        // Organizar los slots por día
        $slotsByDay = [];
        foreach ($request->time_slots as $slot) {
            // El formato es: "día_hora" ejemplo: "1_08:00"
            if (strpos($slot, '_') === false) continue; // Skip si no tiene el formato correcto
            
            [$dayOfWeek, $timeSlot] = explode('_', $slot);
            
            if (!isset($slotsByDay[$dayOfWeek])) {
                $slotsByDay[$dayOfWeek] = [];
            }
            
            $slotsByDay[$dayOfWeek][] = $timeSlot;
        }

        // Crear los horarios para cada día
        foreach ($slotsByDay as $dayOfWeek => $timeSlots) {
            if (empty($timeSlots)) continue;
            
            sort($timeSlots);
            
            // Agrupar slots consecutivos en rangos
            $ranges = [];
            $currentRange = [$timeSlots[0]];
            
            for ($i = 1; $i < count($timeSlots); $i++) {
                $prevTime = \Carbon\Carbon::parse($timeSlots[$i - 1]);
                $currentTime = \Carbon\Carbon::parse($timeSlots[$i]);
                
                // Si el tiempo actual es exactamente 45 minutos después del anterior, es consecutivo
                if ($prevTime->copy()->addMinutes(45)->eq($currentTime)) {
                    $currentRange[] = $timeSlots[$i];
                } else {
                    // Hay una brecha, guardar el rango actual y empezar uno nuevo
                    $ranges[] = $currentRange;
                    $currentRange = [$timeSlots[$i]];
                }
            }
            
            // Agregar el último rango
            $ranges[] = $currentRange;
            
            // Crear un registro por cada rango
            foreach ($ranges as $range) {
                $startTime = reset($range); // Primer slot del rango
                $lastSlot = end($range); // Último slot del rango
                
                // Calcular end_time sumando 45 minutos al último slot
                $endTime = \Carbon\Carbon::parse($lastSlot)->addMinutes(45)->format('H:i');

                NutricionistaSchedule::create([
                    'nutricionista_id' => $nutricionista->id,
                    'day_of_week' => (int)$dayOfWeek,
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                    'consultation_duration' => 45,
                    'is_active' => true,
                ]);
            }
        }

        return redirect()->route('nutricionista.schedules.index')
            ->with('success', 'Horarios guardados correctamente');
    }
}
