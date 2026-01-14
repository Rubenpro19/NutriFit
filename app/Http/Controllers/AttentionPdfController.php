<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Attention;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AttentionPdfController extends Controller
{
    /**
     * Generar y descargar PDF de una atención
     */
    public function download(Appointment $appointment)
    {
        // Verificar que el usuario tenga permiso
        $user = auth()->user();
        
        if ($user->role->name === 'nutricionista' && $appointment->nutricionista_id !== $user->id) {
            abort(403, 'No tienes permiso para ver esta atención.');
        }
        
        if ($user->role->name === 'paciente' && $appointment->paciente_id !== $user->id) {
            abort(403, 'No tienes permiso para ver esta atención.');
        }

        // Cargar las relaciones necesarias
        $appointment->load([
            'paciente.personalData',
            'nutricionista.personalData',
            'attention.attentionData',
            'appointmentState'
        ]);

        // Verificar que existe la atención
        if (!$appointment->attention || !$appointment->attention->attentionData) {
            return back()->with('error', 'Esta cita no tiene una atención registrada.');
        }

        // Calcular el número de atención (cuántas atenciones ha tenido este paciente)
        $attentionNumber = Attention::where('paciente_id', $appointment->paciente_id)
            ->where('created_at', '<=', $appointment->attention->created_at)
            ->count();

        // Preparar datos para el PDF
        $data = [
            'appointment' => $appointment,
            'attention' => $appointment->attention,
            'attentionData' => $appointment->attention->attentionData,
            'paciente' => $appointment->paciente,
            'nutricionista' => $appointment->nutricionista,
            'attentionNumber' => $attentionNumber,
            'attentionDate' => Carbon::parse($appointment->attention->created_at)->format('d-m-Y'),
        ];

        // Generar el PDF
        $pdf = Pdf::loadView('pdf.attention', $data);
        
        // Configurar el PDF - A4 vertical
        $pdf->setPaper('a4', 'portrait');

        // Generar nombre del archivo
        // Formato: NombrePaciente_Atencion1_14-01-2026.pdf
        $pacienteName = str_replace(' ', '_', $appointment->paciente->name);
        $pacienteName = preg_replace('/[^A-Za-z0-9_]/', '', $pacienteName); // Limpiar caracteres especiales
        $fileName = "{$pacienteName}_Atencion{$attentionNumber}_{$data['attentionDate']}.pdf";

        // Descargar o ver en navegador
        return $pdf->download($fileName);
    }

    /**
     * Ver PDF en el navegador (sin descargar)
     */
    public function view(Appointment $appointment)
    {
        // Verificar que el usuario tenga permiso
        $user = auth()->user();
        
        if ($user->role->name === 'nutricionista' && $appointment->nutricionista_id !== $user->id) {
            abort(403, 'No tienes permiso para ver esta atención.');
        }
        
        if ($user->role->name === 'paciente' && $appointment->paciente_id !== $user->id) {
            abort(403, 'No tienes permiso para ver esta atención.');
        }

        // Cargar las relaciones necesarias
        $appointment->load([
            'paciente.personalData',
            'nutricionista.personalData',
            'attention.attentionData',
            'appointmentState'
        ]);

        // Verificar que existe la atención
        if (!$appointment->attention || !$appointment->attention->attentionData) {
            return back()->with('error', 'Esta cita no tiene una atención registrada.');
        }

        // Calcular el número de atención
        $attentionNumber = Attention::where('paciente_id', $appointment->paciente_id)
            ->where('created_at', '<=', $appointment->attention->created_at)
            ->count();

        // Preparar datos para el PDF
        $data = [
            'appointment' => $appointment,
            'attention' => $appointment->attention,
            'attentionData' => $appointment->attention->attentionData,
            'paciente' => $appointment->paciente,
            'nutricionista' => $appointment->nutricionista,
            'attentionNumber' => $attentionNumber,
            'attentionDate' => Carbon::parse($appointment->attention->created_at)->format('d-m-Y'),
        ];

        // Generar el PDF
        $pdf = Pdf::loadView('pdf.attention', $data);
        $pdf->setPaper('a4', 'portrait');

        // Mostrar en navegador
        return $pdf->stream();
    }
}
