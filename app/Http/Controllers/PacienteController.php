<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Appointment;
use App\Models\AppointmentState;
use App\Models\NutricionistaSchedule;
use App\Notifications\AppointmentCancelledByPatient;
use App\Notifications\AppointmentCreatedNotification;
use App\Notifications\AppointmentConfirmedForPatient;
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

        // Lista de nutricionistas disponibles (solo habilitados clínicamente)
        $nutricionistas = User::whereHas('role', fn($q) => $q->where('name', 'nutricionista'))
            ->with('userState')
            ->withCount('schedules')
            ->get()
            ->filter(fn($n) => $n->estaHabilitadoClinicamente());

        return view('paciente.dashboard', compact('stats', 'nextAppointment', 'recentAppointments', 'nutricionistas'));
    }

    /**
     * Mostrar lista de nutricionistas para agendar
     */
    public function showBooking()
    {
        // Lista de nutricionistas habilitados clínicamente
        $nutricionistas = User::whereHas('role', fn($q) => $q->where('name', 'nutricionista'))
            ->with('userState')
            ->withCount('schedules')
            ->get()
            ->filter(fn($n) => $n->estaHabilitadoClinicamente());

        return view('paciente.booking.index', compact('nutricionistas'));
    }

    /**
     * Mostrar horarios disponibles de un nutricionista
     */
    public function selectSchedule(User $nutricionista)
    {
        $paciente = auth()->user();

        // Verificar que el nutricionista esté habilitado clínicamente
        if (!$nutricionista->estaHabilitadoClinicamente()) {
            return redirect()->route('paciente.booking.index')
                ->with('error', 'Este nutricionista no está disponible en este momento.');
        }

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

        // Verificar que el nutricionista esté habilitado clínicamente
        if (!$nutricionista->estaHabilitadoClinicamente()) {
            return redirect()->route('paciente.booking.index')
                ->with('error', 'Este nutricionista no está disponible en este momento.');
        }

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

        $appointment = Appointment::create([
            'paciente_id' => $paciente->id,
            'nutricionista_id' => $nutricionista->id,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'appointment_state_id' => $pendienteState->id,
            'reason' => $request->reason,
            'appointment_type' => $request->appointment_type,
            'price' => $nutricionista->nutricionistaSettings?->consultation_price ?? 30.00,
        ]);

        // Notificar al nutricionista sobre la nueva cita (en cola, inmediato)
        $nutricionista->notify(new AppointmentCreatedNotification($appointment));

        // Notificar al paciente con 20 segundos de retraso (solo para desarrollo/Mailtrap)
        $paciente->notify((new AppointmentConfirmedForPatient($appointment))->delay(now()->addSeconds(20)));

        return redirect()->route('paciente.dashboard')
            ->with('booking_success', '¡Cita agendada exitosamente!');
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

        // Obtener nutricionista y paciente
        $nutricionista = $appointment->nutricionista;
        $paciente = $appointment->paciente;

        // Notificar al paciente que canceló (en cola, inmediato)
        $paciente->notify(new AppointmentCancelledByPatient($appointment, 'paciente'));

        // Notificar al nutricionista con 20 segundos de retraso (solo para desarrollo/Mailtrap)
        $nutricionista->notify((new AppointmentCancelledByPatient($appointment, 'nutricionista'))->delay(now()->addSeconds(20)));

        return back()->with('cancellation_success', 'Cita cancelada exitosamente.');
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
            $query->whereHas('appointmentState', function ($q) use ($request) {
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
        $nutricionistas = User::whereHas('appointmentsAsNutricionista', function ($q) use ($paciente) {
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

    /**
     * Muestra el perfil del paciente
     */
    public function profile()
    {
        return view('paciente.profile');
    }

    /**
     * Ver historial clínico del paciente con gráficas de progreso
     */
    public function history()
    {
        $paciente = auth()->user();
        $paciente->load('personalData');

        // Obtener todas las atenciones del paciente
        $attentions = \App\Models\Attention::where('paciente_id', $paciente->id)
            ->with(['attentionData', 'appointment', 'nutricionista'])
            ->whereHas('attentionData')
            ->orderBy('created_at', 'asc')
            ->get();

        // Preparar datos para las gráficas
        $chartData = $this->prepareChartData($attentions);

        // Calcular estadísticas de progreso
        $progressStats = $this->calculateProgressStats($attentions);

        return view('paciente.history', compact('paciente', 'attentions', 'chartData', 'progressStats'));
    }

    /**
     * Preparar datos para las gráficas de Chart.js
     */
    private function prepareChartData($attentions)
    {
        $labels = [];
        $weights = [];
        $bmis = [];
        $bodyFats = [];
        $waists = [];
        $hips = [];
        $necks = [];
        $wrists = [];
        $armContracted = [];
        $armRelaxed = [];
        $thighs = [];
        $calves = [];
        $tmbs = [];
        $tdees = [];
        $whrs = [];
        $whts = [];
        $targetCalories = [];

        foreach ($attentions as $attention) {
            $data = $attention->attentionData;
            if (!$data) continue;

            $date = $attention->created_at->format('d/m/Y');
            $labels[] = $date;
            $weights[] = (float) $data->weight;
            $bmis[] = (float) $data->bmi;
            $bodyFats[] = $data->body_fat ? (float) $data->body_fat : null;
            $waists[] = $data->waist ? (float) $data->waist : null;
            $hips[] = $data->hip ? (float) $data->hip : null;
            $necks[] = $data->neck ? (float) $data->neck : null;
            $wrists[] = $data->wrist ? (float) $data->wrist : null;
            $armContracted[] = $data->arm_contracted ? (float) $data->arm_contracted : null;
            $armRelaxed[] = $data->arm_relaxed ? (float) $data->arm_relaxed : null;
            $thighs[] = $data->thigh ? (float) $data->thigh : null;
            $calves[] = $data->calf ? (float) $data->calf : null;
            $tmbs[] = $data->tmb ? (float) $data->tmb : null;
            $tdees[] = $data->tdee ? (float) $data->tdee : null;
            $whrs[] = $data->whr ? (float) $data->whr : null;
            $whts[] = $data->wht ? (float) $data->wht : null;
            $targetCalories[] = $data->target_calories ? (float) $data->target_calories : null;
        }

        return [
            'labels' => $labels,
            'weights' => $weights,
            'bmis' => $bmis,
            'bodyFats' => $bodyFats,
            'waists' => $waists,
            'hips' => $hips,
            'necks' => $necks,
            'wrists' => $wrists,
            'armContracted' => $armContracted,
            'armRelaxed' => $armRelaxed,
            'thighs' => $thighs,
            'calves' => $calves,
            'tmbs' => $tmbs,
            'tdees' => $tdees,
            'whrs' => $whrs,
            'whts' => $whts,
            'targetCalories' => $targetCalories,
        ];
    }

    /**
     * Calcular estadísticas de progreso
     */
    private function calculateProgressStats($attentions)
    {
        if ($attentions->isEmpty()) {
            return null;
        }

        $first = $attentions->first()->attentionData;
        $last = $attentions->last()->attentionData;

        if (!$first || !$last) {
            return null;
        }

        return [
            'total_attentions' => $attentions->count(),
            'first_date' => $attentions->first()->created_at->format('d/m/Y'),
            'last_date' => $attentions->last()->created_at->format('d/m/Y'),
            'weight' => [
                'initial' => (float) $first->weight,
                'current' => (float) $last->weight,
                'change' => round((float) $last->weight - (float) $first->weight, 2),
                'percentage' => $first->weight > 0 ? round((((float) $last->weight - (float) $first->weight) / (float) $first->weight) * 100, 1) : 0,
            ],
            'bmi' => [
                'initial' => (float) $first->bmi,
                'current' => (float) $last->bmi,
                'change' => round((float) $last->bmi - (float) $first->bmi, 2),
            ],
            'body_fat' => [
                'initial' => $first->body_fat ? (float) $first->body_fat : null,
                'current' => $last->body_fat ? (float) $last->body_fat : null,
                'change' => ($first->body_fat && $last->body_fat) ? round((float) $last->body_fat - (float) $first->body_fat, 2) : null,
            ],
            'waist' => [
                'initial' => $first->waist ? (float) $first->waist : null,
                'current' => $last->waist ? (float) $last->waist : null,
                'change' => ($first->waist && $last->waist) ? round((float) $last->waist - (float) $first->waist, 2) : null,
            ],
            'hip' => [
                'initial' => $first->hip ? (float) $first->hip : null,
                'current' => $last->hip ? (float) $last->hip : null,
                'change' => ($first->hip && $last->hip) ? round((float) $last->hip - (float) $first->hip, 2) : null,
            ],
            'neck' => [
                'initial' => $first->neck ? (float) $first->neck : null,
                'current' => $last->neck ? (float) $last->neck : null,
                'change' => ($first->neck && $last->neck) ? round((float) $last->neck - (float) $first->neck, 2) : null,
            ],
            'arm_contracted' => [
                'initial' => $first->arm_contracted ? (float) $first->arm_contracted : null,
                'current' => $last->arm_contracted ? (float) $last->arm_contracted : null,
                'change' => ($first->arm_contracted && $last->arm_contracted) ? round((float) $last->arm_contracted - (float) $first->arm_contracted, 2) : null,
            ],
            'thigh' => [
                'initial' => $first->thigh ? (float) $first->thigh : null,
                'current' => $last->thigh ? (float) $last->thigh : null,
                'change' => ($first->thigh && $last->thigh) ? round((float) $last->thigh - (float) $first->thigh, 2) : null,
            ],
            'tmb' => [
                'initial' => $first->tmb ? (float) $first->tmb : null,
                'current' => $last->tmb ? (float) $last->tmb : null,
                'change' => ($first->tmb && $last->tmb) ? round((float) $last->tmb - (float) $first->tmb, 0) : null,
            ],
            'tdee' => [
                'initial' => $first->tdee ? (float) $first->tdee : null,
                'current' => $last->tdee ? (float) $last->tdee : null,
                'change' => ($first->tdee && $last->tdee) ? round((float) $last->tdee - (float) $first->tdee, 0) : null,
            ],
        ];
    }
}
