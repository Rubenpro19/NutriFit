<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Appointment;
use App\Models\AppointmentState;
use App\Models\NutricionistaSchedule;
use Carbon\Carbon;

class PacienteController extends Controller
{
    public function index()
    {
        $paciente = auth()->user();
        
        // Marcar citas vencidas
        Appointment::markExpiredAppointments();

        // Estadísticas del paciente
        $stats = [
            'total' => Appointment::where('paciente_id', $paciente->id)->count(),
            'completadas' => Appointment::where('paciente_id', $paciente->id)
                ->whereHas('appointmentState', fn($q) => $q->where('name', 'completada'))
                ->count(),
            'pendientes' => Appointment::where('paciente_id', $paciente->id)
                ->whereHas('appointmentState', fn($q) => $q->where('name', 'pendiente'))
                ->count(),
        ];

        // Próxima cita
        $nextAppointment = Appointment::where('paciente_id', $paciente->id)
            ->whereHas('appointmentState', fn($q) => $q->where('name', 'pendiente'))
            ->where('start_time', '>', now())
            ->with(['nutricionista', 'appointmentState'])
            ->orderBy('start_time')
            ->first();

        // Historial de citas recientes
        $recentAppointments = Appointment::where('paciente_id', $paciente->id)
            ->with(['nutricionista', 'appointmentState'])
            ->orderBy('start_time', 'desc')
            ->take(10)
            ->get();

        // Lista de nutricionistas disponibles
        $nutricionistas = User::whereHas('role', fn($q) => $q->where('name', 'nutricionista'))
            ->whereHas('userState', fn($q) => $q->where('name', 'activo'))
            ->withCount('schedules')
            ->get();

        return view('paciente.dashboard', compact('stats', 'nextAppointment', 'recentAppointments', 'nutricionistas'));
    }

    /**
     * Mostrar lista de nutricionistas para agendar
     */
    public function showBooking()
    {
        $nutricionistas = User::whereHas('role', fn($q) => $q->where('name', 'nutricionista'))
            ->whereHas('userState', fn($q) => $q->where('name', 'activo'))
            ->withCount('schedules')
            ->get();

        return view('paciente.booking.index', compact('nutricionistas'));
    }

    /**
     * Mostrar horarios disponibles de un nutricionista
     */
    public function selectSchedule(User $nutricionista)
    {
        $paciente = auth()->user();

        // VALIDACIÓN: Verificar si el paciente ya tiene una cita pendiente
        $hasActiveAppointment = Appointment::where('paciente_id', $paciente->id)
            ->whereHas('appointmentState', fn($q) => $q->where('name', 'pendiente'))
            ->exists();

        if ($hasActiveAppointment) {
            return redirect()->route('paciente.dashboard')
                ->with('error', 'Ya tienes una cita pendiente. Debes esperar a que se complete o cancele antes de agendar otra.');
        }

        // Obtener horarios del nutricionista
        $schedules = NutricionistaSchedule::where('nutricionista_id', $nutricionista->id)
            ->where('is_active', true)
            ->orderBy('day_of_week')
            ->get()
            ->groupBy('day_of_week');

        if ($schedules->isEmpty()) {
            return redirect()->route('paciente.booking.index')
                ->with('error', 'Este nutricionista no tiene horarios disponibles en este momento.');
        }

        // Generar 4 semanas desde hoy
        $weeks = [];
        $startDate = Carbon::now()->startOfWeek();
        
        for ($i = 0; $i < 4; $i++) {
            $week = [];
            for ($j = 0; $j < 7; $j++) {
                $date = $startDate->copy()->addDays(($i * 7) + $j);
                $week[] = [
                    'date' => $date,
                    'dayOfWeek' => $date->dayOfWeek,
                    'isToday' => $date->isToday(),
                    'isPast' => $date->isPast() && !$date->isToday(),
                ];
            }
            $weeks[] = $week;
        }

        return view('paciente.booking.schedule', compact('nutricionista', 'schedules', 'weeks'));
    }

