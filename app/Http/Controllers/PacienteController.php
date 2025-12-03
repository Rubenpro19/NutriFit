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

        // VALIDACIÓN: Verificar si el paciente ya tiene una cita pendiente (con cualquier nutricionista)
        $hasActiveAppointment = Appointment::where('paciente_id', $paciente->id)
            ->whereHas('appointmentState', fn($q) => $q->where('name', 'pendiente'))
            ->exists();

        if ($hasActiveAppointment) {
            return redirect()->route('paciente.dashboard')
                ->with('error', 'Ya tienes una cita pendiente. Solo puedes tener una cita activa a la vez. Debes esperar a que se complete o cancele antes de agendar otra.');
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

        // Generar 4 semanas desde el inicio de la semana actual (lunes)
        $weeks = [];
        $startDate = Carbon::now()->startOfWeek(); // Empieza el lunes
        
        for ($weekIndex = 0; $weekIndex < 4; $weekIndex++) {
            $weekDays = [];
            
            for ($dayIndex = 0; $dayIndex < 7; $dayIndex++) {
                $date = $startDate->copy()->addDays(($weekIndex * 7) + $dayIndex);
                $dateStr = $date->format('Y-m-d');
                $dayOfWeek = $date->dayOfWeek;
                
                $dayData = [
                    'date' => $date,
                    'date_formatted' => $date->locale('es')->isoFormat('dddd, D [de] MMMM'),
                    'day_name' => $date->locale('es')->isoFormat('dddd'),
                    'day_number' => $date->format('d'),
                    'is_today' => $date->isToday(),
                    'is_past' => $date->isPast() && !$date->isToday(),
                    'slots' => []
                ];
                
                // Si el día no es pasado y existe horario configurado
                if (!$dayData['is_past'] && isset($schedules[$dayOfWeek])) {
                    foreach ($schedules[$dayOfWeek] as $schedule) {
                        $startTime = Carbon::parse($schedule->start_time);
                        $endTime = Carbon::parse($schedule->end_time);
                        $currentTime = $startTime->copy();
                        
                        while ($currentTime->lt($endTime)) {
                            $slot = $currentTime->format('H:i');
                            $isAvailable = $schedule->isTimeSlotAvailable($dateStr, $slot);
                            
                            if ($isAvailable) {
                                $dayData['slots'][] = [
                                    'time' => $slot,
                                    'time_formatted' => $currentTime->format('h:i A'),
                                ];
                            }
                            
                            $currentTime->addMinutes(45);
                        }
                    }
                }
                
                // Solo incluir días que tienen slots disponibles
                if (!empty($dayData['slots'])) {
                    $weekDays[] = $dayData;
                }
            }
            
            // Solo añadir la semana si tiene al menos un día con slots
            if (!empty($weekDays)) {
                $weeks[] = [
                    'week_number' => $weekIndex + 1,
                    'start_date' => $weekDays[0]['date'],
                    'start_date_formatted' => $weekDays[0]['date']->locale('es')->isoFormat('D MMM'),
                    'days' => $weekDays
                ];
            }
        }

        return view('paciente.booking.schedule', compact('nutricionista', 'weeks'));
    }

    /**
     * Guardar una nueva cita
     */
    public function storeAppointment(Request $request, User $nutricionista)
    {
        $paciente = auth()->user();

        // VALIDACIÓN: Verificar nuevamente si el paciente ya tiene una cita activa (con cualquier nutricionista)
        $hasActiveAppointment = Appointment::where('paciente_id', $paciente->id)
            ->whereHas('appointmentState', fn($q) => $q->where('name', 'pendiente'))
            ->exists();

        if ($hasActiveAppointment) {
            return back()->with('error', 'Ya tienes una cita pendiente. Solo puedes tener una cita activa a la vez.');
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
        if ($request->filled('estado')) {
            $query->whereHas('appointmentState', function($q) use ($request) {
                $q->where('name', $request->estado);
            });
        }

        // Filtrar por nutricionista si se proporciona
        if ($request->filled('nutricionista')) {
            $query->where('nutricionista_id', $request->nutricionista);
        }

        // Filtrar por rango de fechas
        if ($request->filled('fecha_desde')) {
            $query->whereDate('start_time', '>=', $request->fecha_desde);
        }

        if ($request->filled('fecha_hasta')) {
            $query->whereDate('start_time', '<=', $request->fecha_hasta);
        }

        // Ordenar por fecha más reciente
        $appointments = $query->orderBy('start_time', 'desc')->paginate(10);

        // Obtener lista de nutricionistas con los que el paciente ha tenido citas
        $nutricionistas = User::whereHas('appointmentsAsNutricionista', function($q) use ($paciente) {
            $q->where('paciente_id', $paciente->id);
        })->orderBy('name')->get();

        return view('paciente.appointments.index', compact('appointments', 'nutricionistas'));
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
