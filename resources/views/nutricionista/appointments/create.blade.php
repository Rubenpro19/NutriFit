@extends('layouts.app')

@section('title', 'Asignar Cita - NutriFit')

@section('content')
<body class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-green-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 flex flex-col">
    @include('layouts.header')

    <main class="container mx-auto px-4 py-8 flex-grow">
        <!-- Breadcrumb -->
        <nav class="mb-6 text-sm text-gray-600 dark:text-gray-400">
            <a href="{{ route('nutricionista.dashboard') }}" class="hover:text-green-600 dark:hover:text-green-400">Dashboard</a>
            <span class="mx-2">/</span>
            <span class="text-gray-900 dark:text-white">Asignar Cita</span>
        </nav>

        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center gap-3 mb-2">
                <a href="{{ route('nutricionista.dashboard') }}" class="text-gray-600 dark:text-gray-400 hover:text-green-600 dark:hover:text-green-400 transition-colors">
                    <span class="material-symbols-outlined text-2xl">arrow_back</span>
                </a>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                    Asignar Cita a Paciente
                </h1>
            </div>
            <p class="text-gray-600 dark:text-gray-400 ml-11">
                Selecciona un paciente y asigna un horario disponible
            </p>
        </div>

        @if(session('success'))
            <div class="mb-6 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-800 dark:text-green-200 px-4 py-3 rounded-lg flex items-center gap-3">
                <span class="material-symbols-outlined">check_circle</span>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-800 dark:text-red-200 px-4 py-3 rounded-lg flex items-center gap-3">
                <span class="material-symbols-outlined">error</span>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <div x-data="appointmentAssignment()" x-init="init()">
            @if($pacientes->isEmpty())
                <!-- Sin Pacientes Disponibles -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-12 text-center border border-gray-200 dark:border-gray-700">
                    <div class="text-6xl mb-4">📅</div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">No hay pacientes disponibles</h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-6">
                        Todos tus pacientes ya tienen citas pendientes asignadas o no tienes pacientes registrados.
                    </p>
                    <a 
                        href="{{ route('nutricionista.patients.index') }}"
                        class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white font-semibold rounded-lg hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-lg hover:shadow-xl"
                    >
                        <span class="material-symbols-outlined">groups</span>
                        Ver Mis Pacientes
                    </a>
                </div>
            @else
                <div class="grid lg:grid-cols-3 gap-6">
                    <!-- Columna Izquierda: Selección de Paciente -->
                    <div class="lg:col-span-1">
                        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 border border-gray-200 dark:border-gray-700 lg:sticky lg:top-6">
                            <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                                <span class="material-symbols-outlined text-green-600">person_search</span>
                                Seleccionar Paciente
                            </h2>

                            <div class="space-y-3 max-h-96 overflow-y-auto">
                                @foreach($pacientes as $paciente)
                                    <button
                                        @click="selectPatient({{ $paciente->id }})"
                                        :class="selectedPatientId === {{ $paciente->id }} ? 'border-green-500 bg-green-50 dark:bg-green-900/20' : 'border-gray-200 dark:border-gray-700 hover:border-green-300'"
                                        class="w-full text-left p-4 rounded-lg border-2 transition-all duration-200"
                                    >
                                        <div class="flex items-center gap-3">
                                            <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-500 rounded-full flex items-center justify-center text-white text-sm font-bold flex-shrink-0">
                                                {{ $paciente->initials() }}
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="font-semibold text-gray-900 dark:text-white truncate">{{ $paciente->name }}</p>
                                                <p class="text-xs text-gray-600 dark:text-gray-400 truncate">{{ $paciente->email }}</p>
                                            </div>
                                            <span x-show="selectedPatientId === {{ $paciente->id }}" class="material-symbols-outlined text-green-600">check_circle</span>
                                        </div>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Columna Derecha: Horarios y Formulario -->
                    <div class="lg:col-span-2">
                        <!-- Mensaje inicial -->
                        <div x-show="!selectedPatientId" class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-12 text-center border border-gray-200 dark:border-gray-700">
                            <span class="material-symbols-outlined text-6xl text-gray-300 dark:text-gray-600 mb-4">arrow_back</span>
                            <p class="text-gray-600 dark:text-gray-400">Selecciona un paciente para ver los horarios disponibles</p>
                        </div>

                        <!-- Loading -->
                        <div x-show="loading" class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-12 text-center border border-gray-200 dark:border-gray-700">
                            <svg class="animate-spin h-12 w-12 mx-auto text-green-600 mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <p class="text-gray-600 dark:text-gray-400">Cargando horarios disponibles...</p>
                        </div>

                        <!-- Horarios Disponibles y Formulario -->
                        <div x-show="selectedPatientId && !loading && weeks.length > 0">
                            <form method="POST" action="{{ route('nutricionista.appointments.store') }}" @submit="submitting = true">
                                @csrf
                                <input type="hidden" name="paciente_id" x-model="selectedPatientId">
                                <input type="hidden" name="appointment_date" x-model="selectedDate">
                                <input type="hidden" name="appointment_time" x-model="selectedTime">

                                <!-- Información del Paciente Seleccionado -->
                                <div class="bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 rounded-2xl p-6 mb-6 border border-green-200 dark:border-green-800">
                                    <div class="flex items-center gap-4 mb-4">
                                        <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-500 rounded-full flex items-center justify-center text-white text-xl font-bold flex-shrink-0">
                                            <span x-text="selectedPatient?.name?.charAt(0).toUpperCase()"></span>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">Asignando cita para:</p>
                                            <p class="text-2xl font-bold text-gray-900 dark:text-white" x-text="selectedPatient?.name"></p>
                                            <p class="text-sm text-gray-600 dark:text-gray-400" x-text="selectedPatient?.email"></p>
                                        </div>
                                    </div>
                                    
                                    <!-- Resumen de Selección -->
                                    <div x-show="selectedDate && selectedTime" class="mt-4 pt-4 border-t border-green-200 dark:border-green-700">
                                        <p class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Horario seleccionado:</p>
                                        <div class="flex flex-wrap gap-3">
                                            <div class="flex items-center gap-2 bg-white dark:bg-gray-800 px-4 py-2 rounded-lg">
                                                <span class="material-symbols-outlined text-green-600 text-sm">calendar_today</span>
                                                <span class="text-sm font-medium text-gray-900 dark:text-white" x-text="selectedDate ? new Date(selectedDate + 'T00:00:00').toLocaleDateString('es-ES', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }) : ''"></span>
                                            </div>
                                            <div class="flex items-center gap-2 bg-white dark:bg-gray-800 px-4 py-2 rounded-lg">
                                                <span class="material-symbols-outlined text-green-600 text-sm">schedule</span>
                                                <span class="text-sm font-medium text-gray-900 dark:text-white" x-text="selectedTime"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Detalles de la Cita -->
                                <div x-show="selectedDate && selectedTime" class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-4 sm:p-6 border border-gray-200 dark:border-gray-700 mb-6">
                                    <h3 class="text-base sm:text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                                        <span class="material-symbols-outlined text-green-600">edit_note</span>
                                        Detalles de la Cita
                                    </h3>

                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                                        <!-- Tipo de Consulta -->
                                        <div>
                                            <label for="appointment_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                Tipo de Consulta *
                                            </label>
                                            <select 
                                                id="appointment_type" 
                                                name="appointment_type" 
                                                required
                                                class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                            >
                                                <option value="">Seleccionar tipo</option>
                                                <option value="primera_vez">🆕 Primera vez</option>
                                                <option value="seguimiento">📋 Seguimiento</option>
                                                <option value="control">🔄 Control</option>
                                            </select>
                                        </div>

                                        <!-- Precio -->
                                        <div>
                                            <label for="price" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                Precio (USD) *
                                            </label>
                                            <input 
                                                type="number" 
                                                id="price" 
                                                name="price" 
                                                step="0.01" 
                                                min="0"
                                                value="50.00"
                                                required
                                                class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                            >
                                        </div>

                                        <!-- Motivo -->
                                        <div class="sm:col-span-2">
                                            <label for="reason" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                Motivo de la Consulta
                                            </label>
                                            <textarea 
                                                id="reason" 
                                                name="reason" 
                                                rows="3"
                                                placeholder="Ej: Control de peso, Evaluación inicial, etc."
                                                class="w-full px-3 sm:px-4 py-2 text-sm sm:text-base rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                            ></textarea>
                                        </div>

                                        <!-- Notas -->
                                        <div class="sm:col-span-2">
                                            <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                Notas Adicionales
                                            </label>
                                            <textarea 
                                                id="notes" 
                                                name="notes" 
                                                rows="3"
                                                placeholder="Notas internas sobre la cita..."
                                                class="w-full px-3 sm:px-4 py-2 text-sm sm:text-base rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                            ></textarea>
                                        </div>
                                    </div>

                                    <!-- Botones -->
                                    <div class="mt-6 flex flex-col sm:flex-row gap-3">
                                        <button 
                                            type="submit"
                                            :disabled="submitting"
                                            class="flex-1 flex items-center justify-center gap-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white font-semibold py-3 px-4 sm:px-6 text-sm sm:text-base rounded-lg hover:from-green-700 hover:to-emerald-700 transition shadow-lg disabled:opacity-50 disabled:cursor-not-allowed"
                                        >
                                            <span x-show="!submitting" class="material-symbols-outlined">check</span>
                                            <svg x-show="submitting" class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                            <span x-text="submitting ? 'Asignando Cita...' : 'Asignar Cita'"></span>
                                        </button>
                                        <button 
                                            type="button"
                                            @click="resetSelection"
                                            class="px-4 sm:px-6 py-3 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 font-semibold text-sm sm:text-base rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition"
                                        >
                                            Cancelar
                                        </button>
                                    </div>
                                </div>

                                <!-- Selección de Horario con Navegación por Semanas -->
                                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg mb-6 border border-gray-200 dark:border-gray-700 overflow-hidden">
                                    <!-- Header -->
                                    <div class="bg-gradient-to-r from-green-600 to-emerald-600 px-6 py-4">
                                        <h3 class="text-xl font-bold text-white flex items-center gap-2">
                                            <span class="material-symbols-outlined">calendar_month</span>
                                            Seleccionar Horario
                                        </h3>
                                        <p class="text-green-100 text-sm mt-1">Próximas 4 semanas disponibles</p>
                                    </div>

                                    <!-- Navegación de Semanas -->
                                    <div class="border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/30">
                                        <div class="flex items-center justify-between px-3 sm:px-6 py-3">
                                            <button type="button" @click="changeWeek(-1)" :disabled="currentWeek === 0"
                                                    class="flex items-center gap-1 sm:gap-2 px-2 sm:px-4 py-2 text-xs sm:text-sm font-semibold text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700 rounded-lg transition disabled:opacity-50 disabled:cursor-not-allowed">
                                                <span class="material-symbols-outlined text-lg sm:text-base">chevron_left</span>
                                                <span class="hidden sm:inline">Anterior</span>
                                            </button>
                                            
                                            <div class="flex gap-2 overflow-x-auto pb-2 flex-1 mx-2 sm:mx-4 justify-start sm:justify-center scrollbar-hide">
                                                <template x-for="(week, index) in weeks" :key="index">
                                                    <button type="button" @click="showWeek(index)"
                                                            :class="currentWeek === index 
                                                                ? 'bg-gradient-to-r from-green-600 to-emerald-600 text-white shadow-lg' 
                                                                : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'"
                                                            class="flex-shrink-0 px-3 sm:px-6 py-2 sm:py-3 rounded-lg font-semibold transition-all">
                                                        <div class="text-xs opacity-90 mb-1" x-text="'Sem ' + (index + 1)"></div>
                                                        <div class="text-xs sm:text-sm font-bold" x-text="week.label"></div>
                                                    </button>
                                                </template>
                                            </div>

                                            <button type="button" @click="changeWeek(1)" :disabled="currentWeek === weeks.length - 1"
                                                    class="flex items-center gap-1 sm:gap-2 px-2 sm:px-4 py-2 text-xs sm:text-sm font-semibold text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700 rounded-lg transition disabled:opacity-50 disabled:cursor-not-allowed">
                                                <span class="hidden sm:inline">Siguiente</span>
                                                <span class="material-symbols-outlined text-lg sm:text-base">chevron_right</span>
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Contenido de la Semana Actual -->
                                    <div class="p-3 sm:p-6">
                                        <template x-for="(week, weekIndex) in weeks" :key="weekIndex">
                                            <div x-show="currentWeek === weekIndex" class="space-y-3 sm:space-y-4">
                                                <template x-for="daySlots in week.days" :key="daySlots.date">
                                                    <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-3 sm:p-4">
                                                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-3 gap-2">
                                                            <h4 class="font-semibold text-sm sm:text-base text-gray-900 dark:text-white capitalize flex items-center gap-2">
                                                                <span class="material-symbols-outlined text-green-600 text-lg sm:text-xl">calendar_today</span>
                                                                <span x-text="daySlots.date_formatted"></span>
                                                            </h4>
                                                            <span class="text-xs text-gray-500 dark:text-gray-400 pl-7 sm:pl-0" x-text="daySlots.slots.length + ' horarios'"></span>
                                                        </div>
                                                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-2">
                                                            <template x-for="slot in daySlots.slots" :key="slot.time">
                                                                <button
                                                                    type="button"
                                                                    @click="selectSlot(daySlots.date, slot.time)"
                                                                    :class="selectedDate === daySlots.date && selectedTime === slot.time 
                                                                        ? 'bg-green-600 text-white ring-4 ring-green-400 shadow-lg scale-105' 
                                                                        : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-green-100 dark:hover:bg-green-900/30 hover:shadow-md hover:scale-105'"
                                                                    class="px-2 sm:px-3 py-2 sm:py-2.5 rounded-lg text-xs sm:text-sm font-medium transition-all duration-200 flex items-center justify-center gap-1"
                                                                >
                                                                    <span class="material-symbols-outlined text-xs">schedule</span>
                                                                    <span x-text="slot.time_formatted"></span>
                                                                </button>
                                                            </template>
                                                        </div>
                                                    </div>
                                                </template>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <!-- Sin Horarios Disponibles -->
                        <div x-show="selectedPatientId && !loading && weeks.length === 0" class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-12 text-center border border-gray-200 dark:border-gray-700">
                            <span class="material-symbols-outlined text-6xl text-orange-400 mb-4">event_busy</span>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">No hay horarios disponibles</h3>
                            <p class="text-gray-600 dark:text-gray-400 mb-6">
                                No tienes horarios configurados o todos están ocupados para las próximas 4 semanas.
                            </p>
                            <a 
                                href="{{ route('nutricionista.schedules.index') }}"
                                class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white font-semibold rounded-lg hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-lg hover:shadow-xl"
                            >
                                <span class="material-symbols-outlined">schedule</span>
                                Configurar Horarios
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </main>

    @include('layouts.footer')

    <script>
        function appointmentAssignment() {
            return {
                selectedPatientId: null,
                selectedPatient: null,
                weeks: [],
                currentWeek: 0,
                selectedDate: null,
                selectedTime: null,
                loading: false,
                submitting: false,

                init() {
                    // Inicialización si es necesaria
                },

                async selectPatient(patientId) {
                    this.selectedPatientId = patientId;
                    this.loading = true;
                    this.weeks = [];
                    this.currentWeek = 0;
                    this.selectedDate = null;
                    this.selectedTime = null;

                    try {
                        const response = await fetch(`/nutricionista/citas/asignar/${patientId}/horarios`);
                        const data = await response.json();
                        
                        if (response.ok) {
                            this.selectedPatient = data.paciente;
                            this.weeks = data.weeks.map(week => ({
                                label: week.start_date_formatted,
                                week_number: week.week_number,
                                days: week.days.filter(day => day.slots.length > 0) // Solo días con horarios
                            }));
                        } else {
                            alert(data.error || 'Error al cargar horarios');
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        alert('Error al cargar los horarios disponibles');
                    } finally {
                        this.loading = false;
                    }
                },

                showWeek(index) {
                    this.currentWeek = index;
                },

                changeWeek(direction) {
                    const newWeek = this.currentWeek + direction;
                    if (newWeek >= 0 && newWeek < this.weeks.length) {
                        this.currentWeek = newWeek;
                    }
                },

                selectSlot(date, time) {
                    this.selectedDate = date;
                    this.selectedTime = time;
                },

                resetSelection() {
                    this.selectedPatientId = null;
                    this.selectedPatient = null;
                    this.weeks = [];
                    this.currentWeek = 0;
                    this.selectedDate = null;
                    this.selectedTime = null;
                }
            }
        }
    </script>
</body>
@endsection
