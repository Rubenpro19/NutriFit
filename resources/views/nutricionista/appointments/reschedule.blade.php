@extends('layouts.app')

@section('title', 'Reagendar Cita - NutriFit')

@section('content')
<style>
    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }
    .scrollbar-hide {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
</style>
<body class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-green-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 flex flex-col">
    @include('layouts.header')

    <main class="container mx-auto px-4 py-8 flex-grow">
        <div class="max-w-7xl mx-auto">
        <!-- Breadcrumb -->
        <nav class="mb-6 flex items-center gap-2 text-sm">
            <a href="{{ route('nutricionista.dashboard') }}" class="text-emerald-600 dark:text-emerald-400 hover:underline">Dashboard</a>
            <span class="material-symbols-outlined text-gray-400 text-sm">chevron_right</span>
            <a href="{{ route('nutricionista.appointments.show', $appointment) }}" class="text-emerald-600 dark:text-emerald-400 hover:underline">Detalle de Cita</a>
            <span class="material-symbols-outlined text-gray-400 text-sm">chevron_right</span>
            <span class="text-gray-600 dark:text-gray-400">Reagendar</span>
        </nav>

        @if(session('error'))
            <div class="mb-6 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-800 dark:text-red-200 px-4 py-3 rounded-lg flex items-center gap-3">
                <span class="material-symbols-outlined">error</span>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <div class="grid lg:grid-cols-5 gap-8">
            <!-- Panel Izquierdo: Info de la Cita Actual -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Cita Actual -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 border border-gray-200 dark:border-gray-700">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined text-amber-600">event</span>
                        Cita Actual
                    </h2>
                    
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-16 h-16 rounded-full bg-gradient-to-br from-emerald-400 to-emerald-600 flex items-center justify-center text-white text-2xl font-bold">
                            {{ substr($appointment->paciente->name, 0, 1) }}
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                {{ $appointment->paciente->name }}
                            </h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $appointment->paciente->email }}</p>
                        </div>
                    </div>

                    <div class="space-y-3 text-sm">
                        <div class="flex items-center gap-3 text-gray-700 dark:text-gray-300">
                            <span class="material-symbols-outlined text-amber-600">calendar_today</span>
                            <span>{{ \Carbon\Carbon::parse($appointment->start_time)->format('d/m/Y') }}</span>
                        </div>
                        <div class="flex items-center gap-3 text-gray-700 dark:text-gray-300">
                            <span class="material-symbols-outlined text-amber-600">schedule</span>
                            <span>{{ \Carbon\Carbon::parse($appointment->start_time)->format('H:i') }}</span>
                        </div>
                        <div class="flex items-center gap-3 text-gray-700 dark:text-gray-300">
                            <span class="material-symbols-outlined text-amber-600">medical_information</span>
                            <span>{{ $appointment->appointment_type === 'primera_vez' ? 'Primera vez' : ($appointment->appointment_type === 'seguimiento' ? 'Seguimiento' : 'Control') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Formulario de Reagendamiento -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 border border-gray-200 dark:border-gray-700 sticky top-24">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
                        <span class="material-symbols-outlined text-emerald-600">event_repeat</span>
                        Nuevo Horario
                    </h3>
                    
                    <form method="POST" action="{{ route('nutricionista.appointments.reschedule.store', $appointment) }}" id="rescheduleForm">
                        @csrf
                        
                        <input type="hidden" name="date" id="selectedDate">
                        <input type="hidden" name="time" id="selectedTime">

                        <!-- Horario Seleccionado -->
                        <div class="mb-6 p-4 bg-gradient-to-br from-emerald-50 to-green-50 dark:from-emerald-900/20 dark:to-green-900/20 rounded-xl border-2 border-dashed border-emerald-300 dark:border-emerald-600">
                            <div id="selectionDisplay" class="text-center py-4">
                                <span class="material-symbols-outlined text-5xl text-emerald-400 dark:text-emerald-500 mb-3 block">event_note</span>
                                <p class="text-sm text-gray-600 dark:text-gray-400 font-medium">
                                    Selecciona la nueva fecha y hora
                                </p>
                            </div>
                            <div id="selectedInfo" class="hidden space-y-3">
                                <div class="flex items-center gap-3 p-3 bg-white dark:bg-gray-700 rounded-lg">
                                    <span class="material-symbols-outlined text-emerald-600 dark:text-emerald-400">calendar_today</span>
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Nueva Fecha</p>
                                        <p id="displayDate" class="font-bold text-gray-900 dark:text-white"></p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3 p-3 bg-white dark:bg-gray-700 rounded-lg">
                                    <span class="material-symbols-outlined text-emerald-600 dark:text-emerald-400">schedule</span>
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Nueva Hora</p>
                                        <p id="displayTime" class="font-bold text-gray-900 dark:text-white"></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Motivo del Reagendamiento -->
                        <div class="mb-6">
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                Motivo del reagendamiento
                                <span class="text-gray-400 font-normal">(Opcional)</span>
                            </label>
                            <textarea name="reschedule_reason" rows="3"
                                      class="w-full rounded-lg border-2 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-4 py-3 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200 dark:focus:ring-emerald-800 transition resize-none"
                                      placeholder="Ej: Ajuste de agenda, imprevisto, solicitud del paciente..."></textarea>
                        </div>

                        <!-- Botones de Acción -->
                        <div class="space-y-3">
                            <button type="submit" id="submitBtn" disabled
                                    class="w-full rounded-xl bg-gradient-to-r from-emerald-600 to-green-600 px-6 py-4 font-bold text-white text-lg transition-all hover:from-emerald-700 hover:to-green-700 hover:shadow-xl disabled:from-gray-300 disabled:to-gray-400 disabled:cursor-not-allowed disabled:shadow-none flex items-center justify-center gap-2 group">
                                <span class="material-symbols-outlined group-disabled:animate-none">check_circle</span>
                                <span id="btnText">Selecciona un horario</span>
                            </button>
                            
                            <a href="{{ route('nutricionista.appointments.show', $appointment) }}" 
                               class="w-full block text-center rounded-xl bg-gray-100 dark:bg-gray-700 px-6 py-3 font-semibold text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                                Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Panel Derecho: Calendario -->
            <div class="lg:col-span-3">
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <!-- Header del Calendario -->
                    <div class="bg-gradient-to-r from-emerald-600 to-green-600 px-6 py-4">
                        <div class="flex items-center justify-between flex-wrap gap-4">
                            <div>
                                <h2 class="text-2xl font-bold text-white mb-1">Horarios Disponibles</h2>
                                <p class="text-emerald-100 text-sm">Selecciona el nuevo día y hora</p>
                            </div>
                            <div class="flex items-center gap-3 bg-white/20 backdrop-blur-sm rounded-lg px-4 py-2">
                                <div class="flex items-center gap-2">
                                    <span class="w-3 h-3 rounded-full bg-emerald-400"></span>
                                    <span class="text-white text-sm font-medium">Disponible</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Navegación de Semanas (Pestañas) -->
                    <div class="border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/30">
                        <div class="flex items-center justify-between px-3 sm:px-6 py-3">
                            <button onclick="changeWeek(-1)" id="prevWeek" class="flex items-center gap-1 sm:gap-2 px-2 sm:px-4 py-2 text-xs sm:text-sm font-semibold text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700 rounded-lg transition disabled:opacity-50 disabled:cursor-not-allowed">
                                <span class="material-symbols-outlined text-lg sm:text-base">chevron_left</span>
                                <span class="hidden sm:inline">Anterior</span>
                            </button>
                            
                            <div class="flex gap-2 overflow-x-auto pb-2 flex-1 mx-2 sm:mx-4 justify-start sm:justify-center scrollbar-hide">
                                @foreach($weeks as $weekIndex => $week)
                                    <button onclick="showWeek({{ $weekIndex }})" 
                                            data-week="{{ $weekIndex }}"
                                            class="week-tab flex-shrink-0 px-3 sm:px-6 py-2 sm:py-3 rounded-lg font-semibold transition-all {{ $weekIndex === 0 ? 'bg-gradient-to-r from-emerald-600 to-green-600 text-white shadow-lg' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                        <div class="text-xs opacity-90 mb-1">Sem {{ $weekIndex + 1 }}</div>
                                        <div class="text-xs sm:text-sm font-bold">{{ $week['start_date_formatted'] }}</div>
                                    </button>
                                @endforeach
                            </div>

                            <button onclick="changeWeek(1)" id="nextWeek" class="flex items-center gap-1 sm:gap-2 px-2 sm:px-4 py-2 text-xs sm:text-sm font-semibold text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700 rounded-lg transition disabled:opacity-50 disabled:cursor-not-allowed">
                                <span class="hidden sm:inline">Siguiente</span>
                                <span class="material-symbols-outlined text-lg sm:text-base">chevron_right</span>
                            </button>
                        </div>
                    </div>

                    <!-- Contenido del Calendario -->
                    <div class="p-3 sm:p-6">
                        @foreach($weeks as $weekIndex => $week)
                            <div id="week-{{ $weekIndex }}" class="week-content {{ $weekIndex !== 0 ? 'hidden' : '' }} space-y-3 sm:space-y-4">
                                @foreach($week['days'] as $day)
                                    <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-3 sm:p-4">
                                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-3 gap-2">
                                            <h4 class="font-semibold text-sm sm:text-base text-gray-900 dark:text-white capitalize flex items-center gap-2">
                                                <span class="material-symbols-outlined text-emerald-600 text-lg sm:text-xl">calendar_today</span>
                                                <span>{{ $day['date_formatted'] }}</span>
                                            </h4>
                                            <span class="text-xs text-gray-500 dark:text-gray-400 pl-7 sm:pl-0">{{ count($day['slots']) }} horarios</span>
                                        </div>
                                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-2">
                                            @foreach($day['slots'] as $slot)
                                                <button
                                                    type="button"
                                                    onclick="selectSlot('{{ $day['date']->format('Y-m-d') }}', '{{ $slot['time'] }}', this)"
                                                    class="time-slot px-2 sm:px-3 py-2 sm:py-2.5 rounded-lg text-xs sm:text-sm font-medium transition-all duration-200 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-emerald-100 dark:hover:bg-emerald-900/30 hover:shadow-md hover:scale-105 flex items-center justify-center gap-1"
                                                >
                                                    <span class="material-symbols-outlined text-xs">schedule</span>
                                                    <span>{{ $slot['time'] }}</span>
                                                </button>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        </div>
    </main>

    @include('layouts.footer')

    <script>
        let selectedButton = null;
        let currentWeek = 0;
        const totalWeeks = {{ count($weeks) }};

        function selectSlot(date, time, button) {
            // Remover selección anterior
            if (selectedButton) {
                selectedButton.classList.remove('ring-4', 'ring-emerald-400', 'bg-emerald-600', 'text-white', 'shadow-lg', 'scale-105', '!bg-emerald-600');
                selectedButton.classList.add('bg-gray-100', 'dark:bg-gray-700', 'text-gray-700', 'dark:text-gray-300');
            }

            // Aplicar selección actual
            selectedButton = button;
            button.classList.remove('bg-gray-100', 'dark:bg-gray-700', 'text-gray-700', 'dark:text-gray-300');
            button.classList.add('bg-emerald-600', 'text-white', 'ring-4', 'ring-emerald-400', 'shadow-lg', 'scale-105', '!bg-emerald-600');

            // Actualizar campos del formulario
            document.getElementById('selectedDate').value = date;
            document.getElementById('selectedTime').value = time;
            
            // Formatear fecha
            const dateObj = new Date(date + 'T00:00:00');
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            const formattedDate = dateObj.toLocaleDateString('es-ES', options);
            
            // Actualizar UI
            document.getElementById('selectionDisplay').classList.add('hidden');
            document.getElementById('selectedInfo').classList.remove('hidden');
            document.getElementById('displayDate').textContent = formattedDate.charAt(0).toUpperCase() + formattedDate.slice(1);
            document.getElementById('displayTime').textContent = time;
            
            // Habilitar botón y cambiar texto
            const submitBtn = document.getElementById('submitBtn');
            submitBtn.disabled = false;
            document.getElementById('btnText').textContent = 'Confirmar Reagendamiento';
            
            // Scroll suave en móvil
            if (window.innerWidth < 1024) {
                document.getElementById('rescheduleForm').scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }

        function showWeek(weekIndex) {
            // Ocultar todas las semanas
            document.querySelectorAll('.week-content').forEach(week => {
                week.classList.add('hidden');
            });

            // Mostrar la semana seleccionada
            document.getElementById('week-' + weekIndex).classList.remove('hidden');

            // Actualizar pestañas
            document.querySelectorAll('.week-tab').forEach((tab, index) => {
                if (index === weekIndex) {
                    tab.classList.remove('bg-white', 'dark:bg-gray-800', 'text-gray-700', 'dark:text-gray-300');
                    tab.classList.add('bg-gradient-to-r', 'from-emerald-600', 'to-green-600', 'text-white', 'shadow-lg');
                } else {
                    tab.classList.remove('bg-gradient-to-r', 'from-emerald-600', 'to-green-600', 'text-white', 'shadow-lg');
                    tab.classList.add('bg-white', 'dark:bg-gray-800', 'text-gray-700', 'dark:text-gray-300');
                }
            });

            currentWeek = weekIndex;
            updateNavigationButtons();
        }

        function changeWeek(direction) {
            const newWeek = currentWeek + direction;
            if (newWeek >= 0 && newWeek < totalWeeks) {
                showWeek(newWeek);
            }
        }

        function updateNavigationButtons() {
            const prevBtn = document.getElementById('prevWeek');
            const nextBtn = document.getElementById('nextWeek');

            prevBtn.disabled = currentWeek === 0;
            nextBtn.disabled = currentWeek === totalWeeks - 1;
        }

        // Inicializar
        updateNavigationButtons();
    </script>
@endsection
