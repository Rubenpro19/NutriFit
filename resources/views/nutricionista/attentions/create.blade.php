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
            <div class="lg:col-span-2 order-1">
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
                        <input type="hidden" id="weight" name="weight" value="{{ old('weight') }}" required>
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

                    <!-- Cintura -->
                    <div>
                        <label for="waist" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Cintura (cm) <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="number" 
                            step="0.01" 
                            id="waist" 
                            name="waist" 
                            value="{{ old('waist') }}"
                            class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-gray-900 dark:text-white transition @error('waist') border-red-500 @enderror"
                            placeholder="75"
                            required
                        >
                        @error('waist')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Cadera -->
                    <div>
                        <label for="hip" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Cadera (cm) <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="number" 
                            step="0.01" 
                            id="hip" 
                            name="hip" 
                            value="{{ old('hip') }}"
                            class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-gray-900 dark:text-white transition @error('hip') border-red-500 @enderror"
                            placeholder="95"
                            required
                        >
                        @error('hip')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Cuello -->
                    <div>
                        <label for="neck" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Cuello (cm) <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="number" 
                            step="0.01" 
                            id="neck" 
                            name="neck" 
                            value="{{ old('neck') }}"
                            class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-gray-900 dark:text-white transition @error('neck') border-red-500 @enderror"
                            placeholder="35"
                            required
                        >
                        @error('neck')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Muñeca -->
                    <div>
                        <label for="wrist" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Muñeca (cm) <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="number" 
                            step="0.01" 
                            id="wrist" 
                            name="wrist" 
                            value="{{ old('wrist') }}"
                            class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-gray-900 dark:text-white transition @error('wrist') border-red-500 @enderror"
                            placeholder="16"
                            required
                        >
                        @error('wrist')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Brazo Contraído -->
                    <div>
                        <label for="arm_contracted" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Brazo Contraído (cm) <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="number" 
                            step="0.01" 
                            id="arm_contracted" 
                            name="arm_contracted" 
                            value="{{ old('arm_contracted') }}"
                            class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-gray-900 dark:text-white transition @error('arm_contracted') border-red-500 @enderror"
                            placeholder="35"
                            required
                        >
                        @error('arm_contracted')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Brazo Relajado -->
                    <div>
                        <label for="arm_relaxed" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Brazo Relajado (cm) <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="number" 
                            step="0.01" 
                            id="arm_relaxed" 
                            name="arm_relaxed" 
                            value="{{ old('arm_relaxed') }}"
                            class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-gray-900 dark:text-white transition @error('arm_relaxed') border-red-500 @enderror"
                            placeholder="30"
                            required
                        >
                        @error('arm_relaxed')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Pierna -->
                    <div>
                        <label for="thigh" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Pierna (cm) <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="number" 
                            step="0.01" 
                            id="thigh" 
                            name="thigh" 
                            value="{{ old('thigh') }}"
                            class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-gray-900 dark:text-white transition @error('thigh') border-red-500 @enderror"
                            placeholder="55"
                            required
                        >
                        @error('thigh')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Pantorrilla -->
                    <div>
                        <label for="calf" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Pantorrilla (cm) <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="number" 
                            step="0.01" 
                            id="calf" 
                            name="calf" 
                            value="{{ old('calf') }}"
                            class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-gray-900 dark:text-white transition @error('calf') border-red-500 @enderror"
                            placeholder="38"
                            required
                        >
                        @error('calf')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nivel de Actividad Física -->
                    <div>
                        <label for="activity_level" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Nivel de Actividad Física <span class="text-red-500">*</span>
                        </label>
                        <select 
                            id="activity_level" 
                            name="activity_level"
                            class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-gray-900 dark:text-white transition @error('activity_level') border-red-500 @enderror"
                            required
                        >
                            <option value="sedentary">Sedentario (poco o ningún ejercicio)</option>
                            <option value="light">Ligero (ejercicio 1-3 días/semana)</option>
                            <option value="moderate" selected>Moderado (ejercicio 3-5 días/semana)</option>
                            <option value="active">Activo (ejercicio 6-7 días/semana)</option>
                            <option value="very_active">Muy activo (ejercicio intenso diario)</option>
                        </select>
                        @error('activity_level')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Objetivo Nutricional -->
                    <div>
                        <label for="nutrition_goal" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Objetivo Nutricional <span class="text-red-500">*</span>
                        </label>
                        <select 
                            id="nutrition_goal" 
                            name="nutrition_goal"
                            class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-gray-900 dark:text-white transition"
                        >
                            <option value="deficit">Pérdida de peso (déficit calórico)</option>
                            <option value="maintenance" selected>Mantenimiento</option>
                            <option value="surplus">Ganancia de masa (superávit calórico)</option>
                        </select>
                    </div>
                </div>

                <!-- Campos ocultos para valores calculados -->
                <input type="hidden" id="bmi" name="bmi" value="{{ old('bmi') }}" required>
                <input type="hidden" id="body_fat" name="body_fat" value="{{ old('body_fat') }}">
                <input type="hidden" id="tmb" name="tmb" value="{{ old('tmb') }}">
                <input type="hidden" id="tdee" name="tdee" value="{{ old('tdee') }}">
                <input type="hidden" id="whr" name="whr" value="{{ old('whr') }}">
                <input type="hidden" id="wht" name="wht" value="{{ old('wht') }}">
                <input type="hidden" id="frame_index" name="frame_index" value="{{ old('frame_index') }}">
                <input type="hidden" id="target_calories" name="target_calories" value="{{ old('target_calories') }}">
                <input type="hidden" id="protein_grams" name="protein_grams" value="{{ old('protein_grams') }}">
                <input type="hidden" id="fat_grams" name="fat_grams" value="{{ old('fat_grams') }}">
                <input type="hidden" id="carbs_grams" name="carbs_grams" value="{{ old('carbs_grams') }}">
            </div>

            <!-- Resultados Nutricionales -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 border border-gray-200 dark:border-gray-700 mt-6">
                <!-- Título principal -->
                <div class="text-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white flex items-center justify-center gap-2 mb-2">
                        <span class="material-symbols-outlined text-emerald-600 text-3xl">bar_chart</span>
                        TUS RESULTADOS
                    </h2>
                    <p class="text-sm text-gray-600 dark:text-gray-400">En función de sus aportes, aquí están sus objetivos nutricionales calculados</p>
                </div>

                <div class="grid md:grid-cols-3 gap-6">
                    <!-- Sección: Datos Metabólicos -->
                    <div class="bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-xl p-5 border border-blue-200 dark:border-blue-700">
                        <div class="flex items-center gap-2 mb-4">
                            <span class="material-symbols-outlined text-blue-600 dark:text-blue-400">show_chart</span>
                            <h4 class="text-base font-bold text-gray-900 dark:text-white">DATOS METABÓLICOS</h4>
                        </div>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-700 dark:text-gray-300">BMR:</span>
                                <span id="display-bmr" class="text-lg font-bold text-blue-600 dark:text-blue-400">-- cal</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-700 dark:text-gray-300">TDEE:</span>
                                <span id="display-tdee-nutrition" class="text-lg font-bold text-blue-600 dark:text-blue-400">-- cal</span>
                            </div>
                            <div class="flex justify-between items-center pt-2 border-t border-blue-200 dark:border-blue-600">
                                <span class="text-sm font-semibold text-gray-800 dark:text-gray-200">Calorías objetivo:</span>
                                <span id="display-target-calories-main" class="text-xl font-bold text-blue-700 dark:text-blue-300">-- cal</span>
                            </div>
                        </div>
                    </div>

                    <!-- Sección: Macros Diarias -->
                    <div class="bg-gradient-to-br from-purple-50 to-pink-50 dark:from-purple-900/20 dark:to-pink-900/20 rounded-xl p-5 border border-purple-200 dark:border-purple-700">
                        <div class="flex items-center gap-2 mb-4">
                            <span class="material-symbols-outlined text-purple-600 dark:text-purple-400">restaurant</span>
                            <h4 class="text-base font-bold text-gray-900 dark:text-white">MACROS DIARIAS</h4>
                        </div>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-700 dark:text-gray-300">Proteína:</span>
                                <span id="display-protein-main" class="text-lg font-bold text-purple-600 dark:text-purple-400">--g</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-700 dark:text-gray-300">Grasas:</span>
                                <span id="display-fat-main" class="text-lg font-bold text-purple-600 dark:text-purple-400">--g</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-700 dark:text-gray-300">Carbohidratos:</span>
                                <span id="display-carbs-main" class="text-lg font-bold text-purple-600 dark:text-purple-400">--g</span>
                            </div>
                        </div>
                    </div>

                    <!-- Sección: Desglose de Macros -->
                    <div class="bg-gradient-to-br from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 rounded-xl p-5 border border-green-200 dark:border-green-700">
                        <div class="flex items-center gap-2 mb-4">
                            <span class="material-symbols-outlined text-green-600 dark:text-green-400">pie_chart</span>
                            <h4 class="text-base font-bold text-gray-900 dark:text-white">DESGLOSE DE MACROS</h4>
                        </div>
                        <div class="space-y-3">
                            <div>
                                <div class="flex justify-between items-center mb-1">
                                    <span class="text-sm text-gray-700 dark:text-gray-300">Proteína (<span id="protein-percent-display">--</span>):</span>
                                    <span id="protein-kcal-display" class="text-sm font-bold text-green-600 dark:text-green-400">-- cal</span>
                                </div>
                                <div class="h-2 bg-gray-200 dark:bg-gray-600 rounded-full overflow-hidden">
                                    <div id="protein-bar" class="h-full bg-blue-500 transition-all duration-500" style="width: 0%"></div>
                                </div>
                            </div>
                            <div>
                                <div class="flex justify-between items-center mb-1">
                                    <span class="text-sm text-gray-700 dark:text-gray-300">Grasas (<span id="fat-percent-display">--</span>):</span>
                                    <span id="fat-kcal-display" class="text-sm font-bold text-green-600 dark:text-green-400">-- cal</span>
                                </div>
                                <div class="h-2 bg-gray-200 dark:bg-gray-600 rounded-full overflow-hidden">
                                    <div id="fat-bar" class="h-full bg-orange-500 transition-all duration-500" style="width: 0%"></div>
                                </div>
                            </div>
                            <div>
                                <div class="flex justify-between items-center mb-1">
                                    <span class="text-sm text-gray-700 dark:text-gray-300">Carbohidratos (<span id="carbs-percent-display">--</span>):</span>
                                    <span id="carbs-kcal-display" class="text-sm font-bold text-green-600 dark:text-green-400">-- cal</span>
                                </div>
                                <div class="h-2 bg-gray-200 dark:bg-gray-600 rounded-full overflow-hidden">
                                    <div id="carbs-bar" class="h-full bg-purple-500 transition-all duration-500" style="width: 0%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notas Clínicas -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 border border-gray-200 dark:border-gray-700 mt-6">
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
            <div class="mt-6">
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
            </div>
        </form>
        </div>

        <!-- Columna Derecha: Panel de Resultados (1/3) -->
        <div class="lg:col-span-1 order-2">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 border border-gray-200 dark:border-gray-700 lg:sticky lg:top-8">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4 flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-blue-600">analytics</span>
                    Análisis Antropométrico Completo
                </h2>
                
                <!-- Muñeco IMC -->
                <div class="flex justify-center mb-6">
                    <svg id="bmi-avatar" width="80" height="120" viewBox="0 0 100 150" xmlns="http://www.w3.org/2000/svg">
                        <!-- Cabeza -->
                        <circle cx="50" cy="25" r="15" fill="#FDB8B8" stroke="#333" stroke-width="1.5"/>
                        
                        <!-- Torso -->
                        <ellipse id="torso" cx="50" cy="65" rx="18" ry="25" fill="#4A90E2" stroke="#333" stroke-width="1.5"/>
                        
                        <!-- Brazos -->
                        <line id="arm-left" x1="32" y1="55" x2="20" y2="75" stroke="#FDB8B8" stroke-width="6" stroke-linecap="round"/>
                        <line id="arm-right" x1="68" y1="55" x2="80" y2="75" stroke="#FDB8B8" stroke-width="6" stroke-linecap="round"/>
                        
                        <!-- Piernas -->
                        <line id="leg-left" x1="43" y1="90" x2="40" y2="120" stroke="#4A90E2" stroke-width="8" stroke-linecap="round"/>
                        <line id="leg-right" x1="57" y1="90" x2="60" y2="120" stroke="#4A90E2" stroke-width="8" stroke-linecap="round"/>
                        
                        <!-- Pies -->
                        <ellipse cx="40" cy="125" rx="8" ry="4" fill="#333"/>
                        <ellipse cx="60" cy="125" rx="8" ry="4" fill="#333"/>
                        
                        <!-- Ojos -->
                        <circle cx="45" cy="23" r="2" fill="#333"/>
                        <circle cx="55" cy="23" r="2" fill="#333"/>
                        
                        <!-- Boca (sonrisa por defecto) -->
                        <path id="mouth" d="M 43 29 Q 50 32 57 29" stroke="#333" stroke-width="1.5" fill="none" stroke-linecap="round"/>
                    </svg>
                </div>

                <div class="space-y-4">
                    <!-- IMC -->
                    <div class="bg-gradient-to-br from-blue-50 to-cyan-50 dark:from-blue-900/20 dark:to-cyan-900/20 rounded-xl p-4 border border-blue-200 dark:border-blue-700">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">IMC</span>
                            <span id="display-bmi" class="text-2xl font-bold text-blue-600 dark:text-blue-400">--</span>
                        </div>
                        <div class="h-2 bg-gray-200 dark:bg-gray-600 rounded-full overflow-hidden mb-2">
                            <div id="bmi-indicator" class="h-full bg-blue-500 transition-all duration-500" style="width: 0%"></div>
                        </div>
                        <p id="bmi-category" class="text-xs font-medium text-gray-600 dark:text-gray-400">-</p>
                    </div>

                    <!-- TMB -->
                    <div class="bg-gradient-to-br from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 rounded-xl p-4 border border-green-200 dark:border-green-700">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">TMB</span>
                            <span id="display-tmb" class="text-xl font-bold text-green-600 dark:text-green-400">--</span>
                        </div>
                        <p class="text-xs font-medium text-gray-600 dark:text-gray-400">Tasa Metabólica Basal (kcal/día)</p>
                    </div>

                    <!-- Gasto Energético -->
                    <div class="bg-gradient-to-br from-purple-50 to-pink-50 dark:from-purple-900/20 dark:to-pink-900/20 rounded-xl p-4 border border-purple-200 dark:border-purple-700">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">Gasto Energético</span>
                            <span id="display-tdee" class="text-xl font-bold text-purple-600 dark:text-purple-400">--</span>
                        </div>
                        <p class="text-xs font-medium text-gray-600 dark:text-gray-400" id="tdee-description">Calorías diarias requeridas</p>
                    </div>

                    <!-- Índice Cintura-Cadera -->
                    <div class="bg-gradient-to-br from-orange-50 to-red-50 dark:from-orange-900/20 dark:to-red-900/20 rounded-xl p-4 border border-orange-200 dark:border-orange-700">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">Índice Cintura-Cadera</span>
                            <span id="display-whr" class="text-xl font-bold text-orange-600 dark:text-orange-400">--</span>
                        </div>
                        <p id="whr-category" class="text-xs font-medium text-gray-600 dark:text-gray-400">-</p>
                    </div>

                    <!-- Índice Cintura-Altura -->
                    <div class="bg-gradient-to-br from-yellow-50 to-amber-50 dark:from-yellow-900/20 dark:to-amber-900/20 rounded-xl p-4 border border-yellow-200 dark:border-yellow-700">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">Índice Cintura-Altura</span>
                            <span id="display-wht" class="text-xl font-bold text-yellow-600 dark:text-yellow-400">--</span>
                        </div>
                        <p id="wht-category" class="text-xs font-medium text-gray-600 dark:text-gray-400">-</p>
                    </div>

                    <!-- Complexión Ósea -->
                    <div class="bg-gradient-to-br from-indigo-50 to-blue-50 dark:from-indigo-900/20 dark:to-blue-900/20 rounded-xl p-4 border border-indigo-200 dark:border-indigo-700">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">Complexión Ósea</span>
                            <span id="display-frame" class="text-xl font-bold text-indigo-600 dark:text-indigo-400">--</span>
                        </div>
                        <p id="frame-category" class="text-xs font-medium text-gray-600 dark:text-gray-400">-</p>
                    </div>

                    <!-- Porcentaje de Grasa -->
                    <div class="bg-gradient-to-br from-rose-50 to-pink-50 dark:from-rose-900/20 dark:to-pink-900/20 rounded-xl p-4 border border-rose-200 dark:border-rose-700">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">% Grasa Corporal</span>
                            <span id="display-bodyfat" class="text-xl font-bold text-rose-600 dark:text-rose-400">--</span>
                        </div>
                        <p id="bodyfat-category" class="text-xs font-medium text-gray-600 dark:text-gray-400">-</p>
                    </div>

                    <!-- Info del Paciente (para cálculos) -->
                    <input type="hidden" id="patient-gender" value="{{ $appointment->paciente->personalData->gender ?? 'male' }}">
                    <input type="hidden" id="patient-age" value="{{ $appointment->paciente->personalData->age ?? 30 }}">
                </div>
            </div>
        </div>
        
        </div>
    </div>

    <!-- Script para cálculos antropométricos completos -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Inputs
            const weightInputDisplay = document.getElementById('weight-input');
            const weightUnit = document.getElementById('weight-unit');
            const weightInput = document.getElementById('weight');
            const heightInput = document.getElementById('height');
            const waistInput = document.getElementById('waist');
            const hipInput = document.getElementById('hip');
            const neckInput = document.getElementById('neck');
            const wristInput = document.getElementById('wrist');
            const activityLevel = document.getElementById('activity_level');
            const nutritionGoal = document.getElementById('nutrition_goal');
            const bmiInput = document.getElementById('bmi');
            const bodyFatInput = document.getElementById('body_fat');

            // Datos del paciente
            const gender = document.getElementById('patient-gender').value;
            const age = parseInt(document.getElementById('patient-age').value);

            // Displays
            const displayBmi = document.getElementById('display-bmi');
            const bmiIndicator = document.getElementById('bmi-indicator');
            const bmiCategory = document.getElementById('bmi-category');
            const displayTmb = document.getElementById('display-tmb');
            const displayTdee = document.getElementById('display-tdee');
            const tdeeDescription = document.getElementById('tdee-description');
            const displayWhr = document.getElementById('display-whr');
            const whrCategory = document.getElementById('whr-category');
            const displayWht = document.getElementById('display-wht');
            const whtCategory = document.getElementById('wht-category');
            const displayFrame = document.getElementById('display-frame');
            const frameCategory = document.getElementById('frame-category');
            const displayBodyfat = document.getElementById('display-bodyfat');
            const bodyfatCategory = document.getElementById('bodyfat-category');

            function calculateAll() {
                const weightDisplay = parseFloat(weightInputDisplay.value);
                const unit = weightUnit.value;
                const height = parseFloat(heightInput.value);
                const waist = parseFloat(waistInput.value);
                const hip = parseFloat(hipInput.value);
                const neck = parseFloat(neckInput.value);
                const wrist = parseFloat(wristInput.value);

                // Convertir peso a kg
                let weight = weightDisplay;
                if (unit === 'lb') {
                    weight = weightDisplay * 0.453592;
                }
                weightInput.value = weight.toFixed(2);

                if (weight > 0 && height > 0) {
                    const heightM = height / 100;

                    // 1. IMC
                    const bmi = weight / (heightM * heightM);
                    const bmiValue = bmi.toFixed(2);
                    bmiInput.value = bmiValue;
                    displayBmi.textContent = bmiValue;
                    
                    let bmiCat, bmiColor, bmiPercentage;
                    if (bmi < 18.5) {
                        bmiCat = 'Bajo peso - Riesgo de desnutrición';
                        bmiColor = 'text-blue-600';
                        bmiPercentage = (bmi / 18.5) * 25;
                    } else if (bmi < 25) {
                        bmiCat = 'Peso normal - Saludable';
                        bmiColor = 'text-green-600';
                        bmiPercentage = 25 + ((bmi - 18.5) / 6.5) * 25;
                    } else if (bmi < 30) {
                        bmiCat = 'Sobrepeso - Riesgo aumentado';
                        bmiColor = 'text-yellow-600';
                        bmiPercentage = 50 + ((bmi - 25) / 5) * 25;
                    } else if (bmi < 35) {
                        bmiCat = 'Obesidad I - Riesgo moderado';
                        bmiColor = 'text-orange-600';
                        bmiPercentage = 75 + ((bmi - 30) / 5) * 12.5;
                    } else {
                        bmiCat = 'Obesidad II/III - Riesgo alto';
                        bmiColor = 'text-red-600';
                        bmiPercentage = Math.min(87.5 + ((bmi - 35) / 10) * 12.5, 100);
                    }
                    
                    bmiCategory.textContent = bmiCat;
                    bmiCategory.className = `text-xs font-medium ${bmiColor}`;
                    bmiIndicator.style.width = bmiPercentage + '%';

                    // Actualizar muñeco según IMC
                    updateBMIAvatar(bmi);

                    // 2. TMB (Fórmula Mifflin-St Jeor)
                    let tmb;
                    if (gender === 'male') {
                        tmb = (10 * weight) + (6.25 * height) - (5 * age) + 5;
                    } else {
                        tmb = (10 * weight) + (6.25 * height) - (5 * age) - 161;
                    }
                    const tmbValue = Math.round(tmb);
                    document.getElementById('tmb').value = tmbValue;
                    displayTmb.textContent = tmbValue + ' kcal';

                    // 3. Gasto Energético Total (TDEE)
                    const activityMultipliers = {
                        'sedentary': 1.2,
                        'light': 1.375,
                        'moderate': 1.55,
                        'active': 1.725,
                        'very_active': 1.9
                    };
                    const tdee = tmb * activityMultipliers[activityLevel.value];
                    const tdeeValue = Math.round(tdee);
                    document.getElementById('tdee').value = tdeeValue;
                    displayTdee.textContent = tdeeValue + ' kcal';
                    
                    const activityLabels = {
                        'sedentary': 'Sedentario',
                        'light': 'Actividad ligera',
                        'moderate': 'Actividad moderada',
                        'active': 'Muy activo',
                        'very_active': 'Extremadamente activo'
                    };
                    tdeeDescription.textContent = activityLabels[activityLevel.value];

                    // 4. Índice Cintura-Cadera
                    if (waist > 0 && hip > 0) {
                        const whr = waist / hip;
                        const whrValue = whr.toFixed(3);
                        document.getElementById('whr').value = whrValue;
                        displayWhr.textContent = whrValue;
                        
                        let whrCat, whrColor;
                        if (gender === 'male') {
                            if (whr < 0.95) {
                                whrCat = 'Bajo riesgo cardiovascular';
                                whrColor = 'text-green-600';
                            } else if (whr < 1.0) {
                                whrCat = 'Riesgo cardiovascular moderado';
                                whrColor = 'text-yellow-600';
                            } else {
                                whrCat = 'Riesgo cardiovascular alto';
                                whrColor = 'text-red-600';
                            }
                        } else {
                            if (whr < 0.80) {
                                whrCat = 'Bajo riesgo cardiovascular';
                                whrColor = 'text-green-600';
                            } else if (whr < 0.85) {
                                whrCat = 'Riesgo cardiovascular moderado';
                                whrColor = 'text-yellow-600';
                            } else {
                                whrCat = 'Riesgo cardiovascular alto';
                                whrColor = 'text-red-600';
                            }
                        }
                        whrCategory.textContent = whrCat;
                        whrCategory.className = `text-xs font-medium ${whrColor}`;
                    } else {
                        document.getElementById('whr').value = '';
                        displayWhr.textContent = '--';
                        whrCategory.textContent = 'Ingresa cintura y cadera';
                    }

                    // 5. Índice Cintura-Altura
                    if (waist > 0) {
                        const wht = waist / height;
                        const whtValue = wht.toFixed(3);
                        document.getElementById('wht').value = whtValue;
                        displayWht.textContent = whtValue;
                        
                        let whtCat, whtColor;
                        if (wht < 0.40) {
                            whtCat = 'Extremadamente delgado';
                            whtColor = 'text-blue-600';
                        } else if (wht < 0.50) {
                            whtCat = 'Saludable - Bajo riesgo';
                            whtColor = 'text-green-600';
                        } else if (wht < 0.60) {
                            whtCat = 'Sobrepeso - Riesgo aumentado';
                            whtColor = 'text-yellow-600';
                        } else {
                            whtCat = 'Obesidad - Riesgo alto';
                            whtColor = 'text-red-600';
                        }
                        whtCategory.textContent = whtCat;
                        whtCategory.className = `text-xs font-medium ${whtColor}`;
                    } else {
                        document.getElementById('wht').value = '';
                        displayWht.textContent = '--';
                        whtCategory.textContent = 'Ingresa cintura';
                    }

                    // 6. Complexión Ósea (Índice de Frisancho)
                    if (wrist > 0) {
                        const frameIndex = height / wrist;
                        const frameValue = frameIndex.toFixed(2);
                        document.getElementById('frame_index').value = frameValue;
                        displayFrame.textContent = frameValue;
                        
                        let frameCat, frameColor;
                        if (gender === 'male') {
                            if (frameIndex > 10.4) {
                                frameCat = 'Complexión pequeña';
                                frameColor = 'text-blue-600';
                            } else if (frameIndex > 9.6) {
                                frameCat = 'Complexión mediana';
                                frameColor = 'text-green-600';
                            } else {
                                frameCat = 'Complexión grande';
                                frameColor = 'text-indigo-600';
                            }
                        } else {
                            if (frameIndex > 11.0) {
                                frameCat = 'Complexión pequeña';
                                frameColor = 'text-blue-600';
                            } else if (frameIndex > 10.1) {
                                frameCat = 'Complexión mediana';
                                frameColor = 'text-green-600';
                            } else {
                                frameCat = 'Complexión grande';
                                frameColor = 'text-indigo-600';
                            }
                        }
                        frameCategory.textContent = frameCat;
                        frameCategory.className = `text-xs font-medium ${frameColor}`;
                    } else {
                        document.getElementById('frame_index').value = '';
                        displayFrame.textContent = '--';
                        frameCategory.textContent = 'Ingresa muñeca';
                    }

                    // 7. Porcentaje de Grasa Corporal (Fórmula U.S. Navy)
                    if (waist > 0 && neck > 0) {
                        let bodyFatPercentage;
                        if (gender === 'male') {
                            if (hip > 0) {
                                bodyFatPercentage = 495 / (1.0324 - 0.19077 * Math.log10(waist - neck) + 0.15456 * Math.log10(height)) - 450;
                            } else {
                                bodyFatPercentage = 495 / (1.0324 - 0.19077 * Math.log10(waist - neck) + 0.15456 * Math.log10(height)) - 450;
                            }
                        } else {
                            if (hip > 0) {
                                bodyFatPercentage = 495 / (1.29579 - 0.35004 * Math.log10(waist + hip - neck) + 0.22100 * Math.log10(height)) - 450;
                            } else {
                                displayBodyfat.textContent = '--';
                                bodyfatCategory.textContent = 'Ingresa cadera para cálculo preciso';
                                bodyFatInput.value = '';
                                return;
                            }
                        }

                        const bfValue = bodyFatPercentage.toFixed(2);
                        bodyFatInput.value = bfValue;
                        displayBodyfat.textContent = bfValue + '%';

                        // Clasificación según edad y género
                        let bfCat, bfColor;
                        if (gender === 'male') {
                            if (age <= 39) {
                                if (bodyFatPercentage < 8) {
                                    bfCat = 'Atleta de élite';
                                    bfColor = 'text-blue-600';
                                } else if (bodyFatPercentage < 20) {
                                    bfCat = 'Saludable';
                                    bfColor = 'text-green-600';
                                } else if (bodyFatPercentage < 25) {
                                    bfCat = 'Aceptable';
                                    bfColor = 'text-yellow-600';
                                } else {
                                    bfCat = 'Alto - Riesgo para la salud';
                                    bfColor = 'text-red-600';
                                }
                            } else {
                                if (bodyFatPercentage < 11) {
                                    bfCat = 'Atleta de élite';
                                    bfColor = 'text-blue-600';
                                } else if (bodyFatPercentage < 22) {
                                    bfCat = 'Saludable';
                                    bfColor = 'text-green-600';
                                } else if (bodyFatPercentage < 28) {
                                    bfCat = 'Aceptable';
                                    bfColor = 'text-yellow-600';
                                } else {
                                    bfCat = 'Alto - Riesgo para la salud';
                                    bfColor = 'text-red-600';
                                }
                            }
                        } else {
                            if (age <= 39) {
                                if (bodyFatPercentage < 21) {
                                    bfCat = 'Atleta de élite';
                                    bfColor = 'text-blue-600';
                                } else if (bodyFatPercentage < 33) {
                                    bfCat = 'Saludable';
                                    bfColor = 'text-green-600';
                                } else if (bodyFatPercentage < 39) {
                                    bfCat = 'Aceptable';
                                    bfColor = 'text-yellow-600';
                                } else {
                                    bfCat = 'Alto - Riesgo para la salud';
                                    bfColor = 'text-red-600';
                                }
                            } else {
                                if (bodyFatPercentage < 23) {
                                    bfCat = 'Atleta de élite';
                                    bfColor = 'text-blue-600';
                                } else if (bodyFatPercentage < 34) {
                                    bfCat = 'Saludable';
                                    bfColor = 'text-green-600';
                                } else if (bodyFatPercentage < 40) {
                                    bfCat = 'Aceptable';
                                    bfColor = 'text-yellow-600';
                                } else {
                                    bfCat = 'Alto - Riesgo para la salud';
                                    bfColor = 'text-red-600';
                                }
                            }
                        }
                        bodyfatCategory.textContent = bfCat + ' (' + (gender === 'male' ? 'Hombre' : 'Mujer') + ', ' + age + ' años)';
                        bodyfatCategory.className = `text-xs font-medium ${bfColor}`;
                    } else {
                        displayBodyfat.textContent = '--';
                        bodyfatCategory.textContent = 'Ingresa cintura, cuello' + (gender === 'female' ? ' y cadera' : '');
                        bodyFatInput.value = '';
                    }

                    // 8. Calcular macros y calorías objetivo
                    calculateNutritionPlan(weight, tdeeValue);
                } else {
                    // Reset all displays
                    bmiInput.value = '';
                    document.getElementById('tmb').value = '';
                    document.getElementById('tdee').value = '';
                    document.getElementById('whr').value = '';
                    document.getElementById('wht').value = '';
                    document.getElementById('frame_index').value = '';
                    bodyFatInput.value = '';
                    displayBmi.textContent = '--';
                    bmiCategory.textContent = 'Ingresa peso y altura';
                    bmiIndicator.style.width = '0%';
                    displayTmb.textContent = '--';
                    displayTdee.textContent = '--';
                    tdeeDescription.textContent = 'Calorías diarias requeridas';
                    displayWhr.textContent = '--';
                    whrCategory.textContent = '-';
                    displayWht.textContent = '--';
                    whtCategory.textContent = '-';
                    displayFrame.textContent = '--';
                    frameCategory.textContent = '-';
                    displayBodyfat.textContent = '--';
                    bodyfatCategory.textContent = '-';
                }
            }

            // Función para calcular plan nutricional
            function calculateNutritionPlan(weight, tdee) {
                const goal = nutritionGoal.value;
                
                // Calcular calorías objetivo según el goal
                let targetCalories;
                let goalDesc;
                if (goal === 'deficit') {
                    targetCalories = Math.round(tdee - 500);
                    goalDesc = 'Déficit calórico';
                } else if (goal === 'surplus') {
                    targetCalories = Math.round(tdee + 300);
                    goalDesc = 'Superávit calórico';
                } else {
                    targetCalories = tdee;
                    goalDesc = 'Mantenimiento';
                }

                // Guardar calorías objetivo
                document.getElementById('target_calories').value = targetCalories;
                
                // Mostrar datos metabólicos
                document.getElementById('display-bmr').textContent = Math.round(tdee / 1.55) + ' cal';
                document.getElementById('display-tdee-nutrition').textContent = tdee + ' cal';
                document.getElementById('display-target-calories-main').textContent = targetCalories + ' cal';

                // Calcular proteína (2.2g/kg para déficit/superávit, 1.8g/kg para mantenimiento)
                const proteinMultiplier = (goal === 'maintenance') ? 1.8 : 2.2;
                const proteinGrams = Math.round(weight * proteinMultiplier);
                const proteinKcal = proteinGrams * 4;

                // Calcular grasas (27% del total calórico)
                const fatKcal = Math.round(targetCalories * 0.27);
                const fatGrams = Math.round(fatKcal / 9);

                // Calcular carbohidratos (resto de calorías)
                const carbsKcal = targetCalories - proteinKcal - fatKcal;
                const carbsGrams = Math.round(carbsKcal / 4);

                // Guardar valores
                document.getElementById('protein_grams').value = proteinGrams;
                document.getElementById('fat_grams').value = fatGrams;
                document.getElementById('carbs_grams').value = carbsGrams;

                // Mostrar macros diarias
                document.getElementById('display-protein-main').textContent = proteinGrams + 'g';
                document.getElementById('display-fat-main').textContent = fatGrams + 'g';
                document.getElementById('display-carbs-main').textContent = carbsGrams + 'g';

                // Calcular porcentajes
                const proteinPercent = Math.round((proteinKcal / targetCalories) * 100);
                const fatPercent = Math.round((fatKcal / targetCalories) * 100);
                const carbsPercent = 100 - proteinPercent - fatPercent;

                // Mostrar desglose de macros
                document.getElementById('protein-percent-display').textContent = '(' + proteinPercent + '%)';
                document.getElementById('protein-kcal-display').textContent = proteinKcal + ' cal';
                document.getElementById('fat-percent-display').textContent = '(' + fatPercent + '%)';
                document.getElementById('fat-kcal-display').textContent = fatKcal + ' cal';
                document.getElementById('carbs-percent-display').textContent = '(' + carbsPercent + '%)';
                document.getElementById('carbs-kcal-display').textContent = carbsKcal + ' cal';

                // Actualizar barras visuales
                document.getElementById('protein-bar').style.width = proteinPercent + '%';
                document.getElementById('fat-bar').style.width = fatPercent + '%';
                document.getElementById('carbs-bar').style.width = carbsPercent + '%';
            }

            // Función para actualizar el muñeco según el IMC
            function updateBMIAvatar(bmi) {
                const torso = document.getElementById('torso');
                const armLeft = document.getElementById('arm-left');
                const armRight = document.getElementById('arm-right');
                const legLeft = document.getElementById('leg-left');
                const legRight = document.getElementById('leg-right');
                const mouth = document.getElementById('mouth');
                
                if (!torso) return;

                if (bmi < 18.5) {
                    // Bajo peso - muy delgado
                    torso.setAttribute('rx', '12');
                    torso.setAttribute('ry', '22');
                    armLeft.setAttribute('stroke-width', '4');
                    armRight.setAttribute('stroke-width', '4');
                    legLeft.setAttribute('stroke-width', '5');
                    legRight.setAttribute('stroke-width', '5');
                    mouth.setAttribute('d', 'M 43 31 Q 50 28 57 31'); // Boca triste
                } else if (bmi < 25) {
                    // Normal - proporcionado
                    torso.setAttribute('rx', '18');
                    torso.setAttribute('ry', '25');
                    armLeft.setAttribute('stroke-width', '6');
                    armRight.setAttribute('stroke-width', '6');
                    legLeft.setAttribute('stroke-width', '8');
                    legRight.setAttribute('stroke-width', '8');
                    mouth.setAttribute('d', 'M 43 29 Q 50 32 57 29'); // Sonrisa
                } else if (bmi < 30) {
                    // Sobrepeso - más ancho
                    torso.setAttribute('rx', '23');
                    torso.setAttribute('ry', '28');
                    armLeft.setAttribute('stroke-width', '7');
                    armRight.setAttribute('stroke-width', '7');
                    legLeft.setAttribute('stroke-width', '10');
                    legRight.setAttribute('stroke-width', '10');
                    mouth.setAttribute('d', 'M 43 30 L 57 30'); // Boca neutral
                } else {
                    // Obesidad - más redondo
                    torso.setAttribute('rx', '28');
                    torso.setAttribute('ry', '30');
                    armLeft.setAttribute('stroke-width', '8');
                    armRight.setAttribute('stroke-width', '8');
                    legLeft.setAttribute('stroke-width', '12');
                    legRight.setAttribute('stroke-width', '12');
                    mouth.setAttribute('d', 'M 43 31 Q 50 28 57 31'); // Boca triste
                }
            }

            // Event listeners
            weightInputDisplay.addEventListener('input', calculateAll);
            weightUnit.addEventListener('change', calculateAll);
            heightInput.addEventListener('input', calculateAll);
            waistInput.addEventListener('input', calculateAll);
            hipInput.addEventListener('input', calculateAll);
            neckInput.addEventListener('input', calculateAll);
            wristInput.addEventListener('input', calculateAll);
            activityLevel.addEventListener('change', calculateAll);
            nutritionGoal.addEventListener('change', calculateAll);

            // Calcular inicial si hay valores
            if (weightInputDisplay.value && heightInput.value) {
                calculateAll();
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
            // Validar que se hayan calculado los valores necesarios
            const bmiValue = document.getElementById('bmi').value;
            const diagnosisValue = document.getElementById('diagnosis').value.trim();
            const recommendationsValue = document.getElementById('recommendations').value.trim();
            
            if (!bmiValue || bmiValue === '') {
                e.preventDefault();
                alert('Por favor, ingresa peso y altura para calcular el IMC antes de guardar.');
                return false;
            }
            
            if (!diagnosisValue || diagnosisValue === '') {
                e.preventDefault();
                alert('Por favor, completa el diagnóstico antes de guardar.');
                document.getElementById('diagnosis').focus();
                return false;
            }
            
            if (!recommendationsValue || recommendationsValue === '') {
                e.preventDefault();
                alert('Por favor, completa las recomendaciones antes de guardar.');
                document.getElementById('recommendations').focus();
                return false;
            }
            
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
