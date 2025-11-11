<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Models\User;
use App\Models\Role;
use App\Models\UserState;
use App\Models\Appointment;
use App\Models\AppointmentState;

class AdminController extends Controller
{
    /**
     * Dashboard principal del administrador
     */
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'total_pacientes' => User::whereHas('role', fn($q) => $q->where('name', 'paciente'))->count(),
            'total_nutricionistas' => User::whereHas('role', fn($q) => $q->where('name', 'nutricionista'))->count(),
            'total_appointments' => Appointment::count(),
            'pending_appointments' => Appointment::whereHas('appointmentState', fn($q) => $q->where('name', 'pendiente'))->count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }

    // ==================== GESTIÓN DE USUARIOS ====================

    /**
     * Listar todos los usuarios
     */
    public function users()
    {
        return view('admin.users.index');
    }

    /**
     * Mostrar formulario de creación de usuario
     */
    public function createUser()
    {
        $roles = Role::all();
        $states = UserState::all();
        return view('admin.users.create', compact('roles', 'states'));
    }

    /**
     * Guardar nuevo usuario
     */
    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role_id' => ['required', 'exists:roles,id'],
            'user_state_id' => ['required', 'exists:user_states,id'],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()->route('admin.users.index')->with('success', 'Usuario creado exitosamente');
    }

    /**
     * Mostrar formulario de edición de usuario
     */
    public function editUser(User $user)
    {
        $states = UserState::all();
        return view('admin.users.edit', compact('user', 'states'));
    }

    /**
     * Actualizar usuario
     */
    public function updateUser(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'user_state_id' => ['required', 'exists:user_states,id'],
        ]);

        if (empty($validated['password'])) {
            unset($validated['password']);
        } else {
            $validated['password'] = Hash::make($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('admin.users.index')->with('success', 'Usuario actualizado exitosamente');
    }

    /**
     * Activar/Desactivar usuario
     */
    public function toggleUserStatus(User $user)
    {
        $activeState = UserState::where('name', 'activo')->first();
        $inactiveState = UserState::where('name', 'inactivo')->first();

        if ($user->user_state_id === $activeState->id) {
            $user->update(['user_state_id' => $inactiveState->id]);
            $message = 'Usuario desactivado';
        } else {
            $user->update(['user_state_id' => $activeState->id]);
            $message = 'Usuario activado';
        }

        return back()->with('success', $message);
    }

    // ==================== GESTIÓN DE NUTRICIONISTAS ====================

    /**
     * Listar nutricionistas
     */
    public function nutricionistas(Request $request)
    {
        $roleNutricionista = Role::where('name', 'nutricionista')->first();
        
        $query = User::with(['role', 'userState', 'personalData'])
            ->where('role_id', $roleNutricionista->id);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $nutricionistas = $query->latest()->paginate(15);

        return view('admin.nutricionistas.index', compact('nutricionistas'));
    }

    /**
     * Ver detalles de nutricionista
     */
    public function showNutricionista(User $nutricionista)
    {
        $nutricionista->load(['personalData', 'appointmentsAsNutricionista.paciente']);
        
        $stats = [
            'total_appointments' => $nutricionista->appointmentsAsNutricionista()->count(),
            'completed_appointments' => $nutricionista->appointmentsAsNutricionista()
                ->whereHas('appointmentState', fn($q) => $q->where('name', 'completada'))
                ->count(),
            'pending_appointments' => $nutricionista->appointmentsAsNutricionista()
                ->whereHas('appointmentState', fn($q) => $q->where('name', 'pendiente'))
                ->count(),
        ];

        return view('admin.nutricionistas.show', compact('nutricionista', 'stats'));
    }

    // ==================== GESTIÓN DE PACIENTES ====================

    /**
     * Listar pacientes
     */
    public function pacientes(Request $request)
    {
        $rolePaciente = Role::where('name', 'paciente')->first();
        
        $query = User::with(['role', 'userState', 'personalData'])
            ->where('role_id', $rolePaciente->id);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $pacientes = $query->latest()->paginate(15);

        return view('admin.pacientes.index', compact('pacientes'));
    }

    /**
     * Ver detalles de paciente
     */
    public function showPaciente(User $paciente)
    {
        $paciente->load(['personalData', 'appointmentsAsPaciente.nutricionista']);
        
        $stats = [
            'total_appointments' => $paciente->appointmentsAsPaciente()->count(),
            'completed_appointments' => $paciente->appointmentsAsPaciente()
                ->whereHas('appointmentState', fn($q) => $q->where('name', 'completada'))
                ->count(),
            'pending_appointments' => $paciente->appointmentsAsPaciente()
                ->whereHas('appointmentState', fn($q) => $q->where('name', 'pendiente'))
                ->count(),
        ];

        return view('admin.pacientes.show', compact('paciente', 'stats'));
    }

    // ==================== GESTIÓN DE CITAS ====================

    /**
     * Listar todas las citas
     */
    public function appointments(Request $request)
    {
        $query = Appointment::with(['paciente', 'nutricionista', 'appointmentState']);

        // Filtro por estado
        if ($request->filled('appointment_state_id')) {
            $query->where('appointment_state_id', $request->appointment_state_id);
        }

        // Filtro por fecha
        if ($request->filled('date')) {
            $query->whereDate('start_time', $request->date);
        }

        // Filtro por nutricionista
        if ($request->filled('nutricionista_id')) {
            $query->where('nutricionista_id', $request->nutricionista_id);
        }

        $appointments = $query->latest('start_time')->paginate(15);
        $states = AppointmentState::all();
        $nutricionistas = User::whereHas('role', fn($q) => $q->where('name', 'nutricionista'))->get();

        return view('admin.appointments.index', compact('appointments', 'states', 'nutricionistas'));
    }

    /**
     * Ver detalles de cita
     */
    public function showAppointment(Appointment $appointment)
    {
        $appointment->load(['paciente.personalData', 'nutricionista.personalData', 'appointmentState', 'attention.attentionData']);
        
        return view('admin.appointments.show', compact('appointment'));
    }

    /**
     * Cancelar cita
     */
    public function cancelAppointment(Appointment $appointment)
    {
        $cancelledState = AppointmentState::where('name', 'cancelada')->first();
        
        $appointment->update([
            'appointment_state_id' => $cancelledState->id
        ]);

        return back()->with('success', 'Cita cancelada exitosamente');
    }

    // ==================== REPORTES Y ESTADÍSTICAS ====================

    /**
     * Reportes y estadísticas generales
     */
    public function reports()
    {
        $stats = [
            'users_by_role' => User::select('role_id', DB::raw('count(*) as total'))
                ->groupBy('role_id')
                ->with('role')
                ->get(),
            
            'users_by_state' => User::select('user_state_id', DB::raw('count(*) as total'))
                ->groupBy('user_state_id')
                ->with('userState')
                ->get(),
            
            'appointments_by_state' => Appointment::select('appointment_state_id', DB::raw('count(*) as total'))
                ->groupBy('appointment_state_id')
                ->with('appointmentState')
                ->get(),
            
            'appointments_this_month' => Appointment::whereMonth('start_time', now()->month)
                ->whereYear('start_time', now()->year)
                ->count(),
            
            'appointments_today' => Appointment::whereDate('start_time', now()->toDateString())->count(),
            
            'top_nutricionistas' => User::whereHas('role', fn($q) => $q->where('name', 'nutricionista'))
                ->withCount('appointmentsAsNutricionista')
                ->orderBy('appointments_as_nutricionista_count', 'desc')
                ->take(5)
                ->get(),
            
            'recent_users' => User::with(['role', 'userState'])
                ->latest()
                ->take(10)
                ->get(),
        ];

        return view('admin.reports.index', compact('stats'));
    }

    // ==================== CONFIGURACIÓN ====================

    /**
     * Gestión de roles y estados
     */
    public function settings()
    {
        $roles = Role::withCount('users')->get();
        $userStates = UserState::withCount('users')->get();
        $appointmentStates = AppointmentState::withCount('appointments')->get();

        return view('admin.settings.index', compact('roles', 'userStates', 'appointmentStates'));
    }
}
