@extends('layouts.app')

@section('title', 'Seleccionar Horario - NutriFit')

@section('content')
<body class="min-h-screen bg-gradient-to-br from-purple-50 via-white to-pink-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
    @include('layouts.header')

    <main class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center gap-3 mb-2">
                <a href="{{ route('paciente.booking.show') }}" class="text-gray-600 dark:text-gray-400 hover:text-purple-600 dark:hover:text-purple-400 transition-colors">
                    <span class="material-symbols-outlined text-2xl">arrow_back</span>
                </a>
                <h1 class="text-3xl font-bold bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent">
                    Agendar con {{ $nutricionista->name }}
                </h1>
            </div>
            <p class="text-gray-600 dark:text-gray-400 ml-11">
                Selecciona la fecha y horario que mejor te convenga
            </p>
        </div>

        @if(session('error'))
            <div class="mb-6 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-800 dark:text-red-200 px-4 py-3 rounded-lg flex items-center gap-3">
                <span class="material-symbols-outlined">error</span>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <div class="grid lg:grid-cols-3 gap-6">
            <!-- Calendario de disponibilidad -->
            <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-purple-600 to-pink-600 px-6 py-4">
                    <h2 class="text-xl font-semibold text-white flex items-center gap-2">
                        <span class="material-symbols-outlined">calendar_month</span>
                        Horarios Disponibles
                    </h2>
                </div>

                <div class="p-6">
                    @if($schedules->isEmpty())
                        <div class="text-center py-12">
                            <span class="material-symbols-outlined text-6xl text-gray-300 dark:text-gray-600">event_busy</span>
                            <p class="mt-4 text-gray-500 dark:text-gray-400">Este nutricionista no tiene horarios configurados</p>
                        </div>
                    @else
                        <!-- Próximas 4 semanas -->
                        @foreach($weeks as $weekIndex => $week)
                            <div class="mb-8 {{ $weekIndex > 0 ? 'border-t border-gray-200 dark:border-gray-700 pt-8' : '' }}">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                                    Semana {{ $weekIndex + 1 }}
                                </h3>

                                <div class="grid grid-cols-7 gap-2">
                                    @foreach($week as $day)
                                        @php
                                            $daySchedules = $schedules[$day['dayOfWeek']] ?? collect();
                                            $isToday = $day['date']->isToday();
                                            $isPast = $day['date']->isPast() && !$isToday;
                                        @endphp

                                        <div class="text-center">
                                            <!-- Nombre del día -->
                                            <div class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-2">
                                                {{ $day['date']->locale('es')->shortDayName }}
                                            </div>

                                            <!-- Fecha -->
                                            <div class="mb-2 p-2 rounded-lg 
                                                {{ $isToday ? 'bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 font-bold' : 'text-gray-700 dark:text-gray-300' }}
                                                {{ $isPast ? 'opacity-50' : '' }}">
                                                {{ $day['date']->format('d') }}
                                            </div>

                                            <!-- Botones de horarios -->
                                            @if($day['hasSchedule'] && !$isPast)
                                                <div class="space-y-1">
                                                    @foreach($daySchedules as $schedule)
                                                        @php
                                                            $timeSlots = $schedule->generateTimeSlots();
                                                        @endphp

                                                        @foreach($timeSlots as $slot)
                                                            @php
                                                                // Verificar si el slot ya pasó hoy
                                                                $slotDateTime = \Carbon\Carbon::parse($day['date']->format('Y-m-d') . ' ' . $slot['start']);
                                                                $isSlotPast = $slotDateTime->isPast();
                                                            @endphp

                                                            @if(!$isSlotPast)
                                                                <button 
                                                                    type="button"
                                                                    onclick="selectSlot('{{ $day['date']->format('Y-m-d') }}', '{{ $slot['start'] }}')"
                                                                    class="w-full text-xs px-2 py-1 rounded bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 hover:bg-green-200 dark:hover:bg-green-900/50 transition-colors">
                                                                    {{ \Carbon\Carbon::parse($slot['start'])->format('H:i') }}
                                                                </button>
                                                            @endif
                                                        @endforeach
                                                    @endforeach
                                                </div>
                                            @else
                                                <div class="text-xs text-gray-400 dark:text-gray-600">
                                                    {{ $isPast ? 'Pasado' : 'No disponible' }}
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>

            <!-- Formulario de reserva -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 h-fit sticky top-24">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-purple-600 dark:text-purple-400">assignment</span>
                    Detalles de la Cita
                </h2>

                <form method="POST" action="{{ route('paciente.booking.store', $nutricionista) }}" id="bookingForm">
                    @csrf

                    <input type="hidden" name="date" id="selectedDate">
                    <input type="hidden" name="time" id="selectedTime">

                    <!-- Fecha y hora seleccionada -->
                    <div class="mb-6 p-4 bg-purple-50 dark:bg-purple-900/20 rounded-lg">
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Fecha y hora</p>
                        <p id="selectedDisplay" class="text-lg font-semibold text-purple-600 dark:text-purple-400">
                            Selecciona un horario
                        </p>
                    </div>

                    <!-- Tipo de consulta -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Tipo de consulta *
                        </label>
                        <select name="appointment_type" required
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 dark:bg-gray-700 dark:text-white">
                            <option value="">Seleccionar...</option>
                            <option value="primera_vez">Primera vez</option>
                            <option value="seguimiento">Seguimiento</option>
                            <option value="control">Control</option>
                        </select>
                        @error('appointment_type')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Motivo -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Motivo de la consulta
                        </label>
                        <textarea name="reason" rows="3"
                                  class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 dark:bg-gray-700 dark:text-white"
                                  placeholder="Describe brevemente el motivo de tu consulta..."></textarea>
                        @error('reason')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Botón de agendar -->
                    <button type="submit" id="submitBtn" disabled
                            class="w-full bg-gradient-to-r from-purple-600 to-pink-600 text-white font-semibold py-3 rounded-lg hover:from-purple-700 hover:to-pink-700 transition-all disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined">event_available</span>
                        Confirmar Cita
                    </button>

                    <p class="mt-3 text-xs text-gray-500 dark:text-gray-400 text-center">
                        Tu cita será confirmada por el nutricionista
                    </p>
                </form>
            </div>
        </div>
    </main>

    @include('layouts.footer')

    <script>
        function selectSlot(date, time) {
            // Actualizar campos ocultos
            document.getElementById('selectedDate').value = date;
            document.getElementById('selectedTime').value = time;

            // Formatear fecha para mostrar
            const dateObj = new Date(date + 'T' + time);
            const options = { 
                weekday: 'long', 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            };
            const formatted = dateObj.toLocaleDateString('es-ES', options);

            // Actualizar display
            document.getElementById('selectedDisplay').textContent = formatted;

            // Habilitar botón de submit
            document.getElementById('submitBtn').disabled = false;

            // Quitar selección anterior y marcar nueva
            document.querySelectorAll('.slot-selected').forEach(el => {
                el.classList.remove('slot-selected', 'ring-2', 'ring-purple-500');
            });

            event.target.classList.add('slot-selected', 'ring-2', 'ring-purple-500');
        }
    </script>
</body>
@endsection
