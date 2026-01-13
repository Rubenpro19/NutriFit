@extends('layouts.app')

@section('title', 'Registrar Atención- NutriFit')

@section('content')

    <body
        class="min-h-screen bg-gradient-to-br from-green-50 via-emerald-50 to-rose-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 flex flex-col">
        @include('layouts.header')

        <main class="flex-grow">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
                <!-- Breadcrumb -->
                <nav class="mb-6 flex items-center gap-2 text-sm">
                    <a href="{{ route('nutricionista.dashboard') }}"
                        class="text-green-600 dark:text-green-400 hover:underline transition-colors">Inicio</a>
                    <span class="material-symbols-outlined text-gray-400 text-sm">chevron_right</span>
                    <a href="{{ route('nutricionista.appointments.show', $appointment) }}"
                        class="text-green-600 dark:text-green-400 hover:underline transition-colors">Detalle de Cita</a>
                    <span class="material-symbols-outlined text-gray-400 text-sm">chevron_right</span>
                    <span class="text-gray-700 dark:text-gray-300 font-medium">Registrar Atención</span>
                </nav>

                <!-- Header -->
                <div
                    class="mb-8 bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-4 sm:p-6 border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between gap-3">
                        <div class="flex items-center gap-3 flex-1 min-w-0">
                            <a href="{{ route('nutricionista.appointments.show', $appointment) }}"
                                class="text-gray-700 dark:text-gray-300 hover:text-green-600 dark:hover:text-green-400 hover:scale-110 transition-all">
                                <span class="material-symbols-outlined text-2xl">arrow_back</span>
                            </a>
                            <div class="min-w-0 flex-1">
                                <h1
                                    class="text-2xl sm:text-3xl font-bold bg-gradient-to-r from-green-600 to-emerald-600 dark:from-green-400 dark:to-emerald-400 bg-clip-text text-transparent">
                                    Registrar Atención
                                </h1>
                                <div class="mt-2 space-y-1">
                                    <p class="text-sm sm:text-base text-gray-700 dark:text-gray-300">
                                        <span class="material-symbols-outlined text-sm align-middle">person</span>
                                        <span class="font-semibold">{{ $appointment->paciente->name }}</span>
                                    </p>
                                    <div class="flex flex-wrap gap-3 text-xs sm:text-sm text-gray-600 dark:text-gray-400">
                                        <span class="flex items-center gap-1">
                                            <span class="material-symbols-outlined text-sm">wc</span>
                                            {{ $appointment->paciente->personalData->gender === 'male' ? 'Masculino' : 'Femenino' }}
                                        </span>
                                        <span class="flex items-center gap-1">
                                            <span class="material-symbols-outlined text-sm">cake</span>
                                            {{ $appointment->paciente->personalData->age }} años
                                        </span>
                                        <span
                                            class="flex items-center gap-1 bg-blue-50 dark:bg-blue-900/20 px-2 py-1 rounded-md">
                                            <span
                                                class="material-symbols-outlined text-sm text-blue-600 dark:text-blue-400">event</span>
                                            <span class="font-medium text-blue-700 dark:text-blue-300">Cita:</span>
                                            {{ \Carbon\Carbon::parse($appointment->start_time)->format('d/m/Y H:i') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="flex-shrink-0 hidden sm:flex items-center">
                            <span
                                class="material-symbols-outlined text-3xl sm:text-4xl text-green-600 dark:text-green-400">health_and_safety</span>
                        </div>
                    </div>

                    <!-- Indicador de Borrador Guardado -->
                    <div id="draft-indicator"
                        class="hidden mt-4 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-xl p-3"
                        x-data="{ showClearModal: false }">
                        <div class="flex items-center justify-between gap-3">
                            <div class="flex items-center gap-2 text-amber-800 dark:text-amber-300">
                                <span class="material-symbols-outlined text-lg">save</span>
                                <span class="text-sm font-medium">Borrador guardado automáticamente</span>
                                <span id="draft-timestamp" class="text-xs opacity-75"></span>
                            </div>
                            <button type="button" @click="showClearModal = true"
                                class="text-xs text-amber-700 dark:text-amber-400 hover:text-amber-900 dark:hover:text-amber-200 underline transition">
                                Limpiar borrador
                            </button>
                        </div>

                        <!-- Modal de Confirmación para Limpiar Borrador -->
                        <div x-show="showClearModal" x-cloak
                            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/30 backdrop-blur-sm"
                            @click.self="showClearModal = false">
                            <div @click.away="showClearModal = false"
                                class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full p-6 transform transition-all"
                                x-transition:enter="transition ease-out duration-300"
                                x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-200"
                                x-transition:leave-start="opacity-100 scale-100"
                                x-transition:leave-end="opacity-0 scale-90">

                                <div class="text-center mb-6">
                                    <div
                                        class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-amber-100 dark:bg-amber-900/30 mb-4">
                                        <span
                                            class="material-symbols-outlined text-4xl text-amber-600 dark:text-amber-400">warning</span>
                                    </div>
                                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">
                                        ¿Eliminar el borrador guardado?
                                    </h3>
                                    <p class="text-gray-600 dark:text-gray-400">
                                        Se eliminarán todos los datos guardados automáticamente de este formulario. Esta
                                        acción no se puede deshacer.
                                    </p>
                                </div>

                                <div class="flex gap-3">
                                    <button type="button" @click="showClearModal = false"
                                        class="flex-1 px-4 py-3 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 font-semibold rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                                        No, mantener
                                    </button>
                                    <button type="button" @click="clearDraftConfirmed(); showClearModal = false"
                                        class="flex-1 px-4 py-3 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition flex items-center justify-center gap-2">
                                        <span class="material-symbols-outlined text-base">delete</span>
                                        Sí, eliminar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @if(session('error'))
                    <div
                        class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-800 dark:text-red-200 rounded-xl p-4 mb-6">
                        {{ session('error') }}
                    </div>
                @endif

                <!-- Grid principal: Formulario + Panel de Resultados -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Columna Izquierda: Formulario (2/3) -->
                    <div class="lg:col-span-2 order-1">
                        <form method="POST" action="{{ route('nutricionista.attentions.store', $appointment) }}"
                            id="attention-form">
                            @csrf

                            <!-- Datos Antropométricos -->
                            <div
                                class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 border border-gray-200 dark:border-gray-700 mb-6">
                                <h2
                                    class="text-xl font-semibold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
                                    <span class="material-symbols-outlined text-emerald-600">straighten</span>
                                    Datos Antropométricos
                                </h2>

                                <div class="grid md:grid-cols-2 gap-6">
                                    <!-- Peso -->
                                    <div>
                                        <label for="weight-input"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Peso <span class="text-red-500">*</span>
                                        </label>
                                        <div class="flex gap-2">
                                            <input type="number" step="0.01" id="weight-input" value="{{ old('weight') }}"
                                                class="flex-1 px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-gray-900 dark:text-white transition @error('weight') border-red-500 @enderror"
                                                placeholder="70.5" required>
                                            <select id="weight-unit"
                                                class="px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-gray-900 dark:text-white transition">
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
                                        <label for="height"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Altura (cm) <span class="text-red-500">*</span>
                                        </label>
                                        <input type="number" step="0.01" id="height" name="height"
                                            value="{{ old('height') }}"
                                            class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-gray-900 dark:text-white transition @error('height') border-red-500 @enderror"
                                            placeholder="170" required>
                                        @error('height')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Cintura -->
                                    <div>
                                        <label for="waist"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Cintura (cm) <span class="text-red-500">*</span>
                                        </label>
                                        <input type="number" step="0.01" id="waist" name="waist" value="{{ old('waist') }}"
                                            class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-gray-900 dark:text-white transition @error('waist') border-red-500 @enderror"
                                            placeholder="75" required>
                                        @error('waist')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Cadera -->
                                    <div>
                                        <label for="hip"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Cadera (cm) <span class="text-red-500">*</span>
                                        </label>
                                        <input type="number" step="0.01" id="hip" name="hip" value="{{ old('hip') }}"
                                            class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-gray-900 dark:text-white transition @error('hip') border-red-500 @enderror"
                                            placeholder="95" required>
                                        @error('hip')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Cuello -->
                                    <div>
                                        <label for="neck"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Cuello (cm) <span class="text-red-500">*</span>
                                        </label>
                                        <input type="number" step="0.01" id="neck" name="neck" value="{{ old('neck') }}"
                                            class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-gray-900 dark:text-white transition @error('neck') border-red-500 @enderror"
                                            placeholder="35" required>
                                        @error('neck')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Muñeca -->
                                    <div>
                                        <label for="wrist"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Muñeca (cm) <span class="text-red-500">*</span>
                                        </label>
                                        <input type="number" step="0.01" id="wrist" name="wrist" value="{{ old('wrist') }}"
                                            class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-gray-900 dark:text-white transition @error('wrist') border-red-500 @enderror"
                                            placeholder="16" required>
                                        @error('wrist')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Brazo Contraído -->
                                    <div>
                                        <label for="arm_contracted"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Brazo Contraído (cm) <span class="text-red-500">*</span>
                                        </label>
                                        <input type="number" step="0.01" id="arm_contracted" name="arm_contracted"
                                            value="{{ old('arm_contracted') }}"
                                            class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-gray-900 dark:text-white transition @error('arm_contracted') border-red-500 @enderror"
                                            placeholder="35" required>
                                        @error('arm_contracted')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Brazo Relajado -->
                                    <div>
                                        <label for="arm_relaxed"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Brazo Relajado (cm) <span class="text-red-500">*</span>
                                        </label>
                                        <input type="number" step="0.01" id="arm_relaxed" name="arm_relaxed"
                                            value="{{ old('arm_relaxed') }}"
                                            class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-gray-900 dark:text-white transition @error('arm_relaxed') border-red-500 @enderror"
                                            placeholder="30" required>
                                        @error('arm_relaxed')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Pierna -->
                                    <div>
                                        <label for="thigh"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Pierna (cm) <span class="text-red-500">*</span>
                                        </label>
                                        <input type="number" step="0.01" id="thigh" name="thigh" value="{{ old('thigh') }}"
                                            class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-gray-900 dark:text-white transition @error('thigh') border-red-500 @enderror"
                                            placeholder="55" required>
                                        @error('thigh')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Pantorrilla -->
                                    <div>
                                        <label for="calf"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Pantorrilla (cm) <span class="text-red-500">*</span>
                                        </label>
                                        <input type="number" step="0.01" id="calf" name="calf" value="{{ old('calf') }}"
                                            class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-gray-900 dark:text-white transition @error('calf') border-red-500 @enderror"
                                            placeholder="38" required>
                                        @error('calf')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Nivel de Actividad Física -->
                                    <div>
                                        <label for="activity_level"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Nivel de Actividad Física <span class="text-red-500">*</span>
                                        </label>
                                        <select id="activity_level" name="activity_level"
                                            class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-gray-900 dark:text-white transition @error('activity_level') border-red-500 @enderror"
                                            required>
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
                                        <label for="nutrition_goal"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Objetivo Nutricional <span class="text-red-500">*</span>
                                        </label>
                                        <select id="nutrition_goal" name="nutrition_goal"
                                            class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-gray-900 dark:text-white transition">
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
                                <input type="hidden" id="target_calories" name="target_calories"
                                    value="{{ old('target_calories') }}">
                                <input type="hidden" id="protein_grams" name="protein_grams"
                                    value="{{ old('protein_grams') }}">
                                <input type="hidden" id="fat_grams" name="fat_grams" value="{{ old('fat_grams') }}">
                                <input type="hidden" id="carbs_grams" name="carbs_grams" value="{{ old('carbs_grams') }}">
                <input type="hidden" id="protein_percentage" name="protein_percentage"
                    value="{{ old('protein_percentage') }}">
                <input type="hidden" id="fat_percentage" name="fat_percentage"
                    value="{{ old('fat_percentage') }}">
                <input type="hidden" id="carbs_percentage" name="carbs_percentage"
                    value="{{ old('carbs_percentage') }}">
                <input type="hidden" id="total_calories_equivalents" name="total_calories_equivalents"
                    value="{{ old('total_calories_equivalents') }}">
                                <div class="text-center mb-6">
                                    <h2
                                        class="text-2xl font-bold text-gray-900 dark:text-white flex items-center justify-center gap-2 mb-2">
                                        <span class="material-symbols-outlined text-emerald-600 text-3xl">bar_chart</span>
                                        TUS RESULTADOS
                                    </h2>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">En función de sus aportes, aquí
                                        están sus objetivos nutricionales calculados</p>
                                </div>

                                <div class="grid md:grid-cols-3 gap-6">
                                    <!-- Sección: Datos Metabólicos -->
                                    <div
                                        class="bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-xl p-5 border border-blue-200 dark:border-blue-700">
                                        <div class="flex items-center gap-2 mb-4">
                                            <span
                                                class="material-symbols-outlined text-blue-600 dark:text-blue-400">show_chart</span>
                                            <h4 class="text-base font-bold text-gray-900 dark:text-white">DATOS METABÓLICOS
                                            </h4>
                                        </div>
                                        <div class="space-y-3">
                                            <div class="flex justify-between items-center">
                                                <span class="text-sm text-gray-700 dark:text-gray-300">BMR:</span>
                                                <span id="display-bmr"
                                                    class="text-lg font-bold text-blue-600 dark:text-blue-400">-- cal</span>
                                            </div>
                                            <div class="flex justify-between items-center">
                                                <span class="text-sm text-gray-700 dark:text-gray-300">TDEE:</span>
                                                <span id="display-tdee-nutrition"
                                                    class="text-lg font-bold text-blue-600 dark:text-blue-400">-- cal</span>
                                            </div>
                                            <div
                                                class="flex justify-between items-center pt-2 border-t border-blue-200 dark:border-blue-600">
                                                <span
                                                    class="text-sm font-semibold text-gray-800 dark:text-gray-200">Calorías
                                                    objetivo:</span>
                                                <span id="display-target-calories-main"
                                                    class="text-xl font-bold text-blue-700 dark:text-blue-300">-- cal</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Sección: Macros Diarias -->
                                    <div
                                        class="bg-gradient-to-br from-purple-50 to-pink-50 dark:from-purple-900/20 dark:to-pink-900/20 rounded-xl p-5 border border-purple-200 dark:border-purple-700">
                                        <div class="flex items-center gap-2 mb-4">
                                            <span
                                                class="material-symbols-outlined text-purple-600 dark:text-purple-400">restaurant</span>
                                            <h4 class="text-base font-bold text-gray-900 dark:text-white">MACROS DIARIAS
                                            </h4>
                                        </div>
                                        <div class="space-y-3">
                                            <div class="flex justify-between items-center">
                                                <span class="text-sm text-gray-700 dark:text-gray-300">Proteína:</span>
                                                <span id="display-protein-main"
                                                    class="text-lg font-bold text-purple-600 dark:text-purple-400">--g</span>
                                            </div>
                                            <div class="flex justify-between items-center">
                                                <span class="text-sm text-gray-700 dark:text-gray-300">Grasas:</span>
                                                <span id="display-fat-main"
                                                    class="text-lg font-bold text-purple-600 dark:text-purple-400">--g</span>
                                            </div>
                                            <div class="flex justify-between items-center">
                                                <span class="text-sm text-gray-700 dark:text-gray-300">Carbohidratos:</span>
                                                <span id="display-carbs-main"
                                                    class="text-lg font-bold text-purple-600 dark:text-purple-400">--g</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Sección: Desglose de Macros -->
                                    <div
                                        class="bg-gradient-to-br from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 rounded-xl p-5 border border-green-200 dark:border-green-700">
                                        <div class="flex items-center gap-2 mb-4">
                                            <span
                                                class="material-symbols-outlined text-green-600 dark:text-green-400">pie_chart</span>
                                            <h4 class="text-base font-bold text-gray-900 dark:text-white">DESGLOSE DE MACROS
                                            </h4>
                                        </div>
                                        <div class="space-y-3">
                                            <div>
                                                <div class="flex justify-between items-center mb-1">
                                                    <span class="text-sm text-gray-700 dark:text-gray-300">Proteína (<span
                                                            id="protein-percent-display">--</span>):</span>
                                                    <span id="protein-kcal-display"
                                                        class="text-sm font-bold text-green-600 dark:text-green-400">--
                                                        cal</span>
                                                </div>
                                                <div class="h-2 bg-gray-200 dark:bg-gray-600 rounded-full overflow-hidden">
                                                    <div id="protein-bar"
                                                        class="h-full bg-blue-500 transition-all duration-500"
                                                        style="width: 0%"></div>
                                                </div>
                                            </div>
                                            <div>
                                                <div class="flex justify-between items-center mb-1">
                                                    <span class="text-sm text-gray-700 dark:text-gray-300">Grasas (<span
                                                            id="fat-percent-display">--</span>):</span>
                                                    <span id="fat-kcal-display"
                                                        class="text-sm font-bold text-green-600 dark:text-green-400">--
                                                        cal</span>
                                                </div>
                                                <div class="h-2 bg-gray-200 dark:bg-gray-600 rounded-full overflow-hidden">
                                                    <div id="fat-bar"
                                                        class="h-full bg-orange-500 transition-all duration-500"
                                                        style="width: 0%"></div>
                                                </div>
                                            </div>
                                            <div>
                                                <div class="flex justify-between items-center mb-1">
                                                    <span class="text-sm text-gray-700 dark:text-gray-300">Carbohidratos
                                                        (<span id="carbs-percent-display">--</span>):</span>
                                                    <span id="carbs-kcal-display"
                                                        class="text-sm font-bold text-green-600 dark:text-green-400">--
                                                        cal</span>
                                                </div>
                                                <div class="h-2 bg-gray-200 dark:bg-gray-600 rounded-full overflow-hidden">
                                                    <div id="carbs-bar"
                                                        class="h-full bg-purple-500 transition-all duration-500"
                                                        style="width: 0%"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Distribución de Equivalentes -->
                            <div
                                class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 border border-gray-200 dark:border-gray-700 mt-6">
                                <h2
                                    class="text-xl font-semibold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
                                    <span class="material-symbols-outlined text-emerald-600">restaurant_menu</span>
                                    Distribución de Equivalentes
                                </h2>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-4">
                                    <!-- Cereales -->
                                    <div
                                        class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                                        <label for="eq_cereales"
                                            class="text-sm font-medium text-gray-700 dark:text-gray-300 flex-1">
                                            Cereales <span class="text-xs text-gray-500">(73 kcal)</span>
                                        </label>
                                        <div class="flex items-center gap-3">
                                            <input type="number" id="eq_cereales" name="eq_cereales" min="0" step="0.5"
                                                class="w-20 px-3 py-2 bg-white dark:bg-gray-600 border border-gray-300 dark:border-gray-500 rounded-lg text-center font-bold focus:ring-2 focus:ring-emerald-500 q-input"
                                                placeholder="0">
                                            <span
                                                class="text-sm font-bold text-emerald-600 dark:text-emerald-400 w-16 text-right"
                                                id="kcal_cereales">0</span>
                                        </div>
                                    </div>

                                    <!-- Verduras -->
                                    <div
                                        class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                                        <label for="eq_verduras"
                                            class="text-sm font-medium text-gray-700 dark:text-gray-300 flex-1">
                                            Verduras <span class="text-xs text-gray-500">(24 kcal)</span>
                                        </label>
                                        <div class="flex items-center gap-3">
                                            <input type="number" id="eq_verduras" name="eq_verduras" min="0" step="0.5"
                                                class="w-20 px-3 py-2 bg-white dark:bg-gray-600 border border-gray-300 dark:border-gray-500 rounded-lg text-center font-bold focus:ring-2 focus:ring-emerald-500 q-input"
                                                placeholder="0">
                                            <span
                                                class="text-sm font-bold text-emerald-600 dark:text-emerald-400 w-16 text-right"
                                                id="kcal_verduras">0</span>
                                        </div>
                                    </div>

                                    <!-- Frutas -->
                                    <div
                                        class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                                        <label for="eq_frutas"
                                            class="text-sm font-medium text-gray-700 dark:text-gray-300 flex-1">
                                            Frutas <span class="text-xs text-gray-500">(60 kcal)</span>
                                        </label>
                                        <div class="flex items-center gap-3">
                                            <input type="number" id="eq_frutas" name="eq_frutas" min="0" step="0.5"
                                                class="w-20 px-3 py-2 bg-white dark:bg-gray-600 border border-gray-300 dark:border-gray-500 rounded-lg text-center font-bold focus:ring-2 focus:ring-emerald-500 q-input"
                                                placeholder="0">
                                            <span
                                                class="text-sm font-bold text-emerald-600 dark:text-emerald-400 w-16 text-right"
                                                id="kcal_frutas">0</span>
                                        </div>
                                    </div>

                                    <!-- Lácteo Semidescremado -->
                                    <div
                                        class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                                        <label for="eq_lacteo"
                                            class="text-sm font-medium text-gray-700 dark:text-gray-300 flex-1">
                                            Lácteo Semi. <span class="text-xs text-gray-500">(111 kcal)</span>
                                        </label>
                                        <div class="flex items-center gap-3">
                                            <input type="number" id="eq_lacteo" name="eq_lacteo" min="0" step="0.5"
                                                class="w-20 px-3 py-2 bg-white dark:bg-gray-600 border border-gray-300 dark:border-gray-500 rounded-lg text-center font-bold focus:ring-2 focus:ring-emerald-500 q-input"
                                                placeholder="0">
                                            <span
                                                class="text-sm font-bold text-emerald-600 dark:text-emerald-400 w-16 text-right"
                                                id="kcal_lacteo">0</span>
                                        </div>
                                    </div>

                                    <!-- Origen Animal -->
                                    <div
                                        class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                                        <label for="eq_animal"
                                            class="text-sm font-medium text-gray-700 dark:text-gray-300 flex-1">
                                            Origen Animal <span class="text-xs text-gray-500">(46 kcal)</span>
                                        </label>
                                        <div class="flex items-center gap-3">
                                            <input type="number" id="eq_animal" name="eq_animal" min="0" step="0.5"
                                                class="w-20 px-3 py-2 bg-white dark:bg-gray-600 border border-gray-300 dark:border-gray-500 rounded-lg text-center font-bold focus:ring-2 focus:ring-emerald-500 q-input"
                                                placeholder="0">
                                            <span
                                                class="text-sm font-bold text-emerald-600 dark:text-emerald-400 w-16 text-right"
                                                id="kcal_animal">0</span>
                                        </div>
                                    </div>

                                    <!-- Aceites y Grasas -->
                                    <div
                                        class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                                        <label for="eq_aceites"
                                            class="text-sm font-medium text-gray-700 dark:text-gray-300 flex-1">
                                            Aceites <span class="text-xs text-gray-500">(45 kcal)</span>
                                        </label>
                                        <div class="flex items-center gap-3">
                                            <input type="number" id="eq_aceites" name="eq_aceites" min="0" step="0.5"
                                                class="w-20 px-3 py-2 bg-white dark:bg-gray-600 border border-gray-300 dark:border-gray-500 rounded-lg text-center font-bold focus:ring-2 focus:ring-emerald-500 q-input"
                                                placeholder="0">
                                            <span
                                                class="text-sm font-bold text-emerald-600 dark:text-emerald-400 w-16 text-right"
                                                id="kcal_aceites">0</span>
                                        </div>
                                    </div>

                                    <!-- Grasas con Proteína -->
                                    <div
                                        class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                                        <label for="eq_grasas_prot"
                                            class="text-sm font-medium text-gray-700 dark:text-gray-300 flex-1">
                                            Grasas c/Prot <span class="text-xs text-gray-500">(69 kcal)</span>
                                        </label>
                                        <div class="flex items-center gap-3">
                                            <input type="number" id="eq_grasas_prot" name="eq_grasas_prot" min="0"
                                                step="0.5"
                                                class="w-20 px-3 py-2 bg-white dark:bg-gray-600 border border-gray-300 dark:border-gray-500 rounded-lg text-center font-bold focus:ring-2 focus:ring-emerald-500 q-input"
                                                placeholder="0">
                                            <span
                                                class="text-sm font-bold text-emerald-600 dark:text-emerald-400 w-16 text-right"
                                                id="kcal_grasas_prot">0</span>
                                        </div>
                                    </div>

                                    <!-- Leguminosas -->
                                    <div
                                        class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                                        <label for="eq_leguminosas"
                                            class="text-sm font-medium text-gray-700 dark:text-gray-300 flex-1">
                                            Leguminosas <span class="text-xs text-gray-500">(121 kcal)</span>
                                        </label>
                                        <div class="flex items-center gap-3">
                                            <input type="number" id="eq_leguminosas" name="eq_leguminosas" min="0"
                                                step="0.5"
                                                class="w-20 px-3 py-2 bg-white dark:bg-gray-600 border border-gray-300 dark:border-gray-500 rounded-lg text-center font-bold focus:ring-2 focus:ring-emerald-500 q-input"
                                                placeholder="0">
                                            <span
                                                class="text-sm font-bold text-emerald-600 dark:text-emerald-400 w-16 text-right"
                                                id="kcal_leguminosas">0</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Total Calorías Equivalentes -->
                                <div
                                    class="mt-6 p-4 bg-emerald-50 dark:bg-emerald-900/20 rounded-xl border border-emerald-100 dark:border-emerald-800 flex justify-between items-center">
                                    <span class="text-emerald-900 dark:text-emerald-100 font-semibold">Total Calorías
                                        Equivalentes:</span>
                                    <span id="total_kcal_equivalents"
                                        class="text-xl font-bold text-emerald-600 dark:text-emerald-400">0 kcal</span>
                                </div>
                            </div>
                            <div
                                class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 border border-gray-200 dark:border-gray-700 mt-6">
                                <h2
                                    class="text-xl font-semibold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
                                    <span class="material-symbols-outlined text-emerald-600">clinical_notes</span>
                                    Notas Clínicas
                                </h2>

                                <!-- Diagnóstico -->
                                <div class="mb-6">
                                    <label for="diagnosis"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Diagnóstico <span class="text-red-500">*</span>
                                    </label>
                                    <textarea id="diagnosis" name="diagnosis" rows="6"
                                        class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-gray-900 dark:text-white transition @error('diagnosis') border-red-500 @enderror"
                                        placeholder="Describe el diagnóstico del paciente..."
                                        required>{{ old('diagnosis') }}</textarea>
                                    @error('diagnosis')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Recomendaciones -->
                                <div>
                                    <label for="recommendations"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Recomendaciones <span class="text-red-500">*</span>
                                    </label>
                                    <textarea id="recommendations" name="recommendations" rows="8"
                                        class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-gray-900 dark:text-white transition @error('recommendations') border-red-500 @enderror"
                                        placeholder="Plan nutricional, ejercicios recomendados, cambios de hábitos..."
                                        required>{{ old('recommendations') }}</textarea>
                                    @error('recommendations')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Botones de Acción -->
                            <div class="mt-6">
                                <div class="flex gap-4">
                                    <a href="{{ route('nutricionista.appointments.show', $appointment) }}" id="cancel-btn"
                                        class="flex-1 text-center bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 font-semibold py-3 px-6 rounded-xl hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                                        Cancelar
                                    </a>
                                    <button type="submit" id="submit-btn"
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
                        <div
                            class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 border border-gray-200 dark:border-gray-700 lg:sticky lg:top-8">
                            <h2
                                class="text-xl font-semibold text-gray-900 dark:text-white mb-4 flex items-center justify-center gap-2">
                                <span class="material-symbols-outlined text-blue-600">analytics</span>
                                Análisis Antropométrico Completo
                            </h2>

                            <!-- Muñeco IMC -->
                            <div class="flex justify-center mb-6">
                                <svg id="bmi-avatar" width="80" height="120" viewBox="0 0 100 150"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <!-- Cabeza -->
                                    <circle cx="50" cy="25" r="15" fill="#FDB8B8" stroke="#333" stroke-width="1.5" />

                                    <!-- Torso -->
                                    <ellipse id="torso" cx="50" cy="65" rx="18" ry="25" fill="#4A90E2" stroke="#333"
                                        stroke-width="1.5" />

                                    <!-- Brazos -->
                                    <line id="arm-left" x1="32" y1="55" x2="20" y2="75" stroke="#FDB8B8" stroke-width="6"
                                        stroke-linecap="round" />
                                    <line id="arm-right" x1="68" y1="55" x2="80" y2="75" stroke="#FDB8B8" stroke-width="6"
                                        stroke-linecap="round" />

                                    <!-- Piernas -->
                                    <line id="leg-left" x1="43" y1="90" x2="40" y2="120" stroke="#4A90E2" stroke-width="8"
                                        stroke-linecap="round" />
                                    <line id="leg-right" x1="57" y1="90" x2="60" y2="120" stroke="#4A90E2" stroke-width="8"
                                        stroke-linecap="round" />

                                    <!-- Pies -->
                                    <ellipse cx="40" cy="125" rx="8" ry="4" fill="#333" />
                                    <ellipse cx="60" cy="125" rx="8" ry="4" fill="#333" />

                                    <!-- Ojos -->
                                    <circle cx="45" cy="23" r="2" fill="#333" />
                                    <circle cx="55" cy="23" r="2" fill="#333" />

                                    <!-- Boca (sonrisa por defecto) -->
                                    <path id="mouth" d="M 43 29 Q 50 32 57 29" stroke="#333" stroke-width="1.5" fill="none"
                                        stroke-linecap="round" />
                                </svg>
                            </div>

                            <div class="space-y-4">
                                <!-- IMC -->
                                <div
                                    class="bg-gradient-to-br from-blue-50 to-cyan-50 dark:from-blue-900/20 dark:to-cyan-900/20 rounded-xl p-4 border border-blue-200 dark:border-blue-700">
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">IMC</span>
                                        <span id="display-bmi"
                                            class="text-2xl font-bold text-blue-600 dark:text-blue-400">--</span>
                                    </div>
                                    <div class="h-2 bg-gray-200 dark:bg-gray-600 rounded-full overflow-hidden mb-2">
                                        <div id="bmi-indicator" class="h-full bg-blue-500 transition-all duration-500"
                                            style="width: 0%"></div>
                                    </div>
                                    <p id="bmi-category" class="text-xs font-medium text-gray-600 dark:text-gray-400">-</p>
                                </div>

                                <!-- TMB -->
                                <div
                                    class="bg-gradient-to-br from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 rounded-xl p-4 border border-green-200 dark:border-green-700">
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">TMB</span>
                                        <span id="display-tmb"
                                            class="text-xl font-bold text-green-600 dark:text-green-400">--</span>
                                    </div>
                                    <p class="text-xs font-medium text-gray-600 dark:text-gray-400">Tasa Metabólica Basal
                                        (kcal/día)</p>
                                </div>

                                <!-- Gasto Energético -->
                                <div
                                    class="bg-gradient-to-br from-purple-50 to-pink-50 dark:from-purple-900/20 dark:to-pink-900/20 rounded-xl p-4 border border-purple-200 dark:border-purple-700">
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">Gasto
                                            Energético</span>
                                        <span id="display-tdee"
                                            class="text-xl font-bold text-purple-600 dark:text-purple-400">--</span>
                                    </div>
                                    <p class="text-xs font-medium text-gray-600 dark:text-gray-400" id="tdee-description">
                                        Calorías diarias requeridas</p>
                                </div>

                                <!-- Índice Cintura-Cadera -->
                                <div
                                    class="bg-gradient-to-br from-orange-50 to-red-50 dark:from-orange-900/20 dark:to-red-900/20 rounded-xl p-4 border border-orange-200 dark:border-orange-700">
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">Índice
                                            Cintura-Cadera</span>
                                        <span id="display-whr"
                                            class="text-xl font-bold text-orange-600 dark:text-orange-400">--</span>
                                    </div>
                                    <p id="whr-category" class="text-xs font-medium text-gray-600 dark:text-gray-400">-</p>
                                </div>

                                <!-- Índice Cintura-Altura -->
                                <div
                                    class="bg-gradient-to-br from-yellow-50 to-amber-50 dark:from-yellow-900/20 dark:to-amber-900/20 rounded-xl p-4 border border-yellow-200 dark:border-yellow-700">
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">Índice
                                            Cintura-Altura</span>
                                        <span id="display-wht"
                                            class="text-xl font-bold text-yellow-600 dark:text-yellow-400">--</span>
                                    </div>
                                    <p id="wht-category" class="text-xs font-medium text-gray-600 dark:text-gray-400">-</p>
                                </div>

                                <!-- Complexión Ósea -->
                                <div
                                    class="bg-gradient-to-br from-indigo-50 to-blue-50 dark:from-indigo-900/20 dark:to-blue-900/20 rounded-xl p-4 border border-indigo-200 dark:border-indigo-700">
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">Complexión
                                            Ósea</span>
                                        <span id="display-frame"
                                            class="text-xl font-bold text-indigo-600 dark:text-indigo-400">--</span>
                                    </div>
                                    <p id="frame-category" class="text-xs font-medium text-gray-600 dark:text-gray-400">-
                                    </p>
                                </div>

                                <!-- Porcentaje de Grasa -->
                                <div
                                    class="bg-gradient-to-br from-rose-50 to-pink-50 dark:from-rose-900/20 dark:to-pink-900/20 rounded-xl p-4 border border-rose-200 dark:border-rose-700">
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">% Grasa
                                            Corporal</span>
                                        <span id="display-bodyfat"
                                            class="text-xl font-bold text-rose-600 dark:text-rose-400">--</span>
                                    </div>
                                    <p id="bodyfat-category" class="text-xs font-medium text-gray-600 dark:text-gray-400">-
                                    </p>
                                </div>

                                <!-- Info del Paciente (para cálculos) -->
                                <input type="hidden" id="patient-gender"
                                    value="{{ $appointment->paciente->personalData->gender ?? 'male' }}">
                                <input type="hidden" id="patient-age"
                                    value="{{ $appointment->paciente->personalData->age ?? 30 }}">
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Script para cálculos antropométricos completos -->
            @vite('resources/js/attention-calculator.js')

            <!-- Script para autoguardado de borrador -->
            <script>
                // ID único del borrador basado en la cita
                const DRAFT_KEY = 'attention_draft_{{ $appointment->id }}';
                const DRAFT_TIMESTAMP_KEY = 'attention_draft_timestamp_{{ $appointment->id }}';

                // Elementos del formulario a guardar
                const formFields = [
                    'weight-input', 'weight-unit', 'height', 'waist', 'hip', 'neck', 'wrist',
                    'arm_contracted', 'arm_relaxed', 'thigh', 'calf', 'activity_level',
                    'nutrition_goal', 'diagnosis', 'recommendations'
                ];

                // Función para guardar borrador
                function saveDraft() {
                    const draftData = {};
                    let hasData = false;

                    formFields.forEach(fieldId => {
                        const field = document.getElementById(fieldId);
                        if (field && field.value) {
                            draftData[fieldId] = field.value;
                            hasData = true;
                        }
                    });

                    if (hasData) {
                        localStorage.setItem(DRAFT_KEY, JSON.stringify(draftData));
                        localStorage.setItem(DRAFT_TIMESTAMP_KEY, new Date().toISOString());
                        updateDraftIndicator();
                    }
                }

                // Función para cargar borrador
                function loadDraft() {
                    const draftData = localStorage.getItem(DRAFT_KEY);

                    if (draftData) {
                        try {
                            const data = JSON.parse(draftData);
                            let loadedAny = false;

                            formFields.forEach(fieldId => {
                                const field = document.getElementById(fieldId);
                                // Solo cargar si el campo está vacío (no tiene old() de Laravel)
                                if (field && data[fieldId] !== undefined) {
                                    // Para campos que ya tienen valor de old(), no sobrescribir
                                    if (!field.value || field.value === field.defaultValue) {
                                        field.value = data[fieldId];
                                        loadedAny = true;
                                    }
                                }
                            });

                            if (loadedAny) {
                                updateDraftIndicator();
                                // Disparar evento change en los campos para recalcular
                                const heightField = document.getElementById('height');
                                if (heightField) {
                                    heightField.dispatchEvent(new Event('input', { bubbles: true }));
                                }

                                // Disparar change en los selects también
                                const activityLevel = document.getElementById('activity_level');
                                const nutritionGoal = document.getElementById('nutrition_goal');
                                if (activityLevel) activityLevel.dispatchEvent(new Event('change', { bubbles: true }));
                                if (nutritionGoal) nutritionGoal.dispatchEvent(new Event('change', { bubbles: true }));
                            }
                        } catch (e) {
                            console.error('Error al cargar borrador:', e);
                        }
                    }
                }

                // Función para actualizar el indicador visual
                function updateDraftIndicator() {
                    const indicator = document.getElementById('draft-indicator');
                    const timestamp = localStorage.getItem(DRAFT_TIMESTAMP_KEY);

                    if (timestamp) {
                        const date = new Date(timestamp);
                        const now = new Date();
                        const diffMinutes = Math.floor((now - date) / 60000);

                        let timeText = '';
                        if (diffMinutes < 1) {
                            timeText = 'hace unos segundos';
                        } else if (diffMinutes < 60) {
                            timeText = `hace ${diffMinutes} minuto${diffMinutes > 1 ? 's' : ''}`;
                        } else {
                            const hours = Math.floor(diffMinutes / 60);
                            timeText = `hace ${hours} hora${hours > 1 ? 's' : ''}`;
                        }

                        document.getElementById('draft-timestamp').textContent = `(${timeText})`;
                        indicator.classList.remove('hidden');
                    }
                }

                // Función para limpiar borrador (llamada desde el modal)
                function clearDraftConfirmed() {
                    localStorage.removeItem(DRAFT_KEY);
                    localStorage.removeItem(DRAFT_TIMESTAMP_KEY);
                    document.getElementById('draft-indicator').classList.add('hidden');

                    // Mostrar notificación de éxito
                    const notification = document.createElement('div');
                    notification.className = 'fixed top-20 right-4 bg-green-500 text-white px-6 py-3 rounded-xl shadow-lg z-50 flex items-center gap-2';
                    notification.innerHTML = '<span class="material-symbols-outlined">check_circle</span><span>Borrador eliminado correctamente</span>';
                    document.body.appendChild(notification);

                    // Animación de salida
                    setTimeout(() => {
                        notification.style.opacity = '0';
                        notification.style.transform = 'translateX(100%)';
                        notification.style.transition = 'all 0.3s ease-in-out';
                    }, 2500);

                    setTimeout(() => notification.remove(), 3000);
                }

                // Exponer la función globalmente para que Alpine.js pueda acceder a ella
                window.clearDraftConfirmed = clearDraftConfirmed;

                // Autoguardar cada vez que cambia un campo (con debounce)
                let saveTimeout;
                let formSubmitted = false; // Bandera para evitar guardar después de enviar
                
                function debouncedSave() {
                    // No guardar si el formulario ya fue enviado
                    if (formSubmitted) return;
                    
                    clearTimeout(saveTimeout);
                    saveTimeout = setTimeout(saveDraft, 1000); // Guardar 1 segundo después del último cambio
                }

                // Event listeners
                document.addEventListener('DOMContentLoaded', function () {
                    // Cargar borrador al iniciar
                    loadDraft();

                    // Agregar listeners a todos los campos
                    formFields.forEach(fieldId => {
                        const field = document.getElementById(fieldId);
                        if (field) {
                            field.addEventListener('input', debouncedSave);
                            field.addEventListener('change', debouncedSave);
                        }
                    });

                    // Limpiar borrador al enviar el formulario exitosamente
                    document.getElementById('attention-form').addEventListener('submit', function (e) {
                        // Marcar que el formulario fue enviado
                        formSubmitted = true;
                        
                        // Limpiar el borrador cuando se envía el formulario
                        localStorage.removeItem(DRAFT_KEY);
                        localStorage.removeItem(DRAFT_TIMESTAMP_KEY);
                        
                        // Ocultar el indicador
                        const indicator = document.getElementById('draft-indicator');
                        if (indicator) {
                            indicator.classList.add('hidden');
                        }
                    });
                });

                // Actualizar timestamp cada minuto
                setInterval(updateDraftIndicator, 60000);
            </script>

            </div>
        </main>
        @include('layouts.footer')
    </body>
@endsection