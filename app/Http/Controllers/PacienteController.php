<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Appointment;
use App\Models\Attention;
use App\Models\NutricionistaSchedule;
use App\Models\AppointmentState;
use App\Models\Role;
use Carbon\Carbon;

class PacienteController extends Controller
{
    public function index()
    {
        $paciente = auth()->user();
        $today = now();

        // Estadísticas del paciente
        $stats = [
            // Total de citas agendadas
            'total_appointments' => Appointment::where('paciente_id', $paciente->id)->count(),
            
            // Citas completadas (con atención registrada)
            'completed_appointments' => Attention::where('paciente_id', $paciente->id)->count(),
            
            // Citas pendientes (futuras)
            'pending_appointments' => Appointment::where('paciente_id', $paciente->id)
                ->where('start_time', '>=', now())
                ->whereIn('appointment_state_id', function($query) {
                    $query->select('id')
                        ->from('appointment_states')
                        ->whereIn('name', ['pendiente', 'confirmada']);
                })
                ->count(),
        ];

        // Próxima cita (la más cercana desde ahora)
        $nextAppointment = Appointment::where('paciente_id', $paciente->id)
            ->where('start_time', '>=', now())
            ->with(['nutricionista', 'appointmentState'])
            ->orderBy('start_time', 'asc')
            ->first();

        // Historial de citas (últimas 5)
        $recentAppointments = Appointment::where('paciente_id', $paciente->id)
            ->with(['nutricionista', 'appointmentState'])
            ->orderBy('start_time', 'desc')
            ->limit(5)
            ->get();

        // Nutricionistas disponibles para agendar
        $nutricionistas = User::whereHas('role', function($query) {
                $query->where('name', 'nutricionista');
            })
            ->where('user_state_id', function($query) {
                $query->select('id')
                    ->from('user_states')
                    ->where('name', 'activo')
                    ->limit(1);
            })
            ->limit(3)
            ->get();

        return view('paciente.dashboard', compact(
            'stats',
            'nextAppointment',
            'recentAppointments',
            'nutricionistas'
        ));
    }

    /**
     * Mostrar lista de nutricionistas para agendar
     */
    public function showBooking()
    {
        // Obtener todos los nutricionistas activos
        $nutricionistas = User::whereHas('role', function($query) {
                $query->where('name', 'nutricionista');
            })
            ->where('user_state_id', function($query) {
                $query->select('id')
                    ->from('user_states')
                    ->where('name', 'activo')
                    ->limit(1);
            })
            ->withCount(['schedules' => function($query) {
                $query->where('is_active', true);
            }])
            ->get();

        return view('paciente.booking.index', compact('nutricionistas'));
    }

    /**
     * Mostrar horarios disponibles del nutricionista seleccionado
     */
    public function selectSchedule(User $nutricionista)
    {
        // Verificar que el usuario sea nutricionista
        if ($nutricionista->role->name !== 'nutricionista') {
            abort(403, 'Usuario no válido');
        }

        // Obtener horarios del nutricionista
        $schedules = NutricionistaSchedule::where('nutricionista_id', $nutricionista->id)
            ->where('is_active', true)
            ->orderBy('day_of_week')
            ->get()
            ->groupBy('day_of_week');

        // Generar próximas 4 semanas
        $weeks = [];
        $startDate = Carbon::now()->startOfWeek();
        
        for ($weekNum = 0; $weekNum < 4; $weekNum++) {
            $week = [];
            
            for ($dayNum = 0; $dayNum < 7; $dayNum++) {
                $date = $startDate->copy()->addDays($weekNum * 7 + $dayNum);
                $dayOfWeek = $date->dayOfWeek;
                
                $week[] = [
                    'date' => $date,
                    'dayOfWeek' => $dayOfWeek,
                    'hasSchedule' => isset($schedules[$dayOfWeek]),
                ];
            }
            
            $weeks[] = $week;
        }

        return view('paciente.booking.schedule', compact('nutricionista', 'schedules', 'weeks'));
    }

