<?php

namespace App\Livewire\Paciente;

use App\Models\Appointment;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class AppointmentList extends Component
{
    use WithPagination;

    public $estado = '';
    public $nutricionista = '';
    public $fecha_desde = '';
    public $fecha_hasta = '';

    protected $queryString = [
        'estado' => ['except' => ''],
        'nutricionista' => ['except' => ''],
        'fecha_desde' => ['except' => ''],
        'fecha_hasta' => ['except' => ''],
    ];

    public function updatingEstado()
    {
        $this->resetPage();
    }

    public function updatingNutricionista()
    {
        $this->resetPage();
    }

    public function updatingFechaDesde()
    {
        $this->resetPage();
    }

    public function updatingFechaHasta()
    {
        $this->resetPage();
    }

    public function limpiarFiltros()
    {
        $this->reset(['estado', 'nutricionista', 'fecha_desde', 'fecha_hasta']);
        $this->resetPage();
    }

    public function render()
    {
        $query = Appointment::where('paciente_id', auth()->id())
            ->with(['nutricionista', 'appointmentState'])
            ->orderBy('start_time', 'desc');

        // Filtro por estado
        if ($this->estado) {
            $query->whereHas('appointmentState', function ($q) {
                $q->where('name', $this->estado);
            });
        }

        // Filtro por nutricionista
        if ($this->nutricionista) {
            $query->where('nutricionista_id', $this->nutricionista);
        }

        // Filtro por fecha desde
        if ($this->fecha_desde) {
            $query->whereDate('start_time', '>=', $this->fecha_desde);
        }

        // Filtro por fecha hasta
        if ($this->fecha_hasta) {
            $query->whereDate('start_time', '<=', $this->fecha_hasta);
        }

        $appointments = $query->paginate(10);
        
        $nutricionistas = User::whereHas('role', function ($q) {
            $q->where('name', 'nutricionista');
        })->orderBy('name')->get();

        return view('livewire.paciente.appointment-list', [
            'appointments' => $appointments,
            'nutricionistas' => $nutricionistas,
        ]);
    }
}
