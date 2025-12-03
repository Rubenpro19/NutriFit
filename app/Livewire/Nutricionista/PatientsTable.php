<?php

namespace App\Livewire\Nutricionista;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use App\Models\UserState;

class PatientsTable extends Component
{
    use WithPagination;

    public $search = '';
    public $estado = '';
    public $proximas_citas = '';
    public $con_atenciones = '';
    public $sort = 'name';

    protected $queryString = [
        'search' => ['except' => ''],
        'estado' => ['except' => ''],
        'proximas_citas' => ['except' => ''],
        'con_atenciones' => ['except' => ''],
        'sort' => ['except' => 'name'],
        'page' => ['except' => 1],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatedEstado()
    {
        $this->resetPage();
    }

    public function updatedProximasCitas()
    {
        $this->resetPage();
    }

    public function updatedConAtenciones()
    {
        $this->resetPage();
    }

    public function updatedSort()
    {
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->reset(['search', 'estado', 'proximas_citas', 'con_atenciones', 'sort']);
        $this->resetPage();
    }

    public function render()
    {
        $nutricionista = auth()->user();
        
        // Query base: obtener todos los usuarios con rol 'paciente' (no limitar a los que ya tienen citas)
        $query = User::whereHas('role', fn($q) => $q->where('name', 'paciente'))
            ->with(['userState', 'personalData'])
            ->withCount([
                'appointmentsAsPaciente as total_appointments' => function($q) use ($nutricionista) {
                    $q->where('nutricionista_id', $nutricionista->id);
                },
                'appointmentsAsPaciente as completed_appointments' => function($q) use ($nutricionista) {
                    $q->where('nutricionista_id', $nutricionista->id)
                      ->whereHas('appointmentState', fn($sq) => $sq->where('name', 'completada'));
                },
                'appointmentsAsPaciente as pending_appointments' => function($q) use ($nutricionista) {
                    $q->where('nutricionista_id', $nutricionista->id)
                      ->whereHas('appointmentState', fn($sq) => $sq->where('name', 'pendiente'))
                      ->where('start_time', '>=', now());
                }
            ])
            ->withExists([
                'appointmentsAsPaciente as has_upcoming_appointment' => function($q) use ($nutricionista) {
                    $q->where('nutricionista_id', $nutricionista->id)
                      ->whereHas('appointmentState', fn($sq) => $sq->where('name', 'pendiente'))
                      ->where('start_time', '>=', now());
                }
            ]);

        // Filtro por búsqueda de nombre o email
        if (!empty($this->search)) {
            $search = $this->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'ILIKE', "%{$search}%")
                  ->orWhere('email', 'ILIKE', "%{$search}%");
            });
        }

        // Filtro por estado de usuario
        if (!empty($this->estado)) {
            $query->where('user_state_id', $this->estado);
        }

        // Filtro por pacientes con citas próximas
        if ($this->proximas_citas === '1') {
            $query->whereHas('appointmentsAsPaciente', function($q) use ($nutricionista) {
                $q->where('nutricionista_id', $nutricionista->id)
                  ->whereHas('appointmentState', fn($sq) => $sq->where('name', 'pendiente'))
                  ->where('start_time', '>=', now());
            });
        }

        // Filtro por pacientes con atenciones completadas
        if ($this->con_atenciones === '1') {
            $query->whereHas('appointmentsAsPaciente', function($q) use ($nutricionista) {
                $q->where('nutricionista_id', $nutricionista->id)
                  ->whereHas('attention');
            });
        }

        // Ordenamiento
        if ($this->sort === 'name') {
            $query->orderBy('name', 'asc');
        } elseif ($this->sort === 'recent') {
            $query->withMax('appointmentsAsPaciente as last_appointment_date', 'start_time')
                  ->orderBy('last_appointment_date', 'desc');
        }

        $patients = $query->paginate(12);

        // Obtener estados de usuario para el filtro
        $userStates = UserState::all();

        return view('livewire.nutricionista.patients-table', compact('patients', 'userStates'));
    }
}
