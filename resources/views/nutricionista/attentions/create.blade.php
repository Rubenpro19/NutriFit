@extends('layouts.app')

@section('title', 'Atender Cita - NutriFit')

@section('content')
<body class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-green-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
    @include('layouts.header')

    <main class="container mx-auto px-4 py-8">
    <!-- Breadcrumb -->
    <nav class="mb-6 text-sm text-gray-600 dark:text-gray-400">
        <a href="{{ route('nutricionista.dashboard') }}" class="hover:text-emerald-600 dark:hover:text-emerald-400">Dashboard</a>
        <span class="mx-2">/</span>
        <a href="{{ route('nutricionista.appointments.show', $appointment) }}" class="hover:text-emerald-600 dark:hover:text-emerald-400">Detalle de Cita</a>
        <span class="mx-2">/</span>
        <span class="text-gray-900 dark:text-white">Registrar Atención</span>
    </nav>

        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">
                Registrar Atención
            </h1>
            <p class="text-gray-600 dark:text-gray-400">
                Paciente: <span class="font-semibold text-gray-900 dark:text-white">{{ $appointment->paciente->name }}</span>
            </p>
            <p class="text-sm text-gray-500 dark:text-gray-400">
                Cita: {{ \Carbon\Carbon::parse($appointment->start_time)->format('d/m/Y H:i') }}
            </p>
        </div>

        @if(session('error'))
            <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-800 dark:text-red-200 rounded-xl p-4 mb-6">
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('nutricionista.attentions.store', $appointment) }}" class="max-w-4xl">
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
                            Peso (kg) <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="number" 
                            step="0.01" 
                            id="weight" 
                            name="weight" 
                            value="{{ old('weight') }}"
                            class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-gray-900 dark:text-white transition @error('weight') border-red-500 @enderror"
                            placeholder="70.5"
                            required
                        >
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
                   class="flex-1 text-center bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 font-semibold py-3 px-6 rounded-xl hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                    Cancelar
                </a>
                <button type="submit" 
                        class="flex-1 bg-gradient-to-r from-blue-600 to-emerald-600 text-white font-semibold py-3 px-6 rounded-xl hover:from-blue-700 hover:to-emerald-700 transition shadow-lg flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined">save</span>
                    Guardar Atención
                </button>
            </div>
        </form>
    </div>

    <!-- Script para cálculo automático del IMC -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const weightInput = document.getElementById('weight');
            const heightInput = document.getElementById('height');
            const bmiInput = document.getElementById('bmi');

            function calculateBMI() {
                const weight = parseFloat(weightInput.value);
                const height = parseFloat(heightInput.value);

                if (weight > 0 && height > 0) {
                    // IMC = peso (kg) / (altura (m))^2
                    const heightInMeters = height / 100;
                    const bmi = weight / (heightInMeters * heightInMeters);
                    bmiInput.value = bmi.toFixed(2);
                } else {
                    bmiInput.value = '';
                }
            }

            weightInput.addEventListener('input', calculateBMI);
            heightInput.addEventListener('input', calculateBMI);

            // Calcular IMC inicial si hay valores (por ejemplo, de old())
            if (weightInput.value && heightInput.value) {
                calculateBMI();
            }
        });
    </script>

    </main>
    @include('layouts.footer')
</body>
@endsection
