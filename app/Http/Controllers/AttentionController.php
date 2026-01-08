<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Attention;
use App\Models\AttentionData;
use App\Models\AppointmentState;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttentionController extends Controller
{
    /**
     * Muestra el formulario para crear una nueva atención
     */
    public function create(Appointment $appointment)
    {
        // Verificar que la cita pertenece al nutricionista autenticado
        if ($appointment->nutricionista_id !== auth()->id()) {
            abort(403, 'No tienes permiso para atender esta cita.');
        }

        // Verificar que la cita esté en estado pendiente
        if ($appointment->appointmentState->name !== 'pendiente') {
            return redirect()
                ->route('nutricionista.appointments.show', $appointment)
                ->with('error', 'Esta cita no puede ser atendida. Estado actual: ' . $appointment->appointmentState->name);
        }

        // Cargar relaciones necesarias
        $appointment->load(['paciente.personalData', 'appointmentState']);

        // Verificar si el paciente tiene datos personales
        if (!$appointment->paciente->personalData) {
            return redirect()
                ->route('nutricionista.patients.data', ['patient' => $appointment->paciente, 'appointment' => $appointment->id])
                ->with('warning', 'Antes de iniciar la atención, debes completar los datos personales del paciente.');
        }

        return view('nutricionista.attentions.create', compact('appointment'));
    }

    /**
     * Guarda la atención y los datos antropométricos
     */
    public function store(Request $request, Appointment $appointment)
    {
        // Verificar que la cita pertenece al nutricionista autenticado
        if ($appointment->nutricionista_id !== auth()->id()) {
            abort(403, 'No tienes permiso para atender esta cita.');
        }

        // Verificar que la cita esté en estado pendiente
        if ($appointment->appointmentState->name !== 'pendiente') {
            return redirect()
                ->route('nutricionista.appointments.show', $appointment)
                ->with('error', 'Esta cita no puede ser atendida. Estado actual: ' . $appointment->appointmentState->name);
        }

        // Validación de datos
        $validated = $request->validate([
            // Medidas básicas (obligatorias)
            'weight' => 'required|numeric|min:20|max:300',
            'height' => 'required|numeric|min:100|max:250',
            // Medidas corporales (obligatorias)
            'waist' => 'required|numeric|min:30|max:200',
            'hip' => 'required|numeric|min:30|max:200',
            'neck' => 'required|numeric|min:20|max:100',
            'wrist' => 'required|numeric|min:10|max:30',
            'arm_contracted' => 'required|numeric|min:15|max:100',
            'arm_relaxed' => 'required|numeric|min:15|max:100',
            'thigh' => 'required|numeric|min:30|max:150',
            'calf' => 'required|numeric|min:20|max:100',
            // Nivel de actividad
            'activity_level' => 'required|in:sedentary,light,moderate,active,very_active',
            // Valores calculados (obligatorios)
            'bmi' => 'required|numeric|min:10|max:60',
            'body_fat' => 'nullable|numeric|min:0|max:100',
            'tmb' => 'nullable|numeric|min:500|max:5000',
            'tdee' => 'nullable|numeric|min:500|max:10000',
            'whr' => 'nullable|numeric|min:0.5|max:1.5',
            'wht' => 'nullable|numeric|min:0.3|max:1.0',
            'frame_index' => 'nullable|numeric|min:5|max:20',
            // Notas clínicas (obligatorias)
            'diagnosis' => 'required|string|max:5000',
            'recommendations' => 'required|string|max:5000',
        ], [
            'weight.required' => 'El peso es obligatorio.',
            'weight.numeric' => 'El peso debe ser un número.',
            'weight.min' => 'El peso debe ser al menos 20 kg.',
            'weight.max' => 'El peso no puede superar 300 kg.',
            'height.required' => 'La altura es obligatoria.',
            'height.numeric' => 'La altura debe ser un número.',
            'height.min' => 'La altura debe ser al menos 100 cm.',
            'height.max' => 'La altura no puede superar 250 cm.',
            'waist.required' => 'La medida de cintura es obligatoria.',
            'waist.numeric' => 'La medida de cintura debe ser un número.',
            'hip.required' => 'La medida de cadera es obligatoria.',
            'hip.numeric' => 'La medida de cadera debe ser un número.',
            'neck.required' => 'La medida de cuello es obligatoria.',
            'neck.numeric' => 'La medida de cuello debe ser un número.',
            'wrist.required' => 'La medida de muñeca es obligatoria.',
            'wrist.numeric' => 'La medida de muñeca debe ser un número.',
            'arm_contracted.required' => 'La medida de brazo contraído es obligatoria.',
            'arm_relaxed.required' => 'La medida de brazo relajado es obligatoria.',
            'thigh.required' => 'La medida de pierna es obligatoria.',
            'calf.required' => 'La medida de pantorrilla es obligatoria.',
            'bmi.required' => 'El IMC es obligatorio.',
            'bmi.numeric' => 'El IMC debe ser un número.',
            'activity_level.required' => 'El nivel de actividad física es obligatorio.',
            'activity_level.in' => 'El nivel de actividad física seleccionado no es válido.',
            'body_fat.numeric' => 'El porcentaje de grasa corporal debe ser un número.',
            'body_fat.min' => 'El porcentaje de grasa corporal no puede ser negativo.',
            'body_fat.max' => 'El porcentaje de grasa corporal no puede superar 100%.',
            'diagnosis.required' => 'El diagnóstico es obligatorio.',
            'diagnosis.max' => 'El diagnóstico no puede superar 5000 caracteres.',
            'recommendations.required' => 'Las recomendaciones son obligatorias.',
            'recommendations.max' => 'Las recomendaciones no pueden superar 5000 caracteres.',
        ]);

        try {
            DB::beginTransaction();

            // Crear el registro de atención
            $attention = Attention::create([
                'appointment_id' => $appointment->id,
                'paciente_id' => $appointment->paciente_id,
                'nutricionista_id' => $appointment->nutricionista_id,
                'diagnosis' => $validated['diagnosis'],
                'recommendations' => $validated['recommendations'],
            ]);

            // Crear los datos antropométricos
            AttentionData::create([
                'attention_id' => $attention->id,
                // Medidas básicas
                'weight' => $validated['weight'],
                'height' => $validated['height'],
                // Medidas corporales
                'waist' => $validated['waist'] ?? null,
                'hip' => $validated['hip'] ?? null,
                'neck' => $validated['neck'] ?? null,
                'wrist' => $validated['wrist'] ?? null,
                'arm_contracted' => $validated['arm_contracted'] ?? null,
                'arm_relaxed' => $validated['arm_relaxed'] ?? null,
                'thigh' => $validated['thigh'] ?? null,
                'calf' => $validated['calf'] ?? null,
                // Nivel de actividad
                'activity_level' => $validated['activity_level'],
                // Valores calculados
                'bmi' => $validated['bmi'],
                'body_fat' => $validated['body_fat'] ?? null,
                'tmb' => $validated['tmb'] ?? null,
                'tdee' => $validated['tdee'] ?? null,
                'whr' => $validated['whr'] ?? null,
                'wht' => $validated['wht'] ?? null,
                'frame_index' => $validated['frame_index'] ?? null,
            ]);

            // Cambiar el estado de la cita a completada
            $completedState = AppointmentState::where('name', 'completada')->first();
            $appointment->update([
                'appointment_state_id' => $completedState->id,
            ]);

            DB::commit();

            return redirect()
                ->route('nutricionista.appointments.show', $appointment)
                ->with('success', 'La atención ha sido registrada exitosamente. La cita ahora está completada.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Ocurrió un error al guardar la atención: ' . $e->getMessage());
        }
    }
}
