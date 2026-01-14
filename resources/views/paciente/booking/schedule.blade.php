@extends('layouts.app')

@section('title', 'Seleccionar Horario - NutriFit')

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
<body class="min-h-screen bg-gradient-to-br from-green-50 via-emerald-50 to-rose-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 flex flex-col">
    @include('layouts.header')

    <main class="flex-grow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
        <!-- Breadcrumb -->
        <nav class="mb-6 flex items-center gap-2 text-sm">
            <a href="{{ route('paciente.dashboard') }}" class="text-green-600 dark:text-green-400 hover:underline transition-colors">Inicio</a>
            <span class="material-symbols-outlined text-gray-400 text-sm">chevron_right</span>
            <a href="{{ route('paciente.booking.index') }}" class="text-green-600 dark:text-green-400 hover:underline transition-colors">Agendar Cita</a>
            <span class="material-symbols-outlined text-gray-400 text-sm">chevron_right</span>
            <span class="text-gray-700 dark:text-gray-300 font-medium">Seleccionar Horario</span>
        </nav>

        <!-- Header -->
        <div class="mb-8 bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-4 sm:p-6 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between gap-3">
                <div class="flex items-center gap-3 flex-1 min-w-0">
                    <a href="{{ route('paciente.booking.index') }}" class="text-gray-700 dark:text-gray-300 hover:text-green-600 dark:hover:text-green-400 hover:scale-110 transition-all">
                        <span class="material-symbols-outlined text-2xl">arrow_back</span>
                    </a>
                    <div class="min-w-0 flex-1">
                        <h1 class="text-2xl sm:text-3xl font-bold bg-gradient-to-r from-green-600 to-emerald-600 dark:from-green-400 dark:to-emerald-400 bg-clip-text text-transparent">
                            Seleccionar Horario
                        </h1>
                        <p class="text-sm sm:text-base text-gray-700 dark:text-gray-300 mt-1 truncate">
                            Elige la fecha y hora perfecta para tu cita
                        </p>
                    </div>
                </div>
                <div class="flex-shrink-0 hidden sm:flex items-center">
                    <span class="material-symbols-outlined text-3xl sm:text-4xl text-green-600 dark:text-green-400">event_available</span>
                </div>
            </div>
        </div>

        @if(session('error'))
            <div class="mb-6 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-800 dark:text-red-200 px-4 py-3 rounded-xl flex items-center gap-3">
                <span class="material-symbols-outlined">error</span>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <div class="grid lg:grid-cols-5 gap-8">
            <!-- Panel Izquierdo: Info del Nutricionista y Formulario -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Información del Nutricionista -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 border border-gray-200 dark:border-gray-700">
                    <div class="flex items-start gap-4 mb-6">
                        @if($nutricionista->personalData?->profile_photo)
                            <div class="w-20 h-20 rounded-full overflow-hidden shadow-lg flex-shrink-0">
                                <img src="{{ asset('storage/' . $nutricionista->personalData->profile_photo) }}" 
                                     alt="{{ $nutricionista->name }}" 
                                     class="w-full h-full object-cover">
                            </div>
                        @else
                            <div class="w-20 h-20 bg-gradient-to-br from-green-500 to-emerald-500 rounded-full flex items-center justify-center text-white font-bold text-2xl flex-shrink-0 shadow-lg">
                                {{ $nutricionista->initials() }}
                            </div>
                        @endif
                        <div class="flex-1">
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-1">
                                {{ $nutricionista->name }}
                            </h2>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Nutricionista Profesional</p>
                        </div>
                    </div>

                    <div class="space-y-3 text-sm">
                        @if($nutricionista->email)
                            <div class="flex items-center gap-3 text-gray-700 dark:text-gray-300">
                                <span class="material-symbols-outlined text-green-600 dark:text-green-400">email</span>
                                <span>{{ $nutricionista->email }}</span>
                            </div>
                        @endif
                        @if($nutricionista->personalData && $nutricionista->personalData->phone)
                            <div class="flex items-center gap-3 text-gray-700 dark:text-gray-300">
                                <span class="material-symbols-outlined text-green-600 dark:text-green-400">call</span>
                                <span>{{ $nutricionista->personalData->phone }}</span>
                            </div>
                        @endif
                        <div class="flex items-center gap-3 text-gray-700 dark:text-gray-300">
                            <span class="material-symbols-outlined text-green-600 dark:text-green-400">schedule</span>
                            <span>Duración: 45 minutos</span>
                        </div>
                    </div>

                    <!-- Precio de Consulta -->
                    <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between p-4 bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 rounded-xl border border-green-200 dark:border-green-700">
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-green-600 dark:text-green-400">payments</span>
                                <span class="font-semibold text-gray-900 dark:text-white">Precio por consulta</span>
                            </div>
                            <span class="text-2xl font-bold text-green-600 dark:text-green-400">$30.00</span>
                        </div>
                    </div>
                </div>

                <!-- Formulario de Reserva -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 border border-gray-200 dark:border-gray-700 sticky top-24">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
                        <span class="material-symbols-outlined text-green-600">event_available</span>
                        Detalles de la Cita
                    </h3>
                    
                    <form method="POST" action="{{ route('paciente.booking.store', $nutricionista) }}" id="bookingForm" x-data="{ submitting: false }" @submit="submitting = true">
                        @csrf
                        
                        <input type="hidden" name="date" id="selectedDate">
                        <input type="hidden" name="time" id="selectedTime">

                        <!-- Horario Seleccionado -->
                        <div class="mb-6 p-4 bg-gradient-to-br from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 rounded-xl border-2 border-dashed border-green-300 dark:border-green-600">
                            <div id="selectionDisplay" class="text-center py-4">
                                <span class="material-symbols-outlined text-5xl text-green-400 dark:text-green-500 mb-3 block">event_note</span>
                                <p class="text-sm text-gray-600 dark:text-gray-400 font-medium">
                                    Selecciona una fecha y hora disponible
                                </p>
                            </div>
                            <div id="selectedInfo" class="hidden space-y-3">
                                <div class="flex items-center gap-3 p-3 bg-white dark:bg-gray-700 rounded-lg">
                                    <span class="material-symbols-outlined text-green-600 dark:text-green-400">calendar_today</span>
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Fecha</p>
                                        <p id="displayDate" class="font-bold text-gray-900 dark:text-white"></p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3 p-3 bg-white dark:bg-gray-700 rounded-lg">
                                    <span class="material-symbols-outlined text-green-600 dark:text-green-400">schedule</span>
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Hora</p>
                                        <p id="displayTime" class="font-bold text-gray-900 dark:text-white"></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tipo de Consulta -->
                        <div class="mb-4">
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                Tipo de Consulta <span class="text-red-500">*</span>
                            </label>
                            <select name="appointment_type" required
                                    class="w-full rounded-lg border-2 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-4 py-3 focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-200 dark:focus:ring-green-800 transition">
                                <option value="">Selecciona el tipo de consulta</option>
                                <option value="primera_vez">Primera vez</option>
                                <option value="seguimiento">Seguimiento</option>
                                <option value="control">Control</option>
                            </select>
                            @error('appointment_type')
                                <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                    <span class="material-symbols-outlined text-sm">error</span>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Motivo -->
                        <div class="mb-6">
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                Motivo de la consulta
                                <span class="text-gray-400 font-normal">(Opcional)</span>
                            </label>
                            <textarea name="reason" rows="4"
                                      class="w-full rounded-lg border-2 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-4 py-3 focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-200 dark:focus:ring-green-800 transition resize-none"
                                      placeholder="Ej: Control de peso, plan alimenticio personalizado, orientación nutricional..."></textarea>
                            @error('reason')
                                <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                    <span class="material-symbols-outlined text-sm">error</span>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Botón de Confirmar -->
                        <button type="submit" id="submitBtn" 
                                :disabled="submitting"
                                disabled
                                class="w-full rounded-xl bg-gradient-to-r from-green-600 to-emerald-600 px-6 py-4 font-bold text-white text-lg transition-all hover:from-green-700 hover:to-emerald-700 hover:shadow-xl disabled:from-gray-300 disabled:to-gray-400 disabled:cursor-not-allowed disabled:shadow-none flex items-center justify-center gap-2 group">
                            <span class="material-symbols-outlined group-disabled:animate-none" x-show="!submitting">check_circle</span>
                            <svg x-show="submitting" class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span id="btnText" x-text="submitting ? 'Confirmando...' : (document.getElementById('selectedDate').value ? 'Confirmar Cita' : 'Selecciona un horario')"></span>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Panel Derecho: Calendario -->
            <div class="lg:col-span-3">
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <!-- Header del Calendario -->
                    <div class="bg-gradient-to-r from-green-600 to-emerald-600 px-6 py-4">
                        <div class="flex items-center justify-between flex-wrap gap-4">
                            <div>
                                <h2 class="text-2xl font-bold text-white mb-1">Horarios Disponibles</h2>
                                <p class="text-green-100 text-sm">Selecciona el día y hora que prefieras</p>
                            </div>
                            <div class="flex items-center gap-3 bg-white/20 backdrop-blur-sm rounded-lg px-4 py-2">
                                <div class="flex items-center gap-2">
                                    <span class="w-3 h-3 rounded-full bg-green-400"></span>
                                    <span class="text-white text-sm font-medium">Disponible</span>
                                </div>
                                <div class="w-px h-4 bg-white/40"></div>
                                <div class="flex items-center gap-2">
                                    <span class="w-3 h-3 rounded-full bg-gray-400"></span>
                                    <span class="text-white text-sm font-medium">Ocupado</span>
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
                            
                            <div class="flex gap-2 overflow-x-auto pb-2 flex-1 mx-2 sm:mx-4 justify-center scrollbar-hide">
                                @foreach($weeks as $weekIndex => $week)
                                    <button onclick="showWeek({{ $weekIndex }})" 
                                            data-week="{{ $weekIndex }}"
                                            class="week-tab flex-shrink-0 px-3 sm:px-6 py-2 sm:py-3 rounded-lg font-semibold transition-all {{ $weekIndex === 0 ? 'bg-gradient-to-r from-green-600 to-emerald-600 text-white shadow-lg' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
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
                                                <span class="material-symbols-outlined text-green-600 text-lg sm:text-xl">calendar_today</span>
                                                <span>{{ $day['date_formatted'] }}</span>
                                            </h4>
                                            <span class="text-xs text-gray-500 dark:text-gray-400 pl-7 sm:pl-0">{{ count($day['slots']) }} horarios</span>
                                        </div>
                                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-2">
                                            @foreach($day['slots'] as $slot)
                                                <button
                                                    type="button"
                                                    onclick="selectSlot('{{ $day['date']->format('Y-m-d') }}', '{{ $slot['time'] }}', this)"
                                                    class="time-slot px-2 sm:px-3 py-2 sm:py-2.5 rounded-lg text-xs sm:text-sm font-medium transition-all duration-200 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-green-100 dark:hover:bg-green-900/30 hover:shadow-md hover:scale-105 flex items-center justify-center gap-1"
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

    <script>
        let selectedButton = null;
        let currentWeek = 0;
        const totalWeeks = {{ count($weeks) }};

        function selectSlot(date, time, button) {
            // Remover selección anterior
            if (selectedButton) {
                selectedButton.classList.remove('ring-4', 'ring-green-400', 'bg-green-600', 'text-white', 'shadow-lg', 'scale-105', '!bg-green-600');
                selectedButton.classList.add('bg-gray-100', 'dark:bg-gray-700', 'text-gray-700', 'dark:text-gray-300');
            }

            // Aplicar selección actual
            selectedButton = button;
            button.classList.remove('bg-gray-100', 'dark:bg-gray-700', 'text-gray-700', 'dark:text-gray-300');
            button.classList.add('bg-green-600', 'text-white', 'ring-4', 'ring-green-400', 'shadow-lg', 'scale-105', '!bg-green-600');

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
            
            // Scroll suave en móvil
            if (window.innerWidth < 1024) {
                document.getElementById('bookingForm').scrollIntoView({ behavior: 'smooth', block: 'center' });
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
                    tab.classList.add('bg-gradient-to-r', 'from-green-600', 'to-emerald-600', 'text-white', 'shadow-lg');
                } else {
                    tab.classList.remove('bg-gradient-to-r', 'from-green-600', 'to-emerald-600', 'text-white', 'shadow-lg');
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

    @include('layouts.footer')
</body>
@endsection
