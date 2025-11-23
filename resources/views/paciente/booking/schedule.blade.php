@extends('layouts.app')

@section('title', 'Seleccionar Horario - NutriFit')

@section('content')
<body class="min-h-screen bg-gradient-to-br from-purple-50 via-pink-50 to-rose-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 flex flex-col">
    @include('layouts.header')

    <main class="container mx-auto px-4 py-8 flex-grow">
        <!-- Breadcrumb -->
        <nav class="mb-6 flex items-center gap-2 text-sm">
            <a href="{{ route('paciente.dashboard') }}" class="text-purple-600 dark:text-purple-400 hover:underline">Dashboard</a>
            <span class="material-symbols-outlined text-gray-400 text-sm">chevron_right</span>
            <a href="{{ route('paciente.booking.index') }}" class="text-purple-600 dark:text-purple-400 hover:underline">Nutricionistas</a>
            <span class="material-symbols-outlined text-gray-400 text-sm">chevron_right</span>
            <span class="text-gray-600 dark:text-gray-400">Agendar cita</span>
        </nav>

        @if(session('error'))
            <div class="mb-6 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-800 dark:text-red-200 px-4 py-3 rounded-lg flex items-center gap-3">
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
                        <div class="w-20 h-20 bg-gradient-to-br from-purple-500 to-pink-500 rounded-full flex items-center justify-center text-white font-bold text-2xl flex-shrink-0">
                            {{ substr($nutricionista->name, 0, 1) }}
                        </div>
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
                                <span class="material-symbols-outlined text-purple-600 dark:text-purple-400">email</span>
                                <span>{{ $nutricionista->email }}</span>
                            </div>
                        @endif
                        @if($nutricionista->personalData && $nutricionista->personalData->phone)
                            <div class="flex items-center gap-3 text-gray-700 dark:text-gray-300">
                                <span class="material-symbols-outlined text-purple-600 dark:text-purple-400">call</span>
                                <span>{{ $nutricionista->personalData->phone }}</span>
                            </div>
                        @endif
                        <div class="flex items-center gap-3 text-gray-700 dark:text-gray-300">
                            <span class="material-symbols-outlined text-purple-600 dark:text-purple-400">schedule</span>
                            <span>Duración: 45 minutos</span>
                        </div>
                    </div>
                </div>

                <!-- Formulario de Reserva -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 border border-gray-200 dark:border-gray-700 sticky top-24">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
                        <span class="material-symbols-outlined text-purple-600">event_available</span>
                        Detalles de la Cita
                    </h3>
                    
                    <form method="POST" action="{{ route('paciente.booking.store', $nutricionista) }}" id="bookingForm">
                        @csrf
                        
                        <input type="hidden" name="date" id="selectedDate">
                        <input type="hidden" name="time" id="selectedTime">

                        <!-- Horario Seleccionado -->
                        <div class="mb-6 p-4 bg-gradient-to-br from-purple-50 to-pink-50 dark:from-purple-900/20 dark:to-pink-900/20 rounded-xl border-2 border-dashed border-purple-300 dark:border-purple-600">
                            <div id="selectionDisplay" class="text-center py-4">
                                <span class="material-symbols-outlined text-5xl text-purple-400 dark:text-purple-500 mb-3 block">event_note</span>
                                <p class="text-sm text-gray-600 dark:text-gray-400 font-medium">
                                    Selecciona una fecha y hora disponible
                                </p>
                            </div>
                            <div id="selectedInfo" class="hidden space-y-3">
                                <div class="flex items-center gap-3 p-3 bg-white dark:bg-gray-700 rounded-lg">
                                    <span class="material-symbols-outlined text-purple-600 dark:text-purple-400">calendar_today</span>
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Fecha</p>
                                        <p id="displayDate" class="font-bold text-gray-900 dark:text-white"></p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3 p-3 bg-white dark:bg-gray-700 rounded-lg">
                                    <span class="material-symbols-outlined text-purple-600 dark:text-purple-400">schedule</span>
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
                                    class="w-full rounded-lg border-2 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-4 py-3 focus:border-purple-500 focus:outline-none focus:ring-2 focus:ring-purple-200 dark:focus:ring-purple-800 transition">
                                <option value="">Selecciona el tipo de consulta</option>
                                <option value="primera_vez">🆕 Primera vez</option>
                                <option value="seguimiento">📊 Seguimiento</option>
                                <option value="control">✅ Control</option>
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
                                      class="w-full rounded-lg border-2 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-4 py-3 focus:border-purple-500 focus:outline-none focus:ring-2 focus:ring-purple-200 dark:focus:ring-purple-800 transition resize-none"
                                      placeholder="Ej: Control de peso, plan alimenticio personalizado, orientación nutricional..."></textarea>
                            @error('reason')
                                <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                    <span class="material-symbols-outlined text-sm">error</span>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Botón de Confirmar -->
                        <button type="submit" id="submitBtn" disabled
                                class="w-full rounded-xl bg-gradient-to-r from-purple-600 to-pink-600 px-6 py-4 font-bold text-white text-lg transition-all hover:from-purple-700 hover:to-pink-700 hover:shadow-xl disabled:from-gray-300 disabled:to-gray-400 disabled:cursor-not-allowed disabled:shadow-none flex items-center justify-center gap-2 group">
                            <span class="material-symbols-outlined group-disabled:animate-none">check_circle</span>
                            <span id="btnText">Selecciona un horario</span>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Panel Derecho: Calendario -->
            <div class="lg:col-span-3">
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <!-- Header del Calendario -->
                    <div class="bg-gradient-to-r from-purple-600 to-pink-600 px-6 py-4">
                        <div class="flex items-center justify-between flex-wrap gap-4">
                            <div>
                                <h2 class="text-2xl font-bold text-white mb-1">Horarios Disponibles</h2>
                                <p class="text-purple-100 text-sm">Selecciona el día y hora que prefieras</p>
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
                        <div class="flex items-center justify-between px-6 py-3">
                            <button onclick="changeWeek(-1)" id="prevWeek" class="flex items-center gap-2 px-4 py-2 text-sm font-semibold text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700 rounded-lg transition disabled:opacity-50 disabled:cursor-not-allowed">
                                <span class="material-symbols-outlined">chevron_left</span>
                                Anterior
                            </button>
                            
                            <div class="flex gap-2 overflow-x-auto pb-2 flex-1 mx-4 justify-center">
                                @foreach($weeks as $weekIndex => $week)
                                    <button onclick="showWeek({{ $weekIndex }})" 
                                            data-week="{{ $weekIndex }}"
                                            class="week-tab flex-shrink-0 px-6 py-3 rounded-lg font-semibold transition-all {{ $weekIndex === 0 ? 'bg-gradient-to-r from-purple-600 to-pink-600 text-white shadow-lg' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                        <div class="text-xs opacity-90 mb-1">Semana {{ $weekIndex + 1 }}</div>
                                        <div class="text-sm font-bold">{{ $week[0]['date']->locale('es')->isoFormat('D MMM') }}</div>
                                    </button>
                                @endforeach
                            </div>

                            <button onclick="changeWeek(1)" id="nextWeek" class="flex items-center gap-2 px-4 py-2 text-sm font-semibold text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700 rounded-lg transition disabled:opacity-50 disabled:cursor-not-allowed">
                                Siguiente
                                <span class="material-symbols-outlined">chevron_right</span>
                            </button>
                        </div>
                    </div>

                    <!-- Contenido del Calendario -->
                    <div class="p-6">
                        @foreach($weeks as $weekIndex => $week)
                            <div id="week-{{ $weekIndex }}" class="week-content {{ $weekIndex !== 0 ? 'hidden' : '' }}">
                                <!-- Header de la Semana -->
                                <div class="mb-6 text-center">
                                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-1">
                                        {{ $week[0]['date']->locale('es')->isoFormat('MMMM YYYY') }}
                                    </h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        Del {{ $week[0]['date']->locale('es')->isoFormat('D') }} al {{ end($week)['date']->locale('es')->isoFormat('D [de] MMMM') }}
                                    </p>
                                </div>

                                <!-- Grid de Días -->
                                <div class="grid grid-cols-7 gap-3">
                                    @foreach($week as $day)
                                        <div class="flex flex-col">
                                            <!-- Encabezado del Día -->
                                            <div class="text-center mb-3 pb-2 border-b-2 {{ $day['date']->isToday() ? 'border-purple-500' : 'border-gray-200 dark:border-gray-700' }}">
                                                <div class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase mb-1">
                                                    {{ $day['date']->locale('es')->isoFormat('ddd') }}
                                                </div>
                                                <div class="text-lg font-bold {{ $day['date']->isToday() ? 'text-purple-600 dark:text-purple-400' : 'text-gray-900 dark:text-white' }}">
                                                    {{ $day['date']->format('d') }}
                                                </div>
                                                @if($day['date']->isToday())
                                                    <div class="text-xs text-purple-600 dark:text-purple-400 font-semibold mt-1">Hoy</div>
                                                @endif
                                            </div>
                                            
                                            <!-- Slots de Horarios -->
                                            <div class="space-y-2">
                                                @if($day['isPast'])
                                                    <div class="text-center py-6">
                                                        <span class="material-symbols-outlined text-gray-300 dark:text-gray-700 text-3xl">schedule_send</span>
                                                        <p class="text-xs text-gray-400 dark:text-gray-600 mt-1">Pasado</p>
                                                    </div>
                                                @elseif(!isset($schedules[$day['dayOfWeek']]) || $schedules[$day['dayOfWeek']]->isEmpty())
                                                    <div class="text-center py-6">
                                                        <span class="material-symbols-outlined text-gray-300 dark:text-gray-700 text-3xl">event_busy</span>
                                                        <p class="text-xs text-gray-400 dark:text-gray-600 mt-1">Sin horarios</p>
                                                    </div>
                                                @else
                                                    @foreach($schedules[$day['dayOfWeek']] as $schedule)
                                                        @php
                                                            $startTime = \Carbon\Carbon::parse($schedule->start_time);
                                                            $endTime = \Carbon\Carbon::parse($schedule->end_time);
                                                            $currentTime = $startTime->copy();
                                                            $slots = [];
                                                            
                                                            while ($currentTime->lt($endTime)) {
                                                                $slot = $currentTime->format('H:i');
                                                                $isAvailable = $schedule->isTimeSlotAvailable($day['date']->format('Y-m-d'), $slot);
                                                                $slots[] = ['time' => $slot, 'available' => $isAvailable];
                                                                $currentTime->addMinutes(45);
                                                            }
                                                        @endphp
                                                        
                                                        @foreach($slots as $slotData)
                                                            <button type="button"
                                                                    onclick="selectSlot('{{ $day['date']->format('Y-m-d') }}', '{{ $slotData['time'] }}', this)"
                                                                    {{ !$slotData['available'] ? 'disabled' : '' }}
                                                                    class="time-slot w-full px-3 py-2.5 text-sm font-semibold rounded-lg transition-all duration-200 {{ $slotData['available'] ? 'bg-green-500 text-white hover:bg-green-600 hover:shadow-lg hover:scale-105 active:scale-95' : 'bg-gray-200 dark:bg-gray-700 text-gray-400 dark:text-gray-500 cursor-not-allowed opacity-50' }}">
                                                                <div class="flex items-center justify-center gap-1">
                                                                    <span class="material-symbols-outlined text-xs">schedule</span>
                                                                    <span>{{ $slotData['time'] }}</span>
                                                                </div>
                                                            </button>
                                                        @endforeach
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
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
                selectedButton.classList.remove('ring-4', 'ring-purple-400', 'bg-purple-600');
                selectedButton.classList.add('bg-green-500');
            }

            // Aplicar selección actual
            selectedButton = button;
            button.classList.remove('bg-green-500');
            button.classList.add('bg-purple-600', 'ring-4', 'ring-purple-400');

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
            document.getElementById('btnText').textContent = 'Confirmar Cita';
            
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
                    tab.classList.add('bg-gradient-to-r', 'from-purple-600', 'to-pink-600', 'text-white', 'shadow-lg');
                } else {
                    tab.classList.remove('bg-gradient-to-r', 'from-purple-600', 'to-pink-600', 'text-white', 'shadow-lg');
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