    /**
     * Guardar una nueva cita
     */
    public function storeAppointment(Request $request, User $nutricionista)
    {
        $paciente = auth()->user();

        // VALIDACIÓN: Verificar nuevamente si el paciente ya tiene una cita activa
        $hasActiveAppointment = Appointment::where('paciente_id', $paciente->id)
            ->whereHas('appointmentState', fn($q) => $q->where('name', 'pendiente'))
            ->exists();

        if ($hasActiveAppointment) {
            return back()->with('error', 'Ya tienes una cita pendiente. Debes esperar a que se complete o cancele antes de agendar otra.');
        }

        $request->validate([
            'date' => 'required|date|after_or_equal:today',
            'time' => 'required',
            'reason' => 'nullable|string|max:500',
            'appointment_type' => 'required|in:primera_vez,seguimiento,control',
        ]);

        // Construir fecha y hora completa
        $startTime = Carbon::parse($request->date . ' ' . $request->time);
        $endTime = $startTime->copy()->addMinutes(45);

        // Verificar que el horario esté disponible
        $dayOfWeek = $startTime->dayOfWeek;
        $schedule = NutricionistaSchedule::where('nutricionista_id', $nutricionista->id)
            ->where('day_of_week', $dayOfWeek)
            ->where('is_active', true)
            ->first();

        if (!$schedule) {
            return back()->with('error', 'El nutricionista no tiene disponibilidad para ese día.');
        }

        // Verificar que el horario esté dentro del rango del schedule
        if (!$schedule->isTimeSlotAvailable($request->date, $request->time)) {
            return back()->with('error', 'El horario seleccionado no está disponible.');
        }

        // Crear la cita con estado pendiente
        $pendienteState = AppointmentState::where('name', 'pendiente')->first();

        Appointment::create([
            'paciente_id' => $paciente->id,
            'nutricionista_id' => $nutricionista->id,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'appointment_state_id' => $pendienteState->id,
            'reason' => $request->reason,
            'appointment_type' => $request->appointment_type,
            'price' => $request->appointment_type === 'primera_vez' ? 150.00 : ($request->appointment_type === 'seguimiento' ? 120.00 : 100.00),
        ]);

        return redirect()->route('paciente.dashboard')
            ->with('success', '¡Cita agendada exitosamente! El nutricionista la confirmará pronto.');
    }

    /**
     * Cancelar una cita
     */
    public function cancelAppointment(Appointment $appointment)
    {
        // Verificar que la cita pertenezca al paciente actual
        if ($appointment->paciente_id !== auth()->id()) {
            abort(403, 'No tienes permiso para cancelar esta cita.');
        }

        $cancelledState = AppointmentState::where('name', 'cancelada')->first();
        
        $appointment->update([
            'appointment_state_id' => $cancelledState->id
        ]);

        return back()->with('success', 'Cita cancelada exitosamente.');
    }

    /**
     * Mostrar el historial de citas del paciente
     */
    public function appointments(Request $request)
    {
        $paciente = auth()->user();

        // Marcar citas vencidas
        Appointment::markExpiredAppointments();

        // Query base
        $query = Appointment::where('paciente_id', $paciente->id)
            ->with(['nutricionista.personalData', 'appointmentState', 'attention']);

        // Filtrar por estado si se proporciona
        if ($request->has('estado') && $request->estado !== '') {
            $query->whereHas('appointmentState', function($q) use ($request) {
                $q->where('name', $request->estado);
            });
        }

        // Ordenar por fecha más reciente
        $appointments = $query->orderBy('start_time', 'desc')->paginate(10);

        return view('paciente.appointments.index', compact('appointments'));
    }

    /**
     * Mostrar el detalle de una cita específica
     */
    public function showAppointment(Appointment $appointment)
    {
        // Verificar que la cita pertenezca al paciente autenticado
        if ($appointment->paciente_id !== auth()->id()) {
            abort(403, 'No tienes permiso para ver esta cita.');
        }

        // Cargar relaciones necesarias
        $appointment->load([
            'nutricionista.personalData',
            'appointmentState',
            'attention.attentionData'
        ]);

        return view('paciente.appointments.show', compact('appointment'));
    }
}
