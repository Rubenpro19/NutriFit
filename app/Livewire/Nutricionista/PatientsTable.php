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
    public $sort = 'name';

    protected $queryString = [
        'search' => ['except' => ''],
        'estado' => ['except' => ''],
        'proximas_citas' => ['except' => ''],
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

    public function updatedSort()
    {
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->search = '';
        $this->estado = '';
        $this->proximas_citas = '';
        $this->sort = 'name';
        $this->resetPage();
    }

    public function render()
    {
        $nutricionista = auth()->user();
        
        // Query base: obtener todos los usuarios con rol 'paciente'
        $query = User::whereHas('role', fn($q) => $q->where('name', 'paciente'))
            ->with([
                'userState', 
                'personalData',
                'appointmentsAsPaciente' => function($q) use ($nutricionista) {
                    // Cargar solo la próxima cita pendiente
                    $q->where('nutricionista_id', $nutricionista->id)
                      ->whereHas('appointmentState', fn($sq) => $sq->where('name', 'pendiente'))
                      ->where('start_time', '>=', now())
                      ->orderBy('start_time', 'asc')
                      ->limit(1);
                }
            ])
            ->withCount([
                'appointmentsAsPaciente as total_appointments' => function($q) use ($nutricionista) {
                    $q->where('nutricionista_id', $nutricionista->id);
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
