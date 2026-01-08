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
            'weight' => 'required|numeric|min:20|max:300',
            'height' => 'required|numeric|min:100|max:250',
            'bmi' => 'required|numeric|min:10|max:60',
            'waist' => 'nullable|numeric|min:30|max:200',
            'hip' => 'nullable|numeric|min:30|max:200',
            'body_fat' => 'nullable|numeric|min:0|max:100',
            'blood_pressure' => 'nullable|string|max:20',
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
            'bmi.required' => 'El IMC es obligatorio.',
            'bmi.numeric' => 'El IMC debe ser un número.',
            'waist.numeric' => 'La medida de cintura debe ser un número.',
            'hip.numeric' => 'La medida de cadera debe ser un número.',
            'body_fat.numeric' => 'El porcentaje de grasa corporal debe ser un número.',
            'body_fat.min' => 'El porcentaje de grasa corporal no puede ser negativo.',
            'body_fat.max' => 'El porcentaje de grasa corporal no puede superar 100%.',
            'blood_pressure.max' => 'La presión arterial no puede superar 20 caracteres.',
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
                'weight' => $validated['weight'],
                'height' => $validated['height'],
                'bmi' => $validated['bmi'],
                'waist' => $validated['waist'],
                'hip' => $validated['hip'],
                'body_fat' => $validated['body_fat'],
                'blood_pressure' => $validated['blood_pressure'],
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
