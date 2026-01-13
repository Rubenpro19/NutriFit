@extends('layouts.app')

@section('title', 'Gestión de Horarios - NutriFit')

@section('content')
<body class="min-h-screen bg-gradient-to-br from-green-50 via-emerald-50 to-rose-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 flex flex-col">
    @include('layouts.header')

    <main class="flex-grow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
            <!-- Breadcrumb -->
            <nav class="mb-6 flex items-center gap-2 text-sm">
                <a href="{{ route('nutricionista.dashboard') }}" class="text-green-600 dark:text-green-400 hover:underline transition-colors">Inicio</a>
                <span class="material-symbols-outlined text-gray-400 text-sm">chevron_right</span>
                <span class="text-gray-700 dark:text-gray-300 font-medium">Gestión de Horarios</span>
            </nav>

            <!-- Header -->
            <div class="mb-8 bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-4 sm:p-6 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between gap-3">
                    <div class="flex items-center gap-3 flex-1 min-w-0">
                        <a href="{{ route('nutricionista.dashboard') }}" class="text-gray-700 dark:text-gray-300 hover:text-green-600 dark:hover:text-green-400 hover:scale-110 transition-all">
                            <span class="material-symbols-outlined text-2xl">arrow_back</span>
                        </a>
                        <div class="min-w-0 flex-1">
                            <h1 class="text-2xl sm:text-3xl font-bold bg-gradient-to-r from-green-600 to-emerald-600 dark:from-green-400 dark:to-emerald-400 bg-clip-text text-transparent">
                                Gestor de horarios
                            </h1>
                            <p class="text-sm sm:text-base text-gray-700 dark:text-gray-300 mt-1 truncate">
                                Configura tu disponibilidad semanal. Los pacientes podrán agendar citas en los horarios que marques como disponibles.
                            </p>
                        </div>
                    </div>
                    <div class="flex-shrink-0 hidden sm:flex items-center">
                        <span class="material-symbols-outlined text-3xl sm:text-4xl text-green-600 dark:text-green-400">calendar_today</span>
                    </div>
                </div>
            </div>

            <!-- Toast de Éxito -->
            @if(session('success'))
            <div x-data="{ show: true }" 
                 x-init="setTimeout(() => show = false, 5000)"
                 x-show="show"
                 x-cloak
                 class="fixed top-20 right-4 z-50 max-w-md w-full sm:w-96"
                 style="display: none;">
                <div x-transition:enter="transition ease-out duration-300 transform"
                     x-transition:enter-start="translate-x-full opacity-0"
                     x-transition:enter-end="translate-x-0 opacity-100"
                     x-transition:leave="transition ease-in duration-200 transform"
                     x-transition:leave-start="translate-x-0 opacity-100"
                     x-transition:leave-end="translate-x-full opacity-0"
                     class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl border-l-4 border-green-500 overflow-hidden">
                    
                    
                    <div class="p-4">
                        <div class="flex items-start gap-3">
                            <!-- Icono -->
                            <div class="flex-shrink-0">
                                <div class="bg-green-100 dark:bg-green-900/30 rounded-full p-2">
                                    <span class="material-symbols-outlined text-green-600 dark:text-green-400 text-2xl">check_circle</span>
                                </div>
                            </div>
                            
                            <!-- Contenido -->
                            <div class="flex-1 min-w-0">
                                <h4 class="text-sm font-bold text-gray-900 dark:text-white mb-1">
                                    ¡Horarios Guardados con Éxito!
                                </h4>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                                    {{ session('success') }}
                                </p>
                                
                                <!-- Resumen -->
                                @if(session('slots_count'))
                                <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-2 mb-3 border border-green-200 dark:border-green-700">
                                    <div class="flex items-center gap-2 text-xs text-gray-700 dark:text-gray-300">
                                        <span class="material-symbols-outlined text-green-600 dark:text-green-400 text-sm">schedule</span>
                                        <span class="font-semibold">{{ session('slots_count') }} horarios configurados</span>
                                    </div>
                                </div>
                                @endif
                                
                                <!-- Botón Asignar Cita -->
                                <a href="{{ route('nutricionista.appointments.create') }}"
                                   class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white text-sm font-semibold rounded-lg hover:bg-green-700 transition-all duration-200 shadow-sm hover:shadow-md">
                                    <span class="material-symbols-outlined text-base">event_available</span>
                                    Asignar Cita
                                </a>
                            </div>
                            
                            <!-- Botón Cerrar -->
                            <button @click="show = false"
                                    class="flex-shrink-0 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors">
                                <span class="material-symbols-outlined">close</span>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Barra de progreso horizontal -->
                    <div class="h-1 bg-gray-200 dark:bg-gray-700">
                        <div class="h-full bg-green-500 transition-all duration-100" style="width: 100%; animation: shrink 5s linear forwards;"></div>
                    </div>
                </div>
            </div>

            <style>
                @keyframes shrink {
                    from { width: 100%; }
                    to { width: 0%; }
                }
            </style>
            @endif

            <!-- Formulario -->
            <form id="scheduleForm" method="POST" action="{{ route('nutricionista.schedules.save') }}" x-data="{ submitting: false }" @submit="submitting = true">
                @csrf

                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                    <!-- Header de la tabla -->
                    <div class="bg-gradient-to-r from-green-600 to-emerald-600 px-6 py-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3 text-white">
                                <span class="material-symbols-outlined text-3xl">calendar_month</span>
                                <div>
                                    <h2 class="text-xl font-semibold">Horarios semanales</h2>
                                    <p class="text-green-50 text-sm">Duración de consulta: {{ $consultationDuration }} minutos</p>
                                </div>
                            </div>
                            <button type="submit" 
                                    :disabled="submitting"
                                    class="bg-white text-green-600 px-6 py-3 rounded-lg font-bold hover:bg-green-50 shadow-md hover:shadow-lg hover:scale-105 active:scale-95 transition-all duration-200 flex items-center gap-2 disabled:opacity-60 disabled:cursor-not-allowed disabled:hover:scale-100">
                                <svg x-show="submitting" x-cloak class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span x-show="!submitting" class="material-symbols-outlined">save</span>
                                <span x-text="submitting ? 'Guardando...' : 'Guardar horarios'"></span>
                            </button>
                        </div>
                    </div>

                    <!-- Acciones rápidas -->
                    <div class="bg-gray-50 dark:bg-gray-700/50 px-6 py-4 border-b border-gray-200 dark:border-gray-600">
                        <div class="flex flex-wrap items-center gap-4">
                            <span class="text-sm font-medium text-gray-800 dark:text-gray-200">Acciones rápidas:</span>
                            @foreach($daysOfWeek as $dayNumber => $dayName)
                                <button 
                                    type="button" 
                                    onclick="toggleDay({{ $dayNumber }})"
                                    class="text-sm px-4 py-2 rounded-lg border-2 border-gray-300 dark:border-gray-600 text-gray-800 dark:text-gray-200 font-medium hover:bg-green-500 hover:border-green-500 hover:text-white hover:shadow-md hover:scale-105 active:scale-95 dark:hover:bg-green-600 dark:hover:border-green-600 transition-all duration-200"
                                >
                                    Marcar {{ $dayName }}
                                </button>
                            @endforeach
                        </div>
                        <div class="mt-4 flex justify-end">
                            <button 
                                type="button" 
                                onclick="document.getElementById('clearAllModal').classList.remove('hidden'); document.getElementById('clearAllModal').style.display='block';"
                                class="text-sm px-4 py-2 rounded-lg border-2 border-red-400 dark:border-red-500 text-red-600 dark:text-red-400 font-medium hover:bg-red-600 hover:border-red-600 hover:text-white hover:shadow-md hover:scale-105 active:scale-95 dark:hover:bg-red-600 dark:hover:border-red-600 dark:hover:text-white transition-all duration-200"
                            >
                                Limpiar todo
                            </button>
                        </div>
                    </div>

                    <!-- Tabla de horarios -->
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 dark:text-gray-200 uppercase tracking-wider">
                                        Día/Hora
                                    </th>
                                    @foreach($daysOfWeek as $dayNumber => $dayName)
                                        <th class="px-6 py-3 text-center text-xs font-bold text-gray-700 dark:text-gray-200 uppercase tracking-wider">
                                            {{ $dayName }}
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @php
                                    // Generar slots de tiempo desde las 08:00 hasta las 18:00
                                    // Cada slot es de 45 minutos (duración de la consulta)
                                    $startHour = 8;
                                    $endHour = 18;
                                    $timeSlots = [];
                                    
                                    $currentTime = \Carbon\Carbon::createFromTime($startHour, 0);
                                    $endTime = \Carbon\Carbon::createFromTime($endHour, 0);
                                    
                                    while ($currentTime->lt($endTime)) {
                                        $timeSlots[] = $currentTime->format('H:i');
                                        $currentTime->addMinutes(45);
                                    }
                                @endphp

                                @foreach($timeSlots as $timeSlot)
                                    @php
                                        $slotEnd = \Carbon\Carbon::parse($timeSlot)->addMinutes(45)->format('H:i');
                                    @endphp
                                    
                                    <tr class="hover:bg-green-50 dark:hover:bg-gray-700 transition-colors duration-150">
                                        <td class="px-6 py-3 whitespace-nowrap text-sm font-semibold text-gray-900 dark:text-white">
                                            {{ $timeSlot }} - {{ $slotEnd }}
                                        </td>
                                        @foreach($daysOfWeek as $dayNumber => $dayName)
                                            @php
                                                // Verificar si este slot ya está seleccionado
                                                $isChecked = false;
                                                if (isset($schedules[$dayNumber])) {
                                                    foreach ($schedules[$dayNumber] as $schedule) {
                                                        $slots = $schedule->generateTimeSlots();
                                                        foreach ($slots as $slot) {
                                                            if ($slot['start'] === $timeSlot) {
                                                                $isChecked = true;
                                                                break 2;
                                                            }
                                                        }
                                                    }
                                                }
                                            @endphp
                                            
                                            <td class="px-6 py-3 whitespace-nowrap text-center">
                                                <label class="inline-flex items-center justify-center cursor-pointer group">
                                                    <input 
                                                        type="checkbox" 
                                                        name="time_slots[]" 
                                                        value="{{ $dayNumber }}_{{ $timeSlot }}"
                                                        class="w-6 h-6 text-green-600 bg-gray-100 dark:bg-gray-600 border-2 border-gray-400 dark:border-gray-500 rounded focus:ring-2 focus:ring-green-500 dark:focus:ring-green-400 dark:ring-offset-gray-800 cursor-pointer day-{{ $dayNumber }}-checkbox hover:border-green-500 hover:scale-110 dark:hover:border-green-400 transition-all duration-200 checked:border-green-600 dark:checked:border-green-500"
                                                        {{ $isChecked ? 'checked' : '' }}
                                                    >
                                                </label>
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </form>

            <!-- Info adicional -->
            <div class="mt-6 bg-blue-50 dark:bg-blue-900/20 border-2 border-blue-300 dark:border-blue-700 rounded-lg px-4 py-3">
                    <div class="flex gap-3">
                    <span class="material-symbols-outlined text-blue-600 dark:text-blue-400">info</span>
                    <div class="text-sm text-blue-900 dark:text-blue-100">
                        <p class="font-semibold mb-1">Información importante:</p>
                        <ul class="list-disc list-inside space-y-1 text-blue-800 dark:text-blue-200">
                            <li>Cada casilla representa un intervalo de {{ $consultationDuration }} minutos</li>
                            <li>Las consultas tienen una duración de {{ $consultationDuration }} minutos</li>
                            <li>Los pacientes podrán agendar citas en los horarios que marques</li>
                            <li>Puedes modificar tu disponibilidad en cualquier momento</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Modal de confirmación para limpiar todo -->
    <div id="clearAllModal" class="hidden fixed inset-0 z-[9999] overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4">
            <!-- Overlay semitransparente -->
            <div class="fixed inset-0 bg-black/30 backdrop-blur-sm transition-opacity" 
                 onclick="document.getElementById('clearAllModal').classList.add('hidden'); document.getElementById('clearAllModal').style.display='none';"></div>
            
            <!-- Modal -->
            <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full z-10 p-6">
                <div class="text-center mb-6">
                    <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 dark:bg-red-900/30 mb-4">
                        <span class="material-symbols-outlined text-4xl text-red-600 dark:text-red-400">warning</span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">
                        ¿Limpiar todos los horarios?
                    </h3>
                    <p class="text-gray-600 dark:text-gray-400">
                        Esta acción desmarcará todos los horarios seleccionados. Tendrás que seleccionarlos nuevamente o guardar el formulario vacío.
                    </p>
                </div>

                <div class="flex gap-3">
                    <button 
                        onclick="document.getElementById('clearAllModal').classList.add('hidden'); document.getElementById('clearAllModal').style.display='none';" 
                        class="flex-1 px-4 py-3 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 font-semibold rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition"
                    >
                        No, mantener
                    </button>
                    <button 
                        onclick="clearAll()" 
                        class="flex-1 px-4 py-3 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition"
                    >
                        Sí, limpiar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Función para marcar/desmarcar todos los checkboxes de un día
        function toggleDay(dayNumber) {
            const checkboxes = document.querySelectorAll(`.day-${dayNumber}-checkbox`);
            
            // Contar cuántos están marcados
            let checkedCount = 0;
            checkboxes.forEach(cb => {
                if (cb.checked) checkedCount++;
            });
            
            // Si todos o la mayoría están marcados, desmarcar todos
            // Si ninguno o pocos están marcados, marcar todos
            const shouldCheck = checkedCount < checkboxes.length / 2;
            
            checkboxes.forEach(checkbox => {
                checkbox.checked = shouldCheck;
            });
        }

        // Función para limpiar todos los checkboxes
        function clearAll() {
            document.querySelectorAll('input[type="checkbox"][name="time_slots[]"]').forEach(checkbox => {
                checkbox.checked = false;
            });
            document.getElementById('clearAllModal').classList.add('hidden');
            document.getElementById('clearAllModal').style.display='none';
        }
    </script>
        </div>
    </main>

    @include('layouts.footer')
</body>
@endsection
