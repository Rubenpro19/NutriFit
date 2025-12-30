@extends('layouts.app')

@section('title', 'Gestión de Horarios - NutriFit')

@section('content')
<body class="bg-gradient-to-br from-green-50 via-emerald-50 to-teal-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
    @include('layouts.header')

    <main class="pt-8 pb-16 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <!-- Breadcrumb -->
            <nav class="mb-6 text-sm text-gray-600 dark:text-gray-400">
                <a href="{{ route('nutricionista.dashboard') }}" class="hover:text-green-600 dark:hover:text-green-400">Dashboard</a>
                <span class="mx-2">/</span>
                <span class="text-gray-900 dark:text-white">Gestión de Horarios</span>
            </nav>

            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center gap-3 mb-2">
                    <a href="{{ route('nutricionista.dashboard') }}" class="text-gray-600 dark:text-gray-400 hover:text-green-600 dark:hover:text-green-400 transition-colors">
                        <span class="material-symbols-outlined text-2xl">arrow_back</span>
                    </a>
                    <h1 class="text-3xl font-bold bg-gradient-to-r from-green-600 to-emerald-600 bg-clip-text text-transparent">
                        Gestor de horarios
                    </h1>
                </div>
                <p class="text-gray-600 dark:text-gray-400 ml-11">
                    Configura tu disponibilidad semanal. Los pacientes podrán agendar citas en los horarios que marques como disponibles.
                </p>
            </div>

            @if(session('success'))
                <div class="mb-6 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-800 dark:text-green-200 px-4 py-3 rounded-lg flex items-center gap-3">
                    <span class="material-symbols-outlined">check_circle</span>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <!-- Formulario -->
            <form id="scheduleForm" method="POST" action="{{ route('nutricionista.schedules.save') }}">
                @csrf

                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                    <!-- Header de la tabla -->
                    <div class="bg-gradient-to-r from-green-600 to-emerald-600 px-6 py-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3 text-white">
                                <span class="material-symbols-outlined text-3xl">calendar_month</span>
                                <div>
                                    <h2 class="text-xl font-semibold">Horarios semanales</h2>
                                    <p class="text-green-100 text-sm">Duración de consulta: {{ $consultationDuration }} minutos</p>
                                </div>
                            </div>
                            <button type="submit" class="bg-white text-green-600 px-6 py-2 rounded-lg font-medium hover:bg-green-50 transition-colors flex items-center gap-2">
                                <span class="material-symbols-outlined">save</span>
                                Guardar horario
                            </button>
                        </div>
                    </div>

                    <!-- Acciones rápidas -->
                    <div class="bg-gray-50 dark:bg-gray-700 px-6 py-4 border-b border-gray-200 dark:border-gray-600">
                        <div class="flex flex-wrap items-center gap-4">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Acciones rápidas:</span>
                            @foreach($daysOfWeek as $dayNumber => $dayName)
                                <button 
                                    type="button" 
                                    onclick="toggleDay({{ $dayNumber }})"
                                    class="text-sm px-3 py-1 rounded-lg border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-green-50 dark:hover:bg-green-900/20 hover:border-green-500 dark:hover:border-green-500 hover:text-green-600 dark:hover:text-green-400 transition-colors"
                                >
                                    Marcar {{ $dayName }}
                                </button>
                            @endforeach
                            <button 
                                type="button" 
                                onclick="document.getElementById('clearAllModal').classList.remove('hidden'); document.getElementById('clearAllModal').style.display='block';"
                                class="text-sm px-3 py-1 rounded-lg border border-red-300 dark:border-red-600 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors ml-auto"
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
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Día/Hora
                                    </th>
                                    @foreach($daysOfWeek as $dayNumber => $dayName)
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
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
                                    
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-750">
                                        <td class="px-6 py-3 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
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
                                                <label class="inline-flex items-center justify-center cursor-pointer">
                                                    <input 
                                                        type="checkbox" 
                                                        name="time_slots[]" 
                                                        value="{{ $dayNumber }}_{{ $timeSlot }}"
                                                        class="w-5 h-5 text-green-600 bg-gray-100 border-gray-300 rounded focus:ring-green-500 dark:focus:ring-green-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600 cursor-pointer day-{{ $dayNumber }}-checkbox"
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
            <div class="mt-6 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg px-4 py-3">
                    <div class="flex gap-3">
                    <span class="material-symbols-outlined text-blue-600 dark:text-blue-400">info</span>
                    <div class="text-sm text-blue-800 dark:text-blue-200">
                        <p class="font-medium mb-1">Información importante:</p>
                        <ul class="list-disc list-inside space-y-1 text-blue-700 dark:text-blue-300">
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
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 dark:bg-gray-900 dark:bg-opacity-75 transition-opacity" 
                 onclick="document.getElementById('clearAllModal').classList.add('hidden'); document.getElementById('clearAllModal').style.display='none';"></div>
            
            <!-- Modal -->
            <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-xl max-w-lg w-full z-10">
                <!-- Header -->
                <div class="bg-gradient-to-r from-red-600 to-rose-600 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-bold text-white flex items-center gap-2">
                            <span class="material-symbols-outlined">warning</span>
                            Confirmar acción
                        </h3>
                        <button onclick="document.getElementById('clearAllModal').classList.add('hidden'); document.getElementById('clearAllModal').style.display='none';" class="text-white hover:text-gray-200 transition">
                            <span class="material-symbols-outlined">close</span>
                        </button>
                    </div>
                </div>

                <!-- Contenido -->
                <div class="px-6 py-4">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center">
                                <span class="material-symbols-outlined text-red-600 dark:text-red-400 text-2xl">delete_sweep</span>
                            </div>
                        </div>
                        <div>
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                                ¿Limpiar todos los horarios?
                            </h4>
                            <p class="text-gray-600 dark:text-gray-400">
                                Esta acción desmarcará todos los horarios seleccionados. Tendrás que seleccionarlos nuevamente o guardar el formulario vacío.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="bg-gray-50 dark:bg-gray-700 px-6 py-4 flex justify-end gap-3">
                    <button 
                        onclick="document.getElementById('clearAllModal').classList.add('hidden'); document.getElementById('clearAllModal').style.display='none';" 
                        class="px-4 py-2 bg-gray-200 dark:bg-gray-600 text-gray-800 dark:text-gray-200 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-500 transition font-medium"
                    >
                        Cancelar
                    </button>
                    <button 
                        onclick="clearAll()" 
                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-medium flex items-center gap-2"
                    >
                        <span class="material-symbols-outlined text-sm">delete_sweep</span>
                        Limpiar todo
                    </button>
                </div>
            </div>
        </div>
    </div>

    @include('layouts.footer')

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
</body>
@endsection
