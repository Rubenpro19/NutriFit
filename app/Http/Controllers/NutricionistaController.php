<?php

namespace App\Http\Controllers;

use App\Notifications\AppointmentCancelledByNutricionista;
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

        // Marcar citas vencidas
        Appointment::markExpiredAppointments();

        // Estadísticas del nutricionista - solo citas pendientes
        $stats = [
            // Citas de hoy (solo pendientes)
            'appointments_today' => Appointment::where('nutricionista_id', $nutricionista->id)
                ->whereDate('start_time', $today)
                ->whereHas('appointmentState', fn($q) => $q->where('name', 'pendiente'))
                ->count(),
            
            // Citas de esta semana (solo pendientes)
            'appointments_this_week' => Appointment::where('nutricionista_id', $nutricionista->id)
                ->whereBetween('start_time', [$today->copy()->startOfWeek(), $today->copy()->endOfWeek()])
                ->whereHas('appointmentState', fn($q) => $q->where('name', 'pendiente'))
                ->count(),
        ];

        // Próxima cita (la más cercana desde ahora y pendiente)
        $nextAppointment = Appointment::where('nutricionista_id', $nutricionista->id)
            ->whereHas('appointmentState', fn($q) => $q->where('name', 'pendiente'))
            ->where('start_time', '>=', now())
            ->with(['paciente', 'appointmentState'])
            ->orderBy('start_time', 'asc')
            ->first();

        // Agenda de hoy (solo citas pendientes)
        $todayAppointments = Appointment::where('nutricionista_id', $nutricionista->id)
            ->whereDate('start_time', $today)
            ->whereHas('appointmentState', fn($q) => $q->where('name', 'pendiente'))
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
     * Cancelar una cita
     */
    public function cancelAppointment(Appointment $appointment)
    {
        // Verificar que la cita pertenece al nutricionista autenticado
        if ($appointment->nutricionista_id !== auth()->id()) {
            abort(403, 'No tienes permiso para cancelar esta cita.');
        }

        // Verificar que la cita esté en estado pendiente
        if ($appointment->appointmentState->name !== 'pendiente') {
            return redirect()
                ->route('nutricionista.appointments.show', $appointment)
                ->with('error', 'Solo se pueden cancelar citas en estado pendiente.');
        }

        // Cambiar el estado a cancelada
        $canceledState = \App\Models\AppointmentState::where('name', 'cancelada')->first();
        $appointment->update([
            'appointment_state_id' => $canceledState->id,
        ]);

        // Obtener nutricionista y paciente
        $nutricionista = $appointment->nutricionista;
        $paciente = $appointment->paciente;

        // Notificar al nutricionista que canceló (en cola, inmediato)
        $nutricionista->notify(new AppointmentCancelledByNutricionista($appointment, 'nutricionista'));
        
        // Notificar al paciente con 20 segundos de retraso (solo para desarrollo/Mailtrap)
        $paciente->notify((new AppointmentCancelledByNutricionista($appointment, 'paciente'))->delay(now()->addSeconds(20)));

        return redirect()
            ->route('nutricionista.appointments.show', $appointment)
            ->with('success', 'La cita ha sido cancelada exitosamente.');
    }

    /**
     * Mostrar formulario de reagendamiento
     */
    public function rescheduleForm(Appointment $appointment)
    {
        // Verificar que la cita pertenece al nutricionista autenticado
        if ($appointment->nutricionista_id !== auth()->id()) {
            abort(403, 'No tienes permiso para reagendar esta cita.');
        }

        // Verificar que la cita esté en estado pendiente
        if ($appointment->appointmentState->name !== 'pendiente') {
            return redirect()
                ->route('nutricionista.appointments.show', $appointment)
                ->with('error', 'Solo se pueden reagendar citas en estado pendiente.');
        }

        $nutricionista = auth()->user();

        // Obtener horarios del nutricionista
        $schedules = NutricionistaSchedule::where('nutricionista_id', $nutricionista->id)
            ->where('is_active', true)
            ->orderBy('day_of_week')
            ->get()
            ->groupBy('day_of_week');

        // Generar 4 semanas desde el inicio de la semana actual (lunes)
        $weeks = [];
        $startDate = now()->startOfWeek(); // Empieza el lunes
        
        for ($weekIndex = 0; $weekIndex < 4; $weekIndex++) {
            $weekDays = [];
            
            for ($dayIndex = 0; $dayIndex < 7; $dayIndex++) {
                $date = $startDate->copy()->addDays(($weekIndex * 7) + $dayIndex);
                $dayOfWeek = $date->dayOfWeek;
                
                // Saltar días pasados completamente
                if ($date->isPast() && !$date->isToday()) {
                    continue;
                }
                
                $dayData = [
                    'date' => $date,
                    'date_formatted' => $date->locale('es')->isoFormat('dddd, D [de] MMMM'),
                    'day_name' => $date->locale('es')->isoFormat('dddd'),
                    'day_number' => $date->format('d'),
                    'is_today' => $date->isToday(),
                    'slots' => []
                ];
                
                // Si existe horario configurado para este día
                if (isset($schedules[$dayOfWeek])) {
                    foreach ($schedules[$dayOfWeek] as $schedule) {
                        $timeSlots = $schedule->generateTimeSlots();
                        
                        foreach ($timeSlots as $slot) {
                            $slotDateTime = \Carbon\Carbon::parse($date->format('Y-m-d') . ' ' . $slot['start']);
                            
                            // Solo incluir si es futuro y está disponible (excluyendo la cita actual)
                            if ($slotDateTime->isFuture() && $schedule->isTimeSlotAvailable($date->format('Y-m-d'), $slot['start'], $appointment->id)) {
                                $dayData['slots'][] = [
                                    'time' => $slot['start'],
                                    'time_formatted' => \Carbon\Carbon::parse($slot['start'])->format('h:i A'),
                                    'datetime' => $slotDateTime->toIso8601String(),
                                ];
                            }
                        }
                    }
                }
                
                // Añadir el día solo si tiene slots disponibles
                if (!empty($dayData['slots'])) {
                    $weekDays[] = $dayData;
                }
            }
            
            // Añadir la semana si tiene al menos un día con slots
            if (!empty($weekDays)) {
                $weeks[] = [
                    'week_number' => $weekIndex + 1,
                    'start_date' => $weekDays[0]['date']->format('Y-m-d'),
                    'start_date_formatted' => $weekDays[0]['date']->locale('es')->isoFormat('D MMM'),
                    'days' => $weekDays
                ];
            }
        }

        $appointment->load(['paciente', 'appointmentState']);

        return view('nutricionista.appointments.reschedule', compact('appointment', 'weeks'));
    }

    /**
     * Procesar el reagendamiento de la cita
     */
    public function rescheduleAppointment(Request $request, Appointment $appointment)
    {
        // Verificar que la cita pertenece al nutricionista autenticado
        if ($appointment->nutricionista_id !== auth()->id()) {
            abort(403, 'No tienes permiso para reagendar esta cita.');
        }

        // Verificar que la cita esté en estado pendiente
        if ($appointment->appointmentState->name !== 'pendiente') {
            return redirect()
                ->route('nutricionista.appointments.show', $appointment)
                ->with('error', 'Solo se pueden reagendar citas en estado pendiente.');
        }

        $validated = $request->validate([
            'date' => 'required|date|after_or_equal:today',
            'time' => 'required',
            'reschedule_reason' => 'nullable|string|max:500',
        ]);

        // Crear la nueva fecha/hora de inicio y fin
        $newStartTime = \Carbon\Carbon::parse($validated['date'] . ' ' . $validated['time']);
        $newEndTime = $newStartTime->copy()->addMinutes(45);

        // Verificar que el nuevo horario no esté ocupado
        $nutricionista = auth()->user();
        $conflictingAppointment = Appointment::where('nutricionista_id', $nutricionista->id)
            ->where('id', '!=', $appointment->id) // Excluir la cita actual
            ->whereHas('appointmentState', fn($q) => $q->where('name', 'pendiente'))
            ->where(function($query) use ($newStartTime, $newEndTime) {
                $query->whereBetween('start_time', [$newStartTime, $newEndTime])
                      ->orWhereBetween('end_time', [$newStartTime, $newEndTime])
                      ->orWhere(function($q) use ($newStartTime, $newEndTime) {
                          $q->where('start_time', '<=', $newStartTime)
                            ->where('end_time', '>=', $newEndTime);
                      });
            })
            ->first();

        if ($conflictingAppointment) {
            return redirect()
                ->back()
                ->with('error', 'El horario seleccionado ya no está disponible. Por favor, selecciona otro.')
                ->withInput();
        }

        // Guardar información antigua para la notificación
        $oldStartTime = $appointment->start_time;

        // Actualizar la cita
        $appointment->update([
            'start_time' => $newStartTime,
            'end_time' => $newEndTime,
        ]);

        // Notificar al paciente del reagendamiento
        $paciente = $appointment->paciente;
        $paciente->notify(new \App\Notifications\AppointmentRescheduledNotification(
            $appointment,
            $oldStartTime,
            $validated['reschedule_reason'] ?? null
        ));

        return redirect()
            ->route('nutricionista.appointments.show', $appointment)
            ->with('success', 'La cita ha sido reagendada exitosamente. El paciente ha sido notificado.');
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
     * Ver lista de pacientes del nutricionista
     */
    public function patients()
    {
        return view('nutricionista.patients.index');
    }

    /**
     * Mostrar formulario para asignar cita a un paciente
     */
    public function createAppointment()
    {
        $pacientes = User::whereHas('role', fn($q) => $q->where('name', 'paciente'))
            ->with(['personalData'])
            ->orderBy('name')
            ->get();

        return view('nutricionista.appointments.create', compact('pacientes'));
    }

    /**
     * Obtener horarios disponibles para un paciente específico
     */
    public function getAvailableSchedules(User $paciente)
    {
        $nutricionista = auth()->user();
        
        // Permitir obtener horarios para cualquier paciente con rol 'paciente'.
        // Solo rechazamos si el usuario no es paciente.
        if (!$paciente->role || $paciente->role->name !== 'paciente') {
            return response()->json(['error' => 'Paciente no válido'], 403);
        }

        // Verificar que el paciente no tenga citas pendientes con NINGÚN nutricionista
        $hasPendingAppointment = $paciente->appointmentsAsPaciente()
            ->whereHas('appointmentState', fn($q) => $q->where('name', 'pendiente'))
            ->where('start_time', '>=', now())
            ->exists();
            
        if ($hasPendingAppointment) {
            // Obtener información de la cita pendiente
            $pendingAppointment = $paciente->appointmentsAsPaciente()
                ->with(['nutricionista', 'appointmentState'])
                ->whereHas('appointmentState', fn($q) => $q->where('name', 'pendiente'))
                ->where('start_time', '>=', now())
                ->first();
            
            $nutricionistaName = $pendingAppointment->nutricionista->name ?? 'otro nutricionista';
            $isOwnAppointment = $pendingAppointment->nutricionista_id === $nutricionista->id;
            
            return response()->json([
                'error' => $isOwnAppointment 
                    ? "El paciente ya tiene una cita pendiente contigo"
                    : "El paciente ya tiene una cita pendiente con {$nutricionistaName}",
                'isOwnAppointment' => $isOwnAppointment,
                'appointment' => $isOwnAppointment ? [
                    'id' => $pendingAppointment->id,
                    'start_time' => $pendingAppointment->start_time->format('Y-m-d H:i'),
                    'start_time_formatted' => $pendingAppointment->start_time->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY [a las] h:mm A'),
                    'appointment_type' => $pendingAppointment->appointment_type,
                    'reason' => $pendingAppointment->reason,
                    'price' => $pendingAppointment->price,
                ] : null
            ], 400);
        }

        // Obtener horarios del nutricionista
        $schedules = NutricionistaSchedule::where('nutricionista_id', $nutricionista->id)
            ->where('is_active', true)
            ->orderBy('day_of_week')
            ->get()
            ->groupBy('day_of_week');

        // Generar 4 semanas desde el inicio de la semana actual (lunes)
        $weeks = [];
        $startDate = now()->startOfWeek(); // Empieza el lunes
        
        for ($weekIndex = 0; $weekIndex < 4; $weekIndex++) {
            $weekDays = [];
            
            for ($dayIndex = 0; $dayIndex < 7; $dayIndex++) {
                $date = $startDate->copy()->addDays(($weekIndex * 7) + $dayIndex);
                $dateStr = $date->format('Y-m-d');
                $dayOfWeek = $date->dayOfWeek;
                
                // Saltar días pasados completamente
                if ($date->isPast() && !$date->isToday()) {
                    continue;
                }
                
                $dayData = [
                    'date' => $dateStr,
                    'date_formatted' => $date->locale('es')->isoFormat('dddd, D [de] MMMM'),
                    'day_name' => $date->locale('es')->isoFormat('dddd'),
                    'day_number' => $date->format('d'),
                    'is_today' => $date->isToday(),
                    'is_past' => false, // Ya filtramos los pasados arriba
                    'slots' => []
                ];
                
                // Si existe horario configurado para este día
                if (isset($schedules[$dayOfWeek])) {
                    foreach ($schedules[$dayOfWeek] as $schedule) {
                        $timeSlots = $schedule->generateTimeSlots();
                        
                        foreach ($timeSlots as $slot) {
                            $slotDateTime = \Carbon\Carbon::parse($dateStr . ' ' . $slot['start']);
                            
                            // Solo incluir si es futuro y está disponible
                            if ($slotDateTime->isFuture() && $schedule->isTimeSlotAvailable($dateStr, $slot['start'])) {
                                $dayData['slots'][] = [
                                    'time' => $slot['start'],
                                    'time_formatted' => \Carbon\Carbon::parse($slot['start'])->format('h:i A'),
                                    'datetime' => $slotDateTime->toIso8601String(),
                                ];
                            }
                        }
                    }
                }
                
                // Añadir el día a la semana (incluso si no tiene slots, para mostrar mensaje)
                $weekDays[] = $dayData;
            }
            
            // Añadir la semana si tiene al menos un día (ya filtramos los pasados)
            if (!empty($weekDays)) {
                $weeks[] = [
                    'week_number' => $weekIndex + 1,
                    'start_date' => $weekDays[0]['date'],
                    'start_date_formatted' => \Carbon\Carbon::parse($weekDays[0]['date'])->locale('es')->isoFormat('D MMM'),
                    'days' => $weekDays
                ];
            }
        }

        return response()->json([
            'paciente' => [
                'id' => $paciente->id,
                'name' => $paciente->name,
                'email' => $paciente->email,
            ],
            'weeks' => $weeks
        ]);
    }

    /**
     * Guardar la cita asignada
     */
    public function storeAppointment(Request $request)
    {
        $nutricionista = auth()->user();
        
        $validated = $request->validate([
            'paciente_id' => 'required|exists:users,id',
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required',
            'appointment_type' => 'required|in:primera_vez,seguimiento,control',
            'reason' => 'nullable|string|max:500',
            'notes' => 'nullable|string|max:1000',
            'price' => 'required|numeric|min:0',
        ]);

        // Obtener paciente y verificar que sea un usuario con rol 'paciente'
        $paciente = User::findOrFail($validated['paciente_id']);
        if (!$paciente->role || $paciente->role->name !== 'paciente') {
            return back()->with('error', 'El usuario seleccionado no es un paciente válido.');
        }

        // Verificar que el paciente no tenga citas pendientes con NINGÚN nutricionista
        $pendingAppointmentQuery = $paciente->appointmentsAsPaciente()
            ->whereHas('appointmentState', fn($q) => $q->where('name', 'pendiente'))
            ->where('start_time', '>=', now());
            
        if ($pendingAppointmentQuery->exists()) {
            $pendingAppointment = $pendingAppointmentQuery->with('nutricionista')->first();
            $nutricionistaName = $pendingAppointment->nutricionista->name ?? 'otro nutricionista';
            
            if ($pendingAppointment->nutricionista_id === $nutricionista->id) {
                return back()->with('error', 'El paciente ya tiene una cita pendiente contigo.');
            } else {
                return back()->with('error', "El paciente ya tiene una cita pendiente con {$nutricionistaName}. Un paciente solo puede tener una cita pendiente a la vez.");
            }
        }

        // Construir fecha y hora completa
        $startDateTime = \Carbon\Carbon::parse($validated['appointment_date'] . ' ' . $validated['appointment_time']);
        $endDateTime = $startDateTime->copy()->addMinutes(45);

        // Verificar disponibilidad del horario
        $dayOfWeek = $startDateTime->dayOfWeek;
        $timeStr = $startDateTime->format('H:i');

        $schedule = NutricionistaSchedule::where('nutricionista_id', $nutricionista->id)
            ->where('day_of_week', $dayOfWeek)
            ->where('is_active', true)
            ->first();

        if (!$schedule || !$schedule->isTimeSlotAvailable($validated['appointment_date'], $validated['appointment_time'])) {
            return back()->with('error', 'El horario seleccionado ya no está disponible.');
        }

        // Crear la cita
        $pendienteState = \App\Models\AppointmentState::where('name', 'pendiente')->first();

        $appointment = Appointment::create([
            'appointment_state_id' => $pendienteState->id,
            'paciente_id' => $validated['paciente_id'],
            'nutricionista_id' => $nutricionista->id,
            'start_time' => $startDateTime,
            'end_time' => $endDateTime,
            'appointment_type' => $validated['appointment_type'],
            'reason' => $validated['reason'],
            'notes' => $validated['notes'],
            'price' => $validated['price'],
        ]);

        return redirect()
            ->route('nutricionista.appointments.show', $appointment)
            ->with('success', 'Cita asignada exitosamente al paciente ' . $paciente->name);
    }

    /**
     * Ver detalle de un paciente
     */
    public function showPatient(User $patient)
    {
        $nutricionista = auth()->user();
        
        // Permitir ver cualquier paciente, siempre que sea un usuario con rol 'paciente'.
        if (!$patient->role || $patient->role->name !== 'paciente') {
            abort(403, 'No tienes acceso a este recurso.');
        }

        // Cargar información del paciente
        $patient->load(['userState', 'personalData']);

        // Obtener todas las citas del paciente con este nutricionista
        $appointments = $patient->appointmentsAsPaciente()
            ->where('nutricionista_id', $nutricionista->id)
            ->with(['appointmentState', 'attention.attentionData'])
            ->orderBy('start_time', 'desc')
            ->get();

        // Estadísticas del paciente
        $stats = [
            'total_appointments' => $appointments->count(),
            'completed' => $appointments->filter(fn($a) => $a->appointmentState->name === 'completada')->count(),
            'cancelled' => $appointments->filter(fn($a) => $a->appointmentState->name === 'cancelada')->count(),
            'pending' => $appointments->filter(fn($a) => $a->appointmentState->name === 'pendiente')->count(),
        ];

        // Próxima cita
        $nextAppointment = $appointments->filter(function($a) {
            return $a->appointmentState->name === 'pendiente' && $a->start_time >= now();
        })->first();

        // Última atención
        $lastAttention = $appointments->filter(fn($a) => $a->attention)->first()?->attention;

        return view('nutricionista.patients.show', compact('patient', 'appointments', 'stats', 'nextAppointment', 'lastAttention'));
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

    /**
     * Muestra el formulario de datos personales del paciente
     */
    public function patientData(User $patient)
    {
        $nutricionista = auth()->user();
        
        // Verificar que es un paciente
        if (!$patient->role || $patient->role->name !== 'paciente') {
            abort(404, 'Paciente no encontrado.');
        }

        return view('nutricionista.patients.data', compact('patient'));
    }
}