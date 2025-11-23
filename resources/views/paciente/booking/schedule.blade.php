@extends('layouts.app')

@section('title', 'Seleccionar Horario - NutriFit')

@section('content')
    @include('layouts.header')

    <main class="container mx-auto px-4 py-8">
        <div class="mb-8">
            <div class="flex items-center gap-3 mb-2">
                <a href="{{ route('paciente.booking.index') }}" class="text-gray-600 dark:text-gray-400 hover:text-purple-600 dark:hover:text-purple-400 transition-colors">
                    <span class="material-symbols-outlined text-2xl">arrow_back</span>
                </a>
                <h1 class="text-3xl font-bold bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent">
                    Horarios de {{ $nutricionista->name }}
                </h1>
            </div>
            <p class="text-gray-600 dark:text-gray-400 ml-11">
                Selecciona la fecha y hora que mejor se ajuste a tu horario
            </p>
        </div>

        @if(session('error'))
            <div class="mb-6 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-800 dark:text-red-200 px-4 py-3 rounded-lg flex items-center gap-3">
                <span class="material-symbols-outlined">error</span>
                <span>{{ session('error') }}</span>
            </div>
        @endif

            <div class="grid lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6">
                        <div class="mb-6 flex items-center justify-between">
                            <h2 class="text-xl font-bold text-gray-900 dark:text-white">
                                Próximas 4 semanas
                            </h2>
                            <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                                <span class="w-3 h-3 rounded-full bg-green-500"></span> Disponible
                                <span class="w-3 h-3 rounded-full bg-gray-300 dark:bg-gray-600 ml-3"></span> No disponible
                            </div>
                        </div>

                        @foreach($weeks as $weekIndex => $week)
                            <div class="mb-8 last:mb-0">
                                <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3 flex items-center gap-2">
                                    <span class="material-symbols-outlined text-purple-600 dark:text-purple-400">calendar_view_week</span>
                                    Semana {{ $weekIndex + 1 }}
                                </h3>
                                <div class="grid grid-cols-7 gap-2">
                                    @foreach($week as $day)
                                        <div class="text-center">
                                            <div class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-2">
                                                {{ $day['date']->locale('es')->isoFormat('ddd') }}
                                            </div>
                                            <div class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                                {{ $day['date']->format('d') }}
                                            </div>
                                            
                                            @if($day['isPast'])
                                                <div class="text-xs text-gray-400 dark:text-gray-600">
                                                    Pasado
                                                </div>
                                            @elseif(!isset($schedules[$day['dayOfWeek']]) || $schedules[$day['dayOfWeek']]->isEmpty())
                                                <div class="text-xs text-gray-400 dark:text-gray-600">
                                                    Sin horarios
                                                </div>
                                            @else
                                                <div class="space-y-1">
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
                                                                    onclick="selectSlot('{{ $day['date']->format('Y-m-d') }}', '{{ $slotData['time'] }}')"
                                                                    {{ !$slotData['available'] ? 'disabled' : '' }}
                                                                    class="w-full px-2 py-1 text-xs font-medium rounded transition {{ $slotData['available'] ? 'bg-green-500 text-white hover:bg-green-600' : 'bg-gray-200 dark:bg-gray-700 text-gray-400 dark:text-gray-500 cursor-not-allowed' }}">
                                                                {{ $slotData['time'] }}
                                                            </button>
                                                        @endforeach
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="lg:col-span-1">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 sticky top-24">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-6">Confirmar Cita</h2>
                        
                        <form method="POST" action="{{ route('paciente.booking.store', $nutricionista) }}" id="bookingForm">
                            @csrf
                            
                            <input type="hidden" name="date" id="selectedDate">
                            <input type="hidden" name="time" id="selectedTime">

                            <div class="mb-6 p-4 bg-purple-50 dark:bg-purple-900/20 rounded-lg border-2 border-purple-200 dark:border-purple-700">
                                <div id="selectionDisplay" class="text-center">
                                    <span class="material-symbols-outlined text-4xl text-purple-600 dark:text-purple-400 mb-2">schedule</span>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        Selecciona un horario del calendario
                                    </p>
                                </div>
                                <div id="selectedInfo" class="hidden">
                                    <div class="flex items-center gap-2 mb-2">
                                        <span class="material-symbols-outlined text-purple-600 dark:text-purple-400">calendar_today</span>
                                        <span id="displayDate" class="font-semibold text-gray-900 dark:text-white"></span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="material-symbols-outlined text-purple-600 dark:text-purple-400">schedule</span>
                                        <span id="displayTime" class="font-semibold text-gray-900 dark:text-white"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Tipo de Consulta *
                                </label>
                                <select name="appointment_type" required
                                        class="w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-4 py-2.5 focus:border-purple-500 focus:outline-none focus:ring-2 focus:ring-purple-200">
                                    <option value="">Selecciona un tipo</option>
                                    <option value="primera_vez">Primera vez</option>
                                    <option value="seguimiento">Seguimiento</option>
                                    <option value="control">Control</option>
                                </select>
                                @error('appointment_type')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Motivo de la consulta (opcional)
                                </label>
                                <textarea name="reason" rows="3"
                                          class="w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-4 py-2.5 focus:border-purple-500 focus:outline-none focus:ring-2 focus:ring-purple-200"
                                          placeholder="Describe brevemente el motivo de tu consulta..."></textarea>
                                @error('reason')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <button type="submit" id="submitBtn" disabled
                                    class="w-full rounded-lg bg-gradient-to-r from-purple-600 to-pink-600 px-4 py-3 font-semibold text-white transition hover:from-purple-700 hover:to-pink-700 disabled:from-gray-300 disabled:to-gray-400 disabled:cursor-not-allowed flex items-center justify-center gap-2">
                                <span class="material-symbols-outlined">check_circle</span>
                                Confirmar Cita
                            </button>
                        </form>
                    </div>
                </div>
            </div>
    </main>

    @include('layouts.footer')

    <script>
        function selectSlot(date, time) {
            document.getElementById('selectedDate').value = date;
            document.getElementById('selectedTime').value = time;
            
            const dateObj = new Date(date + 'T00:00:00');
            const months = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
            const formattedDate = dateObj.getDate() + ' de ' + months[dateObj.getMonth()] + ', ' + dateObj.getFullYear();
            
            document.getElementById('selectionDisplay').classList.add('hidden');
            document.getElementById('selectedInfo').classList.remove('hidden');
            document.getElementById('displayDate').textContent = formattedDate;
            document.getElementById('displayTime').textContent = time;
            
            document.getElementById('submitBtn').disabled = false;
            
            if (window.innerWidth < 1024) {
                document.getElementById('bookingForm').scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }
    </script>
@endsection