    /**
     * Guardar la cita agendada
     */
    public function storeAppointment(Request $request, User $nutricionista)
    {
        $request->validate([
            'date' => 'required|date|after_or_equal:today',
            'time' => 'required',
            'appointment_type' => 'required|in:primera_vez,seguimiento,control',
            'reason' => 'nullable|string|max:500',
        ], [
            'date.required' => 'Debe seleccionar una fecha',
            'date.after_or_equal' => 'La fecha debe ser hoy o posterior',
            'time.required' => 'Debe seleccionar un horario',
            'appointment_type.required' => 'Debe seleccionar el tipo de consulta',
        ]);

        // Verificar que el horario esté disponible
        $date = Carbon::parse($request->date);
        $time = $request->time;
        $dayOfWeek = $date->dayOfWeek;

        // Buscar el schedule del nutricionista para ese día
        $schedule = NutricionistaSchedule::where('nutricionista_id', $nutricionista->id)
            ->where('day_of_week', $dayOfWeek)
            ->where('is_active', true)
            ->where('start_time', '<=', $time)
            ->where('end_time', '>', $time)
            ->first();

        if (!$schedule) {
            return back()->with('error', 'El horario seleccionado no está disponible');
        }

        // Verificar que no haya otra cita en ese horario
        $startTime = Carbon::parse($request->date . ' ' . $request->time);
        $endTime = $startTime->copy()->addMinutes(45);

        $existingAppointment = Appointment::where('nutricionista_id', $nutricionista->id)
            ->where(function($query) use ($startTime, $endTime) {
                $query->whereBetween('start_time', [$startTime, $endTime])
                    ->orWhereBetween('end_time', [$startTime, $endTime])
                    ->orWhere(function($q) use ($startTime, $endTime) {
                        $q->where('start_time', '<=', $startTime)
                          ->where('end_time', '>=', $endTime);
                    });
            })
            ->whereIn('appointment_state_id', function($query) {
                $query->select('id')
                    ->from('appointment_states')
                    ->whereIn('name', ['pendiente', 'confirmada']);
            })
            ->exists();

        if ($existingAppointment) {
            return back()->with('error', 'Este horario ya está ocupado. Por favor, selecciona otro.');
        }

        // Obtener el estado "pendiente"
        $pendienteState = AppointmentState::where('name', 'pendiente')->first();

        // Crear la cita
        Appointment::create([
            'paciente_id' => auth()->id(),
            'nutricionista_id' => $nutricionista->id,
            'appointment_state_id' => $pendienteState->id,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'appointment_type' => $request->appointment_type,
            'reason' => $request->reason,
        ]);

        return redirect()->route('paciente.dashboard')
            ->with('success', '¡Cita agendada exitosamente! El nutricionista confirmará tu cita pronto.');
    }

    /**
     * Ver detalles de una cita
     */
    public function showAppointment(Appointment $appointment)
    {
        // Verificar que la cita pertenezca al paciente autenticado
        if ($appointment->paciente_id !== auth()->id()) {
            abort(403, 'No tienes permiso para ver esta cita.');
        }

        $appointment->load(['nutricionista', 'appointmentState', 'attention.attentionData']);

        return view('paciente.appointments.show', compact('appointment'));
    }

    /**
     * Cancelar una cita
     */
    public function cancelAppointment(Appointment $appointment)
    {
        // Verificar que la cita pertenezca al paciente autenticado
        if ($appointment->paciente_id !== auth()->id()) {
            abort(403, 'No tienes permiso para cancelar esta cita.');
        }

        // Verificar que la cita sea futura
        if (Carbon::parse($appointment->start_time)->isPast()) {
            return back()->with('error', 'No puedes cancelar una cita pasada.');
        }

        // Obtener el estado "cancelada"
        $canceladaState = AppointmentState::where('name', 'cancelada')->first();

        // Actualizar el estado de la cita
        $appointment->update([
            'appointment_state_id' => $canceladaState->id,
        ]);

        return back()->with('success', 'Cita cancelada correctamente.');
    }

    /**
     * Mostrar todas las citas del paciente
     */
    public function myAppointments()
    {
        $paciente = auth()->user();

        $appointments = Appointment::where('paciente_id', $paciente->id)
            ->with(['nutricionista', 'appointmentState'])
            ->orderBy('start_time', 'desc')
            ->paginate(10);

        return view('paciente.appointments.index', compact('appointments'));
    }
}
