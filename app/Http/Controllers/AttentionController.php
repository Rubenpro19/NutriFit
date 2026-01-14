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
            // Nivel de actividad y objetivo
            'activity_level' => 'required|in:sedentary,light,moderate,active,very_active',
            'nutrition_goal' => 'required|in:deficit,maintenance,surplus',
            // Valores calculados (obligatorios)
            'bmi' => 'required|numeric|min:10|max:60',
            'body_fat' => 'nullable|numeric|min:0|max:100',
            'tmb' => 'nullable|numeric|min:500|max:5000',
            'tdee' => 'nullable|numeric|min:500|max:10000',
            'whr' => 'nullable|numeric|min:0.5|max:1.5',
            'wht' => 'nullable|numeric|min:0.3|max:1.0',
            'frame_index' => 'nullable|numeric|min:5|max:20',
            // Macronutrientes
            'target_calories' => 'nullable|numeric|min:500|max:10000',
            'protein_grams' => 'nullable|numeric|min:0|max:1000',
            'fat_grams' => 'nullable|numeric|min:0|max:1000',
            'carbs_grams' => 'nullable|numeric|min:0|max:2000',
            // Porcentajes de macronutrientes
            'protein_percentage' => 'nullable|numeric|min:0|max:100',
            'fat_percentage' => 'nullable|numeric|min:0|max:100',
            'carbs_percentage' => 'nullable|numeric|min:0|max:100',
            // Equivalentes
            'eq_cereales' => 'nullable|numeric|min:0|max:50',
            'eq_verduras' => 'nullable|numeric|min:0|max:50',
            'eq_frutas' => 'nullable|numeric|min:0|max:50',
            'eq_lacteo' => 'nullable|numeric|min:0|max:50',
            'eq_animal' => 'nullable|numeric|min:0|max:50',
            'eq_aceites' => 'nullable|numeric|min:0|max:50',
            'eq_grasas_prot' => 'nullable|numeric|min:0|max:50',
            'eq_leguminosas' => 'nullable|numeric|min:0|max:50',
            'total_calories_equivalents' => 'nullable|numeric|min:0|max:10000',
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
            'nutrition_goal.required' => 'El objetivo nutricional es obligatorio.',
            'nutrition_goal.in' => 'El objetivo nutricional seleccionado no es válido.',
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
                // Nivel de actividad y objetivo
                'activity_level' => $validated['activity_level'],
                'nutrition_goal' => $validated['nutrition_goal'] ?? 'maintenance',
                // Valores calculados - Índices corporales
                'bmi' => $validated['bmi'],
                'body_fat' => $validated['body_fat'] ?? null,
                'tmb' => $validated['tmb'] ?? null,
                'tdee' => $validated['tdee'] ?? null,
                'whr' => $validated['whr'] ?? null,
                'wht' => $validated['wht'] ?? null,
                'frame_index' => $validated['frame_index'] ?? null,
                // Macronutrientes
                'target_calories' => $validated['target_calories'] ?? null,
                'protein_grams' => $validated['protein_grams'] ?? null,
                'fat_grams' => $validated['fat_grams'] ?? null,
                'carbs_grams' => $validated['carbs_grams'] ?? null,
                // Porcentajes de macronutrientes
                'protein_percentage' => $validated['protein_percentage'] ?? null,
                'fat_percentage' => $validated['fat_percentage'] ?? null,
                'carbs_percentage' => $validated['carbs_percentage'] ?? null,
                // Equivalentes
                'eq_cereales' => $validated['eq_cereales'] ?? 0,
                'eq_verduras' => $validated['eq_verduras'] ?? 0,
                'eq_frutas' => $validated['eq_frutas'] ?? 0,
                'eq_lacteo' => $validated['eq_lacteo'] ?? 0,
                'eq_animal' => $validated['eq_animal'] ?? 0,
                'eq_aceites' => $validated['eq_aceites'] ?? 0,
                'eq_grasas_prot' => $validated['eq_grasas_prot'] ?? 0,
                'eq_leguminosas' => $validated['eq_leguminosas'] ?? 0,
                'total_calories_equivalents' => $validated['total_calories_equivalents'] ?? 0,
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

    /**
     * Muestra el formulario para editar una atención existente
     */
    public function edit(Appointment $appointment)
    {
        // Verificar que la cita pertenece al nutricionista autenticado
        if ($appointment->nutricionista_id !== auth()->id()) {
            abort(403, 'No tienes permiso para editar esta atención.');
        }

        // Verificar que la cita tiene una atención
        if (!$appointment->attention) {
            return redirect()
                ->route('nutricionista.appointments.show', $appointment)
                ->with('error', 'Esta cita no tiene una atención registrada.');
        }

        // Cargar relaciones necesarias
        $appointment->load(['paciente.personalData', 'appointmentState', 'attention.attentionData']);

        return view('nutricionista.attentions.edit', compact('appointment'));
    }

    /**
     * Actualiza una atención existente
     */
    public function update(Request $request, Appointment $appointment)
    {
        // Verificar que la cita pertenece al nutricionista autenticado
        if ($appointment->nutricionista_id !== auth()->id()) {
            abort(403, 'No tienes permiso para editar esta atención.');
        }

        // Verificar que la cita tiene una atención
        if (!$appointment->attention) {
            return redirect()
                ->route('nutricionista.appointments.show', $appointment)
                ->with('error', 'Esta cita no tiene una atención registrada.');
        }

        // Validación de datos (misma que en store)
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
            // Nivel de actividad y objetivo
            'activity_level' => 'required|in:sedentary,light,moderate,active,very_active',
            'nutrition_goal' => 'required|in:deficit,maintenance,surplus',
            // Valores calculados (obligatorios)
            'bmi' => 'required|numeric|min:10|max:60',
            'body_fat' => 'nullable|numeric|min:0|max:100',
            'tmb' => 'nullable|numeric|min:500|max:5000',
            'tdee' => 'nullable|numeric|min:500|max:10000',
            'whr' => 'nullable|numeric|min:0.5|max:1.5',
            'wht' => 'nullable|numeric|min:0.3|max:1.0',
            'frame_index' => 'nullable|numeric|min:5|max:20',
            // Macronutrientes
            'target_calories' => 'nullable|numeric|min:500|max:10000',
            'protein_grams' => 'nullable|numeric|min:0|max:1000',
            'fat_grams' => 'nullable|numeric|min:0|max:1000',
            'carbs_grams' => 'nullable|numeric|min:0|max:2000',
            // Porcentajes de macronutrientes
            'protein_percentage' => 'nullable|numeric|min:0|max:100',
            'fat_percentage' => 'nullable|numeric|min:0|max:100',
            'carbs_percentage' => 'nullable|numeric|min:0|max:100',
            // Equivalentes
            'eq_cereales' => 'nullable|numeric|min:0|max:50',
            'eq_verduras' => 'nullable|numeric|min:0|max:50',
            'eq_frutas' => 'nullable|numeric|min:0|max:50',
            'eq_lacteo' => 'nullable|numeric|min:0|max:50',
            'eq_animal' => 'nullable|numeric|min:0|max:50',
            'eq_aceites' => 'nullable|numeric|min:0|max:50',
            'eq_grasas_prot' => 'nullable|numeric|min:0|max:50',
            'eq_leguminosas' => 'nullable|numeric|min:0|max:50',
            'total_calories_equivalents' => 'nullable|numeric|min:0|max:10000',
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
            'nutrition_goal.required' => 'El objetivo nutricional es obligatorio.',
            'nutrition_goal.in' => 'El objetivo nutricional seleccionado no es válido.',
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

            // Actualizar el registro de atención
            $appointment->attention->update([
                'diagnosis' => $validated['diagnosis'],
                'recommendations' => $validated['recommendations'],
            ]);

            // Actualizar los datos antropométricos
            $appointment->attention->attentionData->update([
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
                // Nivel de actividad y objetivo
                'activity_level' => $validated['activity_level'],
                'nutrition_goal' => $validated['nutrition_goal'] ?? 'maintenance',
                // Valores calculados - Índices corporales
                'bmi' => $validated['bmi'],
                'body_fat' => $validated['body_fat'] ?? null,
                'tmb' => $validated['tmb'] ?? null,
                'tdee' => $validated['tdee'] ?? null,
                'whr' => $validated['whr'] ?? null,
                'wht' => $validated['wht'] ?? null,
                'frame_index' => $validated['frame_index'] ?? null,
                // Macronutrientes
                'target_calories' => $validated['target_calories'] ?? null,
                'protein_grams' => $validated['protein_grams'] ?? null,
                'fat_grams' => $validated['fat_grams'] ?? null,
                'carbs_grams' => $validated['carbs_grams'] ?? null,
                // Porcentajes de macronutrientes
                'protein_percentage' => $validated['protein_percentage'] ?? null,
                'fat_percentage' => $validated['fat_percentage'] ?? null,
                'carbs_percentage' => $validated['carbs_percentage'] ?? null,
                // Equivalentes
                'eq_cereales' => $validated['eq_cereales'] ?? 0,
                'eq_verduras' => $validated['eq_verduras'] ?? 0,
                'eq_frutas' => $validated['eq_frutas'] ?? 0,
                'eq_lacteo' => $validated['eq_lacteo'] ?? 0,
                'eq_animal' => $validated['eq_animal'] ?? 0,
                'eq_aceites' => $validated['eq_aceites'] ?? 0,
                'eq_grasas_prot' => $validated['eq_grasas_prot'] ?? 0,
                'eq_leguminosas' => $validated['eq_leguminosas'] ?? 0,
                'total_calories_equivalents' => $validated['total_calories_equivalents'] ?? 0,
            ]);

            DB::commit();

            return redirect()
                ->route('nutricionista.appointments.show', $appointment)
                ->with('success', 'La atención ha sido actualizada exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Ocurrió un error al actualizar la atención: ' . $e->getMessage());
        }
    }
}
