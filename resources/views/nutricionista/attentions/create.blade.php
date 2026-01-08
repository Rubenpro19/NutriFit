@extends('layouts.app')

@section('title', 'Atender Cita - NutriFit')

@section('content')
<body class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-green-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 flex flex-col">
    @include('layouts.header')

    <main class="flex-grow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
            <!-- Breadcrumb -->
            <nav class="mb-6 flex items-center gap-2 text-sm">
        <a href="{{ route('nutricionista.dashboard') }}" class="text-green-600 dark:text-green-400 hover:underline transition-colors">Inicio</a>
        <span class="material-symbols-outlined text-gray-400 text-sm">chevron_right</span>
        <a href="{{ route('nutricionista.appointments.show', $appointment) }}" class="text-green-600 dark:text-green-400 hover:underline transition-colors">Detalle de Cita</a>
        <span class="material-symbols-outlined text-gray-400 text-sm">chevron_right</span>
        <span class="text-gray-700 dark:text-gray-300 font-medium">Registrar Atención</span>
    </nav>

        <!-- Header -->
        <div class="mb-8 bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-4 sm:p-6 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between gap-3">
                <div class="flex items-center gap-3 flex-1 min-w-0">
                    <a href="{{ route('nutricionista.appointments.show', $appointment) }}" class="text-gray-700 dark:text-gray-300 hover:text-green-600 dark:hover:text-green-400 hover:scale-110 transition-all">
                        <span class="material-symbols-outlined text-2xl">arrow_back</span>
                    </a>
                    <div class="min-w-0 flex-1">
                        <h1 class="text-2xl sm:text-3xl font-bold bg-gradient-to-r from-green-600 to-emerald-600 dark:from-green-400 dark:to-emerald-400 bg-clip-text text-transparent">
                            Registrar Atención
                        </h1>
                        <p class="text-sm sm:text-base text-gray-700 dark:text-gray-300 mt-1">
                            Paciente: <span class="font-semibold">{{ $appointment->paciente->name }}</span> • {{ \Carbon\Carbon::parse($appointment->start_time)->format('d/m/Y H:i') }}
                        </p>
                    </div>
                </div>
                <div class="flex-shrink-0 hidden sm:flex items-center">
                    <span class="material-symbols-outlined text-3xl sm:text-4xl text-green-600 dark:text-green-400">health_and_safety</span>
                </div>
            </div>
        </div>

        @if(session('error'))
            <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-800 dark:text-red-200 rounded-xl p-4 mb-6">
                {{ session('error') }}
            </div>
        @endif

        <!-- Grid principal: Formulario + Panel de Resultados -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Columna Izquierda: Formulario (2/3) -->
            <div class="lg:col-span-2">
                <form method="POST" action="{{ route('nutricionista.attentions.store', $appointment) }}" id="attention-form">
                    @csrf

                    <!-- Datos Antropométricos -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 border border-gray-200 dark:border-gray-700 mb-6">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
                    <span class="material-symbols-outlined text-emerald-600">straighten</span>
                    Datos Antropométricos
                </h2>

                <div class="grid md:grid-cols-2 gap-6">
                    <!-- Peso -->
                    <div>
                        <label for="weight" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Peso <span class="text-red-500">*</span>
                        </label>
                        <div class="flex gap-2">
                            <input 
                                type="number" 
                                step="0.01" 
                                id="weight-input" 
                                value="{{ old('weight') }}"
                                class="flex-1 px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-gray-900 dark:text-white transition @error('weight') border-red-500 @enderror"
                                placeholder="70.5"
                                required
                            >
                            <select 
                                id="weight-unit"
                                class="px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-gray-900 dark:text-white transition"
                            >
                                <option value="kg">kg</option>
                                <option value="lb">lb</option>
                            </select>
                        </div>
                        <!-- Campo oculto que enviará el peso en kg -->
                        <input type="hidden" id="weight" name="weight" value="{{ old('weight') }}">
                        @error('weight')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Altura -->
                    <div>
                        <label for="height" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Altura (cm) <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="number" 
                            step="0.01" 
                            id="height" 
                            name="height" 
                            value="{{ old('height') }}"
                            class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-gray-900 dark:text-white transition @error('height') border-red-500 @enderror"
                            placeholder="170"
                            required
                        >
                        @error('height')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- IMC (auto-calculado) -->
                    <div>
                        <label for="bmi" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            IMC <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="number" 
                            step="0.01" 
                            id="bmi" 
                            name="bmi" 
                            value="{{ old('bmi') }}"
                            class="w-full px-4 py-3 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-300 dark:border-emerald-700 rounded-xl text-gray-900 dark:text-white font-semibold @error('bmi') border-red-500 @enderror"
                            readonly
                            required
                        >
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Se calcula automáticamente</p>
                        @error('bmi')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Cintura -->
                    <div>
                        <label for="waist" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Cintura (cm)
                        </label>
                        <input 
                            type="number" 
                            step="0.01" 
                            id="waist" 
                            name="waist" 
                            value="{{ old('waist') }}"
                            class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-gray-900 dark:text-white transition @error('waist') border-red-500 @enderror"
                            placeholder="75"
                        >
                        @error('waist')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Cadera -->
                    <div>
                        <label for="hip" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Cadera (cm)
                        </label>
                        <input 
                            type="number" 
                            step="0.01" 
                            id="hip" 
                            name="hip" 
                            value="{{ old('hip') }}"
                            class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-gray-900 dark:text-white transition @error('hip') border-red-500 @enderror"
                            placeholder="95"
                        >
                        @error('hip')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Grasa Corporal -->
                    <div>
                        <label for="body_fat" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Grasa Corporal (%)
                        </label>
                        <input 
                            type="number" 
                            step="0.01" 
                            id="body_fat" 
                            name="body_fat" 
                            value="{{ old('body_fat') }}"
                            class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-gray-900 dark:text-white transition @error('body_fat') border-red-500 @enderror"
                            placeholder="20.5"
                        >
                        @error('body_fat')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Presión Arterial -->
                    <div>
                        <label for="blood_pressure" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Presión Arterial
                        </label>
                        <input 
                            type="text" 
                            id="blood_pressure" 
                            name="blood_pressure" 
                            value="{{ old('blood_pressure') }}"
                            class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-gray-900 dark:text-white transition @error('blood_pressure') border-red-500 @enderror"
                            placeholder="120/80"
                            maxlength="20"
                        >
                        @error('blood_pressure')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Notas Clínicas -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 border border-gray-200 dark:border-gray-700 mb-6">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
                    <span class="material-symbols-outlined text-emerald-600">clinical_notes</span>
                    Notas Clínicas
                </h2>

                <!-- Diagnóstico -->
                <div class="mb-6">
                    <label for="diagnosis" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Diagnóstico <span class="text-red-500">*</span>
                    </label>
                    <textarea 
                        id="diagnosis" 
                        name="diagnosis" 
                        rows="6"
                        class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-gray-900 dark:text-white transition @error('diagnosis') border-red-500 @enderror"
                        placeholder="Describe el diagnóstico del paciente..."
                        required
                    >{{ old('diagnosis') }}</textarea>
                    @error('diagnosis')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Recomendaciones -->
                <div>
                    <label for="recommendations" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Recomendaciones <span class="text-red-500">*</span>
                    </label>
                    <textarea 
                        id="recommendations" 
                        name="recommendations" 
                        rows="8"
                        class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-gray-900 dark:text-white transition @error('recommendations') border-red-500 @enderror"
                        placeholder="Plan nutricional, ejercicios recomendados, cambios de hábitos..."
                        required
                    >{{ old('recommendations') }}</textarea>
                    @error('recommendations')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Botones de Acción -->
            <div class="flex gap-4">
                <a href="{{ route('nutricionista.appointments.show', $appointment) }}" 
                   id="cancel-btn"
                   class="flex-1 text-center bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 font-semibold py-3 px-6 rounded-xl hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                    Cancelar
                </a>
                <button type="submit" 
                        id="submit-btn"
                        class="flex-1 bg-gradient-to-r from-blue-600 to-emerald-600 text-white font-semibold py-3 px-6 rounded-xl hover:from-blue-700 hover:to-emerald-700 transition shadow-lg flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined" id="submit-icon">save</span>
                    <span id="submit-text">Guardar Atención</span>
                </button>
            </div>
        </form>
        </div>

        <!-- Columna Derecha: Panel de Resultados (1/3) -->
        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 border border-gray-200 dark:border-gray-700 sticky top-8">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
                    <span class="material-symbols-outlined text-blue-600">query_stats</span>
                    Análisis Corporal
                </h2>

                <!-- Visualización de la Figura -->
                <div class="mb-6">
                    <div class="flex justify-center items-end h-64 bg-gradient-to-b from-blue-50 to-transparent dark:from-gray-700 rounded-xl p-4">
                        <!-- Figura SVG que cambia según el IMC -->
                        <svg id="body-figure" class="transition-all duration-500" width="120" height="220" viewBox="0 0 120 220">
                            <!-- Cabeza -->
                            <circle cx="60" cy="20" r="15" fill="#FDB44B" stroke="#333" stroke-width="2"/>
                            
                            <!-- Torso -->
                            <ellipse id="torso" cx="60" cy="80" rx="25" ry="40" fill="#4A90E2" stroke="#333" stroke-width="2"/>
                            
                            <!-- Brazos -->
                            <line id="arm-left" x1="35" y1="60" x2="20" y2="100" stroke="#FDB44B" stroke-width="8" stroke-linecap="round"/>
                            <line id="arm-right" x1="85" y1="60" x2="100" y2="100" stroke="#FDB44B" stroke-width="8" stroke-linecap="round"/>
                            
                            <!-- Piernas -->
                            <line id="leg-left" x1="50" y1="120" x2="45" y2="200" stroke="#4A90E2" stroke-width="12" stroke-linecap="round"/>
                            <line id="leg-right" x1="70" y1="120" x2="75" y2="200" stroke="#4A90E2" stroke-width="12" stroke-linecap="round"/>
                        </svg>
                    </div>
                    
                    <!-- Indicador de Estado -->
                    <div id="body-status" class="text-center mt-4">
                        <p class="text-sm font-semibold text-gray-600 dark:text-gray-400">
                            Ingresa peso y altura para ver el análisis
                        </p>
                    </div>
                </div>

                <!-- Métricas Calculadas -->
                <div class="space-y-4">
                    <!-- IMC -->
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm font-medium text-gray-600 dark:text-gray-400">IMC</span>
                            <span id="display-bmi" class="text-2xl font-bold text-gray-900 dark:text-white">--</span>
                        </div>
                        <div id="bmi-bar" class="h-2 bg-gray-200 dark:bg-gray-600 rounded-full overflow-hidden">
                            <div id="bmi-indicator" class="h-full bg-blue-500 transition-all duration-500" style="width: 0%"></div>
                        </div>
                        <p id="bmi-category" class="text-xs text-gray-500 dark:text-gray-400 mt-2 text-center">-</p>
                    </div>

                    <!-- Espacio para más cálculos futuros -->
                    <div class="border-t border-gray-200 dark:border-gray-600 pt-4">
                        <p class="text-xs text-gray-500 dark:text-gray-400 text-center italic">
                            Más métricas se mostrarán aquí próximamente
                        </p>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </div>

    <!-- Script para cálculo automático del IMC -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const weightInputDisplay = document.getElementById('weight-input');
            const weightUnit = document.getElementById('weight-unit');
            const weightInput = document.getElementById('weight'); // Campo oculto que se envía
            const heightInput = document.getElementById('height');
            const bmiInput = document.getElementById('bmi');

            // Elementos del panel derecho
            const displayBmi = document.getElementById('display-bmi');
            const bmiIndicator = document.getElementById('bmi-indicator');
            const bmiCategory = document.getElementById('bmi-category');
            const bodyStatus = document.getElementById('body-status');
            const torso = document.getElementById('torso');
            const armLeft = document.getElementById('arm-left');
            const armRight = document.getElementById('arm-right');
            const legLeft = document.getElementById('leg-left');
            const legRight = document.getElementById('leg-right');

            function calculateBMI() {
                const weightDisplay = parseFloat(weightInputDisplay.value);
                const unit = weightUnit.value;
                const height = parseFloat(heightInput.value);

                if (weightDisplay > 0 && height > 0) {
                    // Convertir peso a kg si está en libras
                    let weightInKg = weightDisplay;
                    if (unit === 'lb') {
                        weightInKg = weightDisplay * 0.453592; // 1 lb = 0.453592 kg
                    }

                    // Guardar el peso en kg en el campo oculto
                    weightInput.value = weightInKg.toFixed(2);

                    // IMC = peso (kg) / (altura (m))^2
                    const heightInMeters = height / 100;
                    const bmi = weightInKg / (heightInMeters * heightInMeters);
                    const bmiValue = bmi.toFixed(2);
                    bmiInput.value = bmiValue;

                    // Actualizar display del IMC
                    displayBmi.textContent = bmiValue;

                    // Determinar categoría y color
                    let category = '';
                    let color = '';
                    let percentage = 0;
                    
                    if (bmi < 18.5) {
                        category = 'Bajo peso';
                        color = 'text-blue-600';
                        percentage = (bmi / 18.5) * 25;
                        updateBodyFigure('thin');
                    } else if (bmi < 25) {
                        category = 'Peso normal';
                        color = 'text-green-600';
                        percentage = 25 + ((bmi - 18.5) / (25 - 18.5)) * 25;
                        updateBodyFigure('normal');
                    } else if (bmi < 30) {
                        category = 'Sobrepeso';
                        color = 'text-yellow-600';
                        percentage = 50 + ((bmi - 25) / (30 - 25)) * 25;
                        updateBodyFigure('overweight');
                    } else {
                        category = 'Obesidad';
                        color = 'text-red-600';
                        percentage = Math.min(75 + ((bmi - 30) / 10) * 25, 100);
                        updateBodyFigure('obese');
                    }

                    bmiCategory.textContent = category;
                    bmiCategory.className = `text-sm font-semibold mt-2 text-center ${color}`;
                    bmiIndicator.style.width = percentage + '%';
                    
                    // Actualizar color del indicador
                    if (bmi < 18.5) {
                        bmiIndicator.className = 'h-full bg-blue-500 transition-all duration-500';
                    } else if (bmi < 25) {
                        bmiIndicator.className = 'h-full bg-green-500 transition-all duration-500';
                    } else if (bmi < 30) {
                        bmiIndicator.className = 'h-full bg-yellow-500 transition-all duration-500';
                    } else {
                        bmiIndicator.className = 'h-full bg-red-500 transition-all duration-500';
                    }

                    bodyStatus.innerHTML = `<p class="text-sm font-semibold ${color}">${category}</p>`;
                } else {
                    bmiInput.value = '';
                    displayBmi.textContent = '--';
                    bmiCategory.textContent = '-';
                    bmiIndicator.style.width = '0%';
                    bodyStatus.innerHTML = '<p class="text-sm font-semibold text-gray-600 dark:text-gray-400">Ingresa peso y altura para ver el análisis</p>';
                }
            }

            function updateBodyFigure(type) {
                switch(type) {
                    case 'thin':
                        // Figura delgada
                        torso.setAttribute('rx', '20');
                        torso.setAttribute('ry', '35');
                        armLeft.setAttribute('stroke-width', '6');
                        armRight.setAttribute('stroke-width', '6');
                        legLeft.setAttribute('stroke-width', '10');
                        legRight.setAttribute('stroke-width', '10');
                        break;
                    case 'normal':
                        // Figura normal
                        torso.setAttribute('rx', '25');
                        torso.setAttribute('ry', '40');
                        armLeft.setAttribute('stroke-width', '8');
                        armRight.setAttribute('stroke-width', '8');
                        legLeft.setAttribute('stroke-width', '12');
                        legRight.setAttribute('stroke-width', '12');
                        break;
                    case 'overweight':
                        // Figura con sobrepeso
                        torso.setAttribute('rx', '30');
                        torso.setAttribute('ry', '42');
                        armLeft.setAttribute('stroke-width', '10');
                        armRight.setAttribute('stroke-width', '10');
                        legLeft.setAttribute('stroke-width', '14');
                        legRight.setAttribute('stroke-width', '14');
                        break;
                    case 'obese':
                        // Figura con obesidad
                        torso.setAttribute('rx', '35');
                        torso.setAttribute('ry', '45');
                        armLeft.setAttribute('stroke-width', '12');
                        armRight.setAttribute('stroke-width', '12');
                        legLeft.setAttribute('stroke-width', '16');
                        legRight.setAttribute('stroke-width', '16');
                        break;
                }
            }

            // Event listeners
            weightInputDisplay.addEventListener('input', calculateBMI);
            weightUnit.addEventListener('change', calculateBMI);
            heightInput.addEventListener('input', calculateBMI);

            // Calcular IMC inicial si hay valores (por ejemplo, de old())
            if (weightInputDisplay.value && heightInput.value) {
                calculateBMI();
            }
        });

        // Protección contra múltiples envíos del formulario
        const form = document.getElementById('attention-form');
        const submitBtn = document.getElementById('submit-btn');
        const cancelBtn = document.getElementById('cancel-btn');
        const submitIcon = document.getElementById('submit-icon');
        const submitText = document.getElementById('submit-text');
        let isSubmitting = false;

        form.addEventListener('submit', function(e) {
            // Si ya se está enviando, prevenir el envío
            if (isSubmitting) {
                e.preventDefault();
                return false;
            }

            // Marcar como enviando
            isSubmitting = true;

            // Deshabilitar el botón de enviar
            submitBtn.disabled = true;
            submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
            submitBtn.classList.remove('hover:from-blue-700', 'hover:to-emerald-700');

            // Deshabilitar el botón de cancelar
            cancelBtn.classList.add('opacity-50', 'cursor-not-allowed', 'pointer-events-none');
            cancelBtn.classList.remove('hover:bg-gray-200', 'dark:hover:bg-gray-600');

            // Cambiar el contenido del botón de enviar
            submitIcon.classList.add('animate-spin');
            submitIcon.textContent = 'hourglass_empty';
            submitText.textContent = 'Guardando...';

            // El formulario se enviará normalmente
        });
    </script>

    </div>
    </main>
    @include('layouts.footer')
</body>
@endsection
