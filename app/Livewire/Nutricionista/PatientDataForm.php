<?php

namespace App\Livewire\Nutricionista;

use Livewire\Component;
use App\Models\User;
use App\Models\PersonalData;
use Illuminate\Support\Facades\Auth;

class PatientDataForm extends Component
{
    public User $patient;
    public $appointmentId = null;
    public $phone = '';
    public $address = '';
    public $birth_date = '';
    public $gender = '';
    
    public $hasPersonalData = false;
    public $isReadOnly = false;

    protected function rules()
    {
        return [
            'gender' => 'required|in:male,female,other',
            'birth_date' => 'required|date|before:today',
            'phone' => 'nullable|string|max:10',
            'address' => 'nullable|string|max:255',
        ];
    }

    protected $messages = [
        'gender.required' => 'El género es obligatorio.',
        'gender.in' => 'El género seleccionado no es válido.',
        'birth_date.required' => 'La fecha de nacimiento es obligatoria.',
        'birth_date.date' => 'La fecha de nacimiento debe ser una fecha válida.',
        'birth_date.before' => 'La fecha de nacimiento debe ser anterior a hoy.',
        'phone.max' => 'El teléfono no puede tener más de 10 caracteres.',
        'address.max' => 'La dirección no puede tener más de 255 caracteres.',
    ];

    public function mount(User $patient, $appointmentId = null)
    {
        // Verificar que el usuario autenticado es nutricionista
        if (!Auth::user()->role || Auth::user()->role->name !== 'nutricionista') {
            abort(403, 'No tienes permiso para acceder a esta página.');
        }

        // Verificar que el paciente tiene rol de paciente
        if (!$patient->role || $patient->role->name !== 'paciente') {
            abort(404, 'Paciente no encontrado.');
        }

        $this->patient = $patient;
        $this->appointmentId = $appointmentId;

        // Verificar si ya tiene datos personales
        if ($patient->personalData) {
            $this->hasPersonalData = true;
            $this->isReadOnly = true;
            
            // Cargar datos existentes
            $this->phone = $patient->personalData->phone ?? '';
            $this->address = $patient->personalData->address ?? '';
            $this->birth_date = $patient->personalData->birth_date ? 
                $patient->personalData->birth_date->format('Y-m-d') : '';
            $this->gender = $patient->personalData->gender ?? '';
        }
    }

    public function save()
    {
        // Solo permitir guardar si no tiene datos personales aún
        if ($this->hasPersonalData) {
            session()->flash('error', 'Este paciente ya tiene datos personales asignados.');
            return;
        }

        $this->validate();

        try {
            PersonalData::create([
                'user_id' => $this->patient->id,
                'gender' => $this->gender,
                'birth_date' => $this->birth_date,
                'phone' => $this->phone,
                'address' => $this->address,
            ]);
            
            // Si hay un appointment_id pendiente, redirigir a registrar atención
            if ($this->appointmentId) {
                return $this->redirect(route('nutricionista.attentions.create', $this->appointmentId), navigate: false);
            }
            
            // Si no hay appointment_id, redirigir a la lista de pacientes
            return $this->redirect(route('nutricionista.patients.index'), navigate: false);

        } catch (\Exception $e) {
            session()->flash('error', 'Error al guardar los datos: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.nutricionista.patient-data-form');
    }
}
