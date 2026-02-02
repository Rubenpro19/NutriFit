@extends('layouts.app')

@section('title', 'Historial Clínico - ' . $patient->name . ' - NutriFit')

@section('content')
<body class="min-h-screen bg-gradient-to-br from-green-50 via-emerald-50 to-rose-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 flex flex-col">
    @include('layouts.header')

    <main class="flex-grow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
            <!-- Breadcrumb -->
            <nav class="mb-6 flex items-center gap-2 text-sm">
                <a href="{{ route('nutricionista.dashboard') }}" class="text-green-600 dark:text-green-400 hover:underline transition-colors">Inicio</a>
                <span class="material-symbols-outlined text-gray-400 text-sm">chevron_right</span>
                <a href="{{ route('nutricionista.patients.index') }}" class="text-green-600 dark:text-green-400 hover:underline transition-colors">Mis Pacientes</a>
                <span class="material-symbols-outlined text-gray-400 text-sm">chevron_right</span>
                <a href="{{ route('nutricionista.patients.show', $patient) }}" class="text-green-600 dark:text-green-400 hover:underline transition-colors">Detalle del Paciente</a>
                <span class="material-symbols-outlined text-gray-400 text-sm">chevron_right</span>
                <span class="text-gray-700 dark:text-gray-300 font-medium">Historial Clínico</span>
            </nav>

            <!-- Header -->
            <div class="mb-8 bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-4 sm:p-6 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between gap-3">
                    <div class="flex items-center gap-3 flex-1 min-w-0">
                        <a href="{{ route('nutricionista.patients.show', $patient) }}" class="text-gray-700 dark:text-gray-300 hover:text-green-600 dark:hover:text-green-400 hover:scale-110 transition-all">
                            <span class="material-symbols-outlined text-2xl">arrow_back</span>
                        </a>
                        <div class="min-w-0 flex-1">
                            <h1 class="text-2xl sm:text-3xl font-bold bg-gradient-to-r from-green-600 to-emerald-600 dark:from-green-400 dark:to-emerald-400 bg-clip-text text-transparent">
                                Historial Clínico
                            </h1>
                            <p class="text-sm sm:text-base text-gray-700 dark:text-gray-300 mt-1 truncate">
                                Evolución completa del paciente
                            </p>
                        </div>
                    </div>
                    <div class="flex-shrink-0 hidden sm:flex items-center gap-3">
                        <div class="text-right">
                            <div class="text-3xl font-bold text-green-600 dark:text-green-400">{{ $attentions->count() }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400 font-medium">atenciones</div>
                        </div>
                        <span class="material-symbols-outlined text-3xl sm:text-4xl text-green-600 dark:text-green-400">monitoring</span>
                    </div>
                </div>
            </div>

            <div class="grid lg:grid-cols-1 gap-6" x-data="{ showPhotoModal: false }">
                <!-- Información del Paciente (Colapsable) -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 mb-2" x-data="{ expanded: false }">
                    <button @click="expanded = !expanded" class="w-full p-6 flex items-center justify-between text-left hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors rounded-2xl">
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                            <span class="material-symbols-outlined text-emerald-600">person</span>
                            Información del Paciente
                        </h2>
                        <span class="material-symbols-outlined text-gray-400 transition-transform duration-200" :class="{ 'rotate-180': expanded }">
                            expand_more
                        </span>
                    </button>
                    
                    <div x-show="expanded" 
                         x-collapse
                         class="border-t border-gray-200 dark:border-gray-700">
                        <div class="p-6">
                    
                    <!-- Foto y Nombre -->
                    <div class="mb-6 pb-4 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
                            <div class="w-20 h-20 rounded-full overflow-hidden flex items-center justify-center flex-shrink-0 shadow-lg {{ $patient->personalData?->profile_photo ? 'cursor-pointer hover:opacity-90 transition' : '' }}"
                                 @if($patient->personalData?->profile_photo) @click="showPhotoModal = true" @endif>
                                @if($patient->personalData?->profile_photo)
                                    <img src="{{ asset('storage/' . $patient->personalData->profile_photo) }}" 
                                         alt="{{ $patient->name }}" 
                                         class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-emerald-400 to-emerald-600 flex items-center justify-center text-white text-3xl font-bold">
                                        {{ $patient->initials() }}
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                    {{ $patient->name }}
                                </h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400 flex items-center gap-1 mt-1">
                                    <span class="material-symbols-outlined text-sm">email</span>
                                    <span class="truncate">{{ $patient->email }}</span>
                                </p>
                            </div>
                            <!-- Botón para ver perfil del paciente -->
                            <a href="{{ route('nutricionista.patients.show', $patient) }}" 
                               class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white text-sm font-semibold rounded-lg transition-all duration-200 shadow-md hover:shadow-lg flex-shrink-0">
                                <span class="material-symbols-outlined text-base">person</span>
                                Ver Perfil Completo
                            </a>
                        </div>
                    </div>

                    <!-- Grid de Información -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        @if($patient->personalData)
                        <!-- Cédula -->
                        <div class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                            <span class="material-symbols-outlined text-blue-500 text-xl">badge</span>
                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Cédula</p>
                                <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $patient->personalData->cedula }}</p>
                            </div>
                        </div>

                        <!-- Género -->
                        <div class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                            <span class="material-symbols-outlined text-purple-500 text-xl">wc</span>
                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Género</p>
                                <p class="text-sm font-semibold text-gray-900 dark:text-white">
                                    {{ $patient->personalData->gender === 'male' ? 'Masculino' : 'Femenino' }}
                                </p>
                            </div>
                        </div>

                        <!-- Fecha de Nacimiento -->
                        <div class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                            <span class="material-symbols-outlined text-green-500 text-xl">cake</span>
                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Fecha de Nacimiento</p>
                                <p class="text-sm font-semibold text-gray-900 dark:text-white">
                                    {{ \Carbon\Carbon::parse($patient->personalData->birth_date)->format('d/m/Y') }}
                                </p>
                            </div>
                        </div>

                        <!-- Dirección -->
                        <div class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                            <span class="material-symbols-outlined text-orange-500 text-xl">home</span>
                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Dirección</p>
                                <p class="text-sm font-semibold text-gray-900 dark:text-white truncate" title="{{ $patient->personalData->address }}">
                                    {{ $patient->personalData->address }}
                                </p>
                            </div>
                        </div>
                        @endif
                    </div>
                        </div>
                    </div>
                </div>

                <!-- Modal para ver foto ampliada -->
            <div x-show="showPhotoModal" 
                 @click.away="showPhotoModal = false"
                 x-cloak
                 class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-75 p-4"
                 style="display: none;">
                <div @click.stop class="relative max-w-3xl max-h-[90vh]">
                    <button @click="showPhotoModal = false" 
                            class="absolute -top-4 -right-4 bg-white dark:bg-gray-800 rounded-full p-2 shadow-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors z-10">
                        <span class="material-symbols-outlined text-gray-700 dark:text-gray-300">close</span>
                    </button>
                    @if($patient->personalData?->profile_photo)
                        <img src="{{ asset('storage/' . $patient->personalData->profile_photo) }}" 
                             alt="{{ $patient->name }}" 
                             class="max-w-full max-h-[90vh] rounded-lg shadow-2xl">
                    @endif
                </div>
            </div>
            </div>

            @if($attentions->isEmpty())
                <!-- Sin datos -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-12 text-center border border-gray-200 dark:border-gray-700">
                    <span class="material-symbols-outlined text-6xl text-gray-400 dark:text-gray-500 mb-4">analytics</span>
                    <h3 class="text-xl font-bold text-gray-700 dark:text-gray-300 mb-2">Sin historial clínico</h3>
                    <p class="text-gray-500 dark:text-gray-400">Este paciente aún no tiene atenciones registradas con medidas antropométricas.</p>
                </div>
            @else
                <!-- Tarjetas de Progreso -->
                @if($progressStats)
                <div class="mb-6">
                    <!-- Tarjetas Principales (siempre visibles) -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
                        <!-- Peso -->
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-5 border border-gray-200 dark:border-gray-700">
                            <div class="flex items-center justify-between mb-3">
                                <span class="text-gray-600 dark:text-gray-400 text-sm font-medium">Peso</span>
                                <span class="material-symbols-outlined text-blue-500">scale</span>
                            </div>
                            <div class="flex items-end gap-2">
                                <span class="text-3xl font-bold text-gray-900 dark:text-white">{{ $progressStats['weight']['current'] }}</span>
                                <span class="text-gray-500 dark:text-gray-400 text-sm mb-1">kg</span>
                            </div>
                            <div class="mt-2 flex items-center gap-2">
                                @php
                                    $weightChange = $progressStats['weight']['change'];
                                    $isPositive = $weightChange > 0;
                                @endphp
                                <span class="flex items-center gap-1 text-sm {{ $isPositive ? 'text-red-500' : ($weightChange < 0 ? 'text-green-500' : 'text-gray-500') }}">
                                    <span class="material-symbols-outlined text-sm">{{ $isPositive ? 'trending_up' : ($weightChange < 0 ? 'trending_down' : 'trending_flat') }}</span>
                                    {{ abs($weightChange) }} kg ({{ $progressStats['weight']['percentage'] }}%)
                                </span>
                                <span class="text-gray-400 text-xs">vs inicial</span>
                            </div>
                        </div>

                        <!-- IMC -->
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-5 border border-gray-200 dark:border-gray-700">
                            <div class="flex items-center justify-between mb-3">
                                <span class="text-gray-600 dark:text-gray-400 text-sm font-medium">IMC</span>
                                <span class="material-symbols-outlined text-purple-500">monitor_weight</span>
                            </div>
                            <div class="flex items-end gap-2">
                                <span class="text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($progressStats['bmi']['current'], 1) }}</span>
                            </div>
                            <div class="mt-2 flex items-center gap-2">
                                @php
                                    $bmiChange = $progressStats['bmi']['change'];
                                @endphp
                                <span class="flex items-center gap-1 text-sm {{ $bmiChange > 0 ? 'text-amber-500' : ($bmiChange < 0 ? 'text-green-500' : 'text-gray-500') }}">
                                    <span class="material-symbols-outlined text-sm">{{ $bmiChange > 0 ? 'trending_up' : ($bmiChange < 0 ? 'trending_down' : 'trending_flat') }}</span>
                                    {{ abs($bmiChange) }}
                                </span>
                                <span class="text-gray-400 text-xs">vs inicial ({{ $progressStats['bmi']['initial'] }})</span>
                            </div>
                        </div>

                        <!-- % Grasa -->
                        @if($progressStats['body_fat']['current'])
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-5 border border-gray-200 dark:border-gray-700">
                            <div class="flex items-center justify-between mb-3">
                                <span class="text-gray-600 dark:text-gray-400 text-sm font-medium">% Grasa Corporal</span>
                                <span class="material-symbols-outlined text-orange-500">water_drop</span>
                            </div>
                            <div class="flex items-end gap-2">
                                <span class="text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($progressStats['body_fat']['current'], 1) }}</span>
                                <span class="text-gray-500 dark:text-gray-400 text-sm mb-1">%</span>
                            </div>
                            @if($progressStats['body_fat']['change'] !== null)
                            <div class="mt-2 flex items-center gap-2">
                                @php
                                    $fatChange = $progressStats['body_fat']['change'];
                                @endphp
                                <span class="flex items-center gap-1 text-sm {{ $fatChange > 0 ? 'text-red-500' : ($fatChange < 0 ? 'text-green-500' : 'text-gray-500') }}">
                                    <span class="material-symbols-outlined text-sm">{{ $fatChange > 0 ? 'trending_up' : ($fatChange < 0 ? 'trending_down' : 'trending_flat') }}</span>
                                    {{ abs($fatChange) }}%
                                </span>
                                <span class="text-gray-400 text-xs">vs inicial</span>
                            </div>
                            @endif
                        </div>
                        @endif

                        <!-- Cintura -->
                        @if($progressStats['waist']['current'])
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-5 border border-gray-200 dark:border-gray-700">
                            <div class="flex items-center justify-between mb-3">
                                <span class="text-gray-600 dark:text-gray-400 text-sm font-medium">Cintura</span>
                                <span class="material-symbols-outlined text-teal-500">straighten</span>
                            </div>
                            <div class="flex items-end gap-2">
                                <span class="text-3xl font-bold text-gray-900 dark:text-white">{{ $progressStats['waist']['current'] }}</span>
                                <span class="text-gray-500 dark:text-gray-400 text-sm mb-1">cm</span>
                            </div>
                            @if($progressStats['waist']['change'] !== null)
                            <div class="mt-2 flex items-center gap-2">
                                @php
                                    $waistChange = $progressStats['waist']['change'];
                                @endphp
                                <span class="flex items-center gap-1 text-sm {{ $waistChange > 0 ? 'text-red-500' : ($waistChange < 0 ? 'text-green-500' : 'text-gray-500') }}">
                                    <span class="material-symbols-outlined text-sm">{{ $waistChange > 0 ? 'trending_up' : ($waistChange < 0 ? 'trending_down' : 'trending_flat') }}</span>
                                    {{ abs($waistChange) }} cm
                                </span>
                                <span class="text-gray-400 text-xs">vs inicial</span>
                            </div>
                            @endif
                        </div>
                        @endif
                    </div>

                    <!-- Botón para mostrar más métricas -->
                    <div x-data="{ showMore: false }">
                        <button @click="showMore = !showMore" class="w-full py-3 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-lg text-gray-700 dark:text-gray-300 font-medium transition-colors flex items-center justify-center gap-2">
                            <span x-text="showMore ? 'Ocultar métricas adicionales' : 'Ver más métricas'"></span>
                            <span class="material-symbols-outlined transition-transform" :class="showMore ? 'rotate-180' : ''">expand_more</span>
                        </button>

                        <!-- Tarjetas Adicionales (colapsables) -->
                        <div x-show="showMore" x-collapse class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mt-4">
                    @if($progressStats['hip']['current'])
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-5 border border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-gray-600 dark:text-gray-400 text-sm font-medium">Cadera</span>
                            <span class="material-symbols-outlined text-amber-500">accessibility</span>
                        </div>
                        <div class="flex items-end gap-2">
                            <span class="text-3xl font-bold text-gray-900 dark:text-white">{{ $progressStats['hip']['current'] }}</span>
                            <span class="text-gray-500 dark:text-gray-400 text-sm mb-1">cm</span>
                        </div>
                        @if($progressStats['hip']['change'] !== null)
                        <div class="mt-2 flex items-center gap-2">
                            @php
                                $hipChange = $progressStats['hip']['change'];
                            @endphp
                            <span class="flex items-center gap-1 text-sm {{ $hipChange > 0 ? 'text-amber-500' : ($hipChange < 0 ? 'text-green-500' : 'text-gray-500') }}">
                                <span class="material-symbols-outlined text-sm">{{ $hipChange > 0 ? 'trending_up' : ($hipChange < 0 ? 'trending_down' : 'trending_flat') }}</span>
                                {{ abs($hipChange) }} cm
                            </span>
                            <span class="text-gray-400 text-xs">vs inicial</span>
                        </div>
                        @endif
                    </div>
                    @endif

                    <!-- Cuello -->
                    @if($progressStats['neck']['current'])
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-5 border border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-gray-600 dark:text-gray-400 text-sm font-medium">Cuello</span>
                            <span class="material-symbols-outlined text-indigo-500">height</span>
                        </div>
                        <div class="flex items-end gap-2">
                            <span class="text-3xl font-bold text-gray-900 dark:text-white">{{ $progressStats['neck']['current'] }}</span>
                            <span class="text-gray-500 dark:text-gray-400 text-sm mb-1">cm</span>
                        </div>
                        @if($progressStats['neck']['change'] !== null)
                        <div class="mt-2 flex items-center gap-2">
                            @php
                                $neckChange = $progressStats['neck']['change'];
                            @endphp
                            <span class="flex items-center gap-1 text-sm {{ $neckChange > 0 ? 'text-red-500' : ($neckChange < 0 ? 'text-green-500' : 'text-gray-500') }}">
                                <span class="material-symbols-outlined text-sm">{{ $neckChange > 0 ? 'trending_up' : ($neckChange < 0 ? 'trending_down' : 'trending_flat') }}</span>
                                {{ abs($neckChange) }} cm
                            </span>
                            <span class="text-gray-400 text-xs">vs inicial</span>
                        </div>
                        @endif
                    </div>
                    @endif

                    <!-- Brazo Contraído -->
                    @if($progressStats['arm_contracted']['current'])
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-5 border border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-gray-600 dark:text-gray-400 text-sm font-medium">Brazo Contraído</span>
                            <span class="material-symbols-outlined text-pink-500">fitness_center</span>
                        </div>
                        <div class="flex items-end gap-2">
                            <span class="text-3xl font-bold text-gray-900 dark:text-white">{{ $progressStats['arm_contracted']['current'] }}</span>
                            <span class="text-gray-500 dark:text-gray-400 text-sm mb-1">cm</span>
                        </div>
                        @if($progressStats['arm_contracted']['change'] !== null)
                        <div class="mt-2 flex items-center gap-2">
                            @php
                                $armChange = $progressStats['arm_contracted']['change'];
                            @endphp
                            <span class="flex items-center gap-1 text-sm {{ $armChange > 0 ? 'text-green-500' : ($armChange < 0 ? 'text-red-500' : 'text-gray-500') }}">
                                <span class="material-symbols-outlined text-sm">{{ $armChange > 0 ? 'trending_up' : ($armChange < 0 ? 'trending_down' : 'trending_flat') }}</span>
                                {{ abs($armChange) }} cm
                            </span>
                            <span class="text-gray-400 text-xs">vs inicial</span>
                        </div>
                        @endif
                    </div>
                    @endif

                    <!-- Muslo -->
                    @if($progressStats['thigh']['current'])
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-5 border border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-gray-600 dark:text-gray-400 text-sm font-medium">Muslo</span>
                            <span class="material-symbols-outlined text-cyan-500">airline_seat_legroom_normal</span>
                        </div>
                        <div class="flex items-end gap-2">
                            <span class="text-3xl font-bold text-gray-900 dark:text-white">{{ $progressStats['thigh']['current'] }}</span>
                            <span class="text-gray-500 dark:text-gray-400 text-sm mb-1">cm</span>
                        </div>
                        @if($progressStats['thigh']['change'] !== null)
                        <div class="mt-2 flex items-center gap-2">
                            @php
                                $thighChange = $progressStats['thigh']['change'];
                            @endphp
                            <span class="flex items-center gap-1 text-sm {{ $thighChange > 0 ? 'text-amber-500' : ($thighChange < 0 ? 'text-green-500' : 'text-gray-500') }}">
                                <span class="material-symbols-outlined text-sm">{{ $thighChange > 0 ? 'trending_up' : ($thighChange < 0 ? 'trending_down' : 'trending_flat') }}</span>
                                {{ abs($thighChange) }} cm
                            </span>
                            <span class="text-gray-400 text-xs">vs inicial</span>
                        </div>
                        @endif
                    </div>
                    @endif

                    <!-- TMB -->
                    @if($progressStats['tmb']['current'])
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-5 border border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-gray-600 dark:text-gray-400 text-sm font-medium">TMB</span>
                            <span class="material-symbols-outlined text-green-500">local_fire_department</span>
                        </div>
                        <div class="flex items-end gap-2">
                            <span class="text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($progressStats['tmb']['current'], 0) }}</span>
                            <span class="text-gray-500 dark:text-gray-400 text-sm mb-1">kcal</span>
                        </div>
                        @if($progressStats['tmb']['change'] !== null)
                        <div class="mt-2 flex items-center gap-2">
                            @php
                                $tmbChange = $progressStats['tmb']['change'];
                            @endphp
                            <span class="flex items-center gap-1 text-sm {{ $tmbChange > 0 ? 'text-green-500' : ($tmbChange < 0 ? 'text-red-500' : 'text-gray-500') }}">
                                <span class="material-symbols-outlined text-sm">{{ $tmbChange > 0 ? 'trending_up' : ($tmbChange < 0 ? 'trending_down' : 'trending_flat') }}</span>
                                {{ abs($tmbChange) }} kcal
                            </span>
                            <span class="text-gray-400 text-xs">vs inicial</span>
                        </div>
                        @endif
                    </div>
                    @endif

                    <!-- TDEE -->
                    @if($progressStats['tdee']['current'])
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-5 border border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-gray-600 dark:text-gray-400 text-sm font-medium">TDEE</span>
                            <span class="material-symbols-outlined text-orange-600">bolt</span>
                        </div>
                        <div class="flex items-end gap-2">
                            <span class="text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($progressStats['tdee']['current'], 0) }}</span>
                            <span class="text-gray-500 dark:text-gray-400 text-sm mb-1">kcal</span>
                        </div>
                        @if($progressStats['tdee']['change'] !== null)
                        <div class="mt-2 flex items-center gap-2">
                            @php
                                $tdeeChange = $progressStats['tdee']['change'];
                            @endphp
                            <span class="flex items-center gap-1 text-sm {{ $tdeeChange > 0 ? 'text-green-500' : ($tdeeChange < 0 ? 'text-red-500' : 'text-gray-500') }}">
                                <span class="material-symbols-outlined text-sm">{{ $tdeeChange > 0 ? 'trending_up' : ($tdeeChange < 0 ? 'trending_down' : 'trending_flat') }}</span>
                                {{ abs($tdeeChange) }} kcal
                            </span>
                            <span class="text-gray-400 text-xs">vs inicial</span>
                        </div>
                        @endif
                    </div>
                    @endif
                        </div>
                    </div>
                </div>
                @endif

                <!-- Gráficas Organizadas por Categorías -->
                <div class="space-y-4 mb-6" x-data="{ 
                    basicas: true, 
                    circunferencias: false, 
                    metabolismo: false, 
                    indices: false 
                }">
                    <!-- Gráficas Básicas (Siempre expandidas por defecto) -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <button @click="basicas = !basicas" class="w-full px-6 py-4 flex items-center justify-between bg-gradient-to-r from-blue-50 to-purple-50 dark:from-gray-700 dark:to-gray-700 hover:from-blue-100 hover:to-purple-100 dark:hover:from-gray-600 dark:hover:to-gray-600 transition-colors">
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-blue-600 dark:text-blue-400">monitoring</span>
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Métricas Básicas</h3>
                                <span class="text-sm text-gray-500 dark:text-gray-400">(Peso, IMC, % Grasa)</span>
                            </div>
                            <span class="material-symbols-outlined transition-transform text-gray-600 dark:text-gray-400" :class="basicas ? 'rotate-180' : ''">expand_more</span>
                        </button>
                        <div x-show="basicas" x-collapse class="p-6">
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                <!-- Gráfica de Peso -->
                                <div>
                                    <h4 class="text-md font-semibold text-gray-900 dark:text-white mb-3 flex items-center gap-2">
                                        <span class="material-symbols-outlined text-blue-500 text-xl">scale</span>
                                        Evolución del Peso
                                    </h4>
                                    <div class="h-64">
                                        <canvas id="weightChart"></canvas>
                                    </div>
                                </div>

                                <!-- Gráfica de IMC -->
                                <div>
                                    <h4 class="text-md font-semibold text-gray-900 dark:text-white mb-3 flex items-center gap-2">
                                        <span class="material-symbols-outlined text-purple-500 text-xl">monitor_weight</span>
                                        Evolución del IMC
                                    </h4>
                                    <div class="h-64">
                                        <canvas id="bmiChart"></canvas>
                                    </div>
                                </div>

                                <!-- Gráfica de % Grasa Corporal -->
                                @if(array_filter($chartData['bodyFats']))
                                <div>
                                    <h4 class="text-md font-semibold text-gray-900 dark:text-white mb-3 flex items-center gap-2">
                                        <span class="material-symbols-outlined text-orange-500 text-xl">water_drop</span>
                                        % Grasa Corporal
                                    </h4>
                                    <div class="h-64">
                                        <canvas id="bodyFatChart"></canvas>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Circunferencias Corporales -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <button @click="circunferencias = !circunferencias" class="w-full px-6 py-4 flex items-center justify-between bg-gradient-to-r from-teal-50 to-cyan-50 dark:from-gray-700 dark:to-gray-700 hover:from-teal-100 hover:to-cyan-100 dark:hover:from-gray-600 dark:hover:to-gray-600 transition-colors">
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-teal-600 dark:text-teal-400">straighten</span>
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Circunferencias Corporales</h3>
                                <span class="text-sm text-gray-500 dark:text-gray-400">(Cintura, Cadera, Cuello, Brazos, Piernas)</span>
                            </div>
                            <span class="material-symbols-outlined transition-transform text-gray-600 dark:text-gray-400" :class="circunferencias ? 'rotate-180' : ''">expand_more</span>
                        </button>
                        <div x-show="circunferencias" x-collapse class="p-6">
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                <!-- Cintura y Cadera -->
                                @if(array_filter($chartData['waists']))
                                <div>
                                    <h4 class="text-md font-semibold text-gray-900 dark:text-white mb-3 flex items-center gap-2">
                                        <span class="material-symbols-outlined text-teal-500 text-xl">straighten</span>
                                        Cintura y Cadera
                                    </h4>
                                    <div class="h-64">
                                        <canvas id="measurementsChart"></canvas>
                                    </div>
                                </div>
                                @endif

                                <!-- Cuello y Muñeca -->
                                @if(array_filter($chartData['necks']) || array_filter($chartData['wrists']))
                                <div>
                                    <h4 class="text-md font-semibold text-gray-900 dark:text-white mb-3 flex items-center gap-2">
                                        <span class="material-symbols-outlined text-indigo-500 text-xl">height</span>
                                        Cuello y Muñeca
                                    </h4>
                                    <div class="h-64">
                                        <canvas id="neckWristChart"></canvas>
                                    </div>
                                </div>
                                @endif

                                <!-- Brazos -->
                                @if(array_filter($chartData['armContracted']) || array_filter($chartData['armRelaxed']))
                                <div>
                                    <h4 class="text-md font-semibold text-gray-900 dark:text-white mb-3 flex items-center gap-2">
                                        <span class="material-symbols-outlined text-pink-500 text-xl">fitness_center</span>
                                        Brazo (Contraído / Relajado)
                                    </h4>
                                    <div class="h-64">
                                        <canvas id="armsChart"></canvas>
                                    </div>
                                </div>
                                @endif

                                <!-- Muslo y Pantorrilla -->
                                @if(array_filter($chartData['thighs']) || array_filter($chartData['calves']))
                                <div>
                                    <h4 class="text-md font-semibold text-gray-900 dark:text-white mb-3 flex items-center gap-2">
                                        <span class="material-symbols-outlined text-cyan-500 text-xl">accessibility</span>
                                        Muslo y Pantorrilla
                                    </h4>
                                    <div class="h-64">
                                        <canvas id="legsChart"></canvas>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Metabolismo y Energía -->
                    @if(array_filter($chartData['tmbs']) || array_filter($chartData['tdees']))
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <button @click="metabolismo = !metabolismo" class="w-full px-6 py-4 flex items-center justify-between bg-gradient-to-r from-green-50 to-emerald-50 dark:from-gray-700 dark:to-gray-700 hover:from-green-100 hover:to-emerald-100 dark:hover:from-gray-600 dark:hover:to-gray-600 transition-colors">
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-green-600 dark:text-green-400">local_fire_department</span>
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Metabolismo y Gasto Energético</h3>
                                <span class="text-sm text-gray-500 dark:text-gray-400">(TMB, TDEE)</span>
                            </div>
                            <span class="material-symbols-outlined transition-transform text-gray-600 dark:text-gray-400" :class="metabolismo ? 'rotate-180' : ''">expand_more</span>
                        </button>
                        <div x-show="metabolismo" x-collapse class="p-6">
                            <div class="grid grid-cols-1 gap-6">
                                <div>
                                    <h4 class="text-md font-semibold text-gray-900 dark:text-white mb-3 flex items-center gap-2">
                                        <span class="material-symbols-outlined text-green-500 text-xl">local_fire_department</span>
                                        TMB y Gasto Energético Total
                                    </h4>
                                    <div class="h-64">
                                        <canvas id="metabolismChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Índices de Salud -->
                    @if(array_filter($chartData['whrs']) || array_filter($chartData['whts']))
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <button @click="indices = !indices" class="w-full px-6 py-4 flex items-center justify-between bg-gradient-to-r from-amber-50 to-yellow-50 dark:from-gray-700 dark:to-gray-700 hover:from-amber-100 hover:to-yellow-100 dark:hover:from-gray-600 dark:hover:to-gray-600 transition-colors">
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-amber-600 dark:text-amber-400">analytics</span>
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Índices de Salud y Riesgo</h3>
                                <span class="text-sm text-gray-500 dark:text-gray-400">(WHR, WHtR)</span>
                            </div>
                            <span class="material-symbols-outlined transition-transform text-gray-600 dark:text-gray-400" :class="indices ? 'rotate-180' : ''">expand_more</span>
                        </button>
                        <div x-show="indices" x-collapse class="p-6">
                            <div class="grid grid-cols-1 gap-6">
                                <div>
                                    <h4 class="text-md font-semibold text-gray-900 dark:text-white mb-3 flex items-center gap-2">
                                        <span class="material-symbols-outlined text-amber-500 text-xl">analytics</span>
                                        Índices de Salud (WHR / WHtR)
                                    </h4>
                                    <div class="h-64">
                                        <canvas id="indicesChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Tabla de Historial -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden border border-gray-200 dark:border-gray-700">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                            <span class="material-symbols-outlined text-green-500">history</span>
                            Historial de Atenciones
                        </h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-100 dark:divide-gray-600">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Fecha</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Peso</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">IMC</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">% Grasa</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Cintura</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Cadera</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Kcal/día</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Objetivo</th>
                                    <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Acción</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-100 dark:divide-gray-600">
                                @foreach($attentions->reverse() as $attention)
                                    @php $data = $attention->attentionData; @endphp
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $attention->created_at->format('d/m/Y') }}</div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ $attention->created_at->format('H:i') }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white font-semibold">
                                            {{ $data->weight }} kg
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $bmi = $data->bmi;
                                                $bmiClass = $bmi < 18.5 ? 'text-blue-600 bg-blue-100' : ($bmi < 25 ? 'text-green-600 bg-green-100' : ($bmi < 30 ? 'text-amber-600 bg-amber-100' : 'text-red-600 bg-red-100'));
                                            @endphp
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $bmiClass }}">
                                                {{ number_format($bmi, 1) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                            {{ $data->body_fat ? number_format($data->body_fat, 1) . '%' : '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                            {{ $data->waist ? $data->waist . ' cm' : '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                            {{ $data->hip ? $data->hip . ' cm' : '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white font-semibold">
                                            {{ $data->target_calories ? number_format($data->target_calories, 0) : '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($data->nutrition_goal)
                                                @php
                                                    $goalColors = [
                                                        'deficit' => 'text-red-600 bg-red-100 dark:bg-red-900/30',
                                                        'maintenance' => 'text-blue-600 bg-blue-100 dark:bg-blue-900/30',
                                                        'surplus' => 'text-green-600 bg-green-100 dark:bg-green-900/30',
                                                    ];
                                                    $goalLabels = [
                                                        'deficit' => 'Déficit',
                                                        'maintenance' => 'Mantenimiento',
                                                        'surplus' => 'Superávit',
                                                    ];
                                                @endphp
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $goalColors[$data->nutrition_goal] ?? 'bg-gray-100' }}">
                                                    {{ $goalLabels[$data->nutrition_goal] ?? $data->nutrition_goal }}
                                                </span>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <a href="{{ route('nutricionista.appointments.show', $attention->appointment_id) }}" 
                                               class="inline-flex items-center gap-1 px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white text-xs font-semibold rounded-lg transition-colors duration-200 shadow-sm hover:shadow-md">
                                                <span class="material-symbols-outlined text-sm">visibility</span>
                                                Ver Detalles
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </main>

    @include('layouts.footer')
</body>
@endsection

@push('scripts')
<script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const chartData = @json($chartData);
    
    // Configuración común
    const commonOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            x: {
                grid: {
                    display: false
                },
                ticks: {
                    color: document.documentElement.classList.contains('dark') ? '#9CA3AF' : '#6B7280'
                }
            },
            y: {
                grid: {
                    color: document.documentElement.classList.contains('dark') ? '#374151' : '#E5E7EB'
                },
                ticks: {
                    color: document.documentElement.classList.contains('dark') ? '#9CA3AF' : '#6B7280'
                }
            }
        }
    };

    // Gráfica de Peso
    if (document.getElementById('weightChart')) {
        new Chart(document.getElementById('weightChart'), {
            type: 'line',
            data: {
                labels: chartData.labels,
                datasets: [{
                    label: 'Peso (kg)',
                    data: chartData.weights,
                    borderColor: '#3B82F6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 5,
                    pointBackgroundColor: '#3B82F6'
                }]
            },
            options: commonOptions
        });
    }

    // Gráfica de IMC
    if (document.getElementById('bmiChart')) {
        new Chart(document.getElementById('bmiChart'), {
            type: 'line',
            data: {
                labels: chartData.labels,
                datasets: [{
                    label: 'IMC',
                    data: chartData.bmis,
                    borderColor: '#8B5CF6',
                    backgroundColor: 'rgba(139, 92, 246, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 5,
                    pointBackgroundColor: '#8B5CF6'
                }]
            },
            options: {
                ...commonOptions,
                plugins: {
                    ...commonOptions.plugins,
                    annotation: {
                        annotations: {
                            normalRange: {
                                type: 'box',
                                yMin: 18.5,
                                yMax: 25,
                                backgroundColor: 'rgba(34, 197, 94, 0.1)',
                                borderWidth: 0
                            }
                        }
                    }
                }
            }
        });
    }

    // Gráfica de Medidas Corporales
    if (document.getElementById('measurementsChart')) {
        new Chart(document.getElementById('measurementsChart'), {
            type: 'line',
            data: {
                labels: chartData.labels,
                datasets: [{
                    label: 'Cintura (cm)',
                    data: chartData.waists,
                    borderColor: '#14B8A6',
                    backgroundColor: 'transparent',
                    borderWidth: 2,
                    tension: 0.4,
                    pointRadius: 4,
                    pointBackgroundColor: '#14B8A6'
                }, {
                    label: 'Cadera (cm)',
                    data: chartData.hips,
                    borderColor: '#F59E0B',
                    backgroundColor: 'transparent',
                    borderWidth: 2,
                    tension: 0.4,
                    pointRadius: 4,
                    pointBackgroundColor: '#F59E0B'
                }]
            },
            options: {
                ...commonOptions,
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom',
                        labels: {
                            color: document.documentElement.classList.contains('dark') ? '#9CA3AF' : '#6B7280'
                        }
                    }
                }
            }
        });
    }

    // Gráfica de % Grasa Corporal
    if (document.getElementById('bodyFatChart')) {
        new Chart(document.getElementById('bodyFatChart'), {
            type: 'line',
            data: {
                labels: chartData.labels,
                datasets: [{
                    label: '% Grasa',
                    data: chartData.bodyFats,
                    borderColor: '#F97316',
                    backgroundColor: 'rgba(249, 115, 22, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 5,
                    pointBackgroundColor: '#F97316'
                }]
            },
            options: commonOptions
        });
    }

    // Gráfica de Cuello y Muñeca
    if (document.getElementById('neckWristChart')) {
        new Chart(document.getElementById('neckWristChart'), {
            type: 'line',
            data: {
                labels: chartData.labels,
                datasets: [{
                    label: 'Cuello (cm)',
                    data: chartData.necks,
                    borderColor: '#6366F1',
                    backgroundColor: 'transparent',
                    borderWidth: 2,
                    tension: 0.4,
                    pointRadius: 4,
                    pointBackgroundColor: '#6366F1'
                }, {
                    label: 'Muñeca (cm)',
                    data: chartData.wrists,
                    borderColor: '#D946EF',
                    backgroundColor: 'transparent',
                    borderWidth: 2,
                    tension: 0.4,
                    pointRadius: 4,
                    pointBackgroundColor: '#D946EF'
                }]
            },
            options: {
                ...commonOptions,
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom',
                        labels: {
                            color: document.documentElement.classList.contains('dark') ? '#9CA3AF' : '#6B7280'
                        }
                    }
                }
            }
        });
    }

    // Gráfica de Brazos
    if (document.getElementById('armsChart')) {
        new Chart(document.getElementById('armsChart'), {
            type: 'line',
            data: {
                labels: chartData.labels,
                datasets: [{
                    label: 'Brazo Contraído (cm)',
                    data: chartData.armContracted,
                    borderColor: '#EC4899',
                    backgroundColor: 'transparent',
                    borderWidth: 2,
                    tension: 0.4,
                    pointRadius: 4,
                    pointBackgroundColor: '#EC4899'
                }, {
                    label: 'Brazo Relajado (cm)',
                    data: chartData.armRelaxed,
                    borderColor: '#A855F7',
                    backgroundColor: 'transparent',
                    borderWidth: 2,
                    tension: 0.4,
                    pointRadius: 4,
                    pointBackgroundColor: '#A855F7'
                }]
            },
            options: {
                ...commonOptions,
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom',
                        labels: {
                            color: document.documentElement.classList.contains('dark') ? '#9CA3AF' : '#6B7280'
                        }
                    }
                }
            }
        });
    }

    // Gráfica de Muslo y Pantorrilla
    if (document.getElementById('legsChart')) {
        new Chart(document.getElementById('legsChart'), {
            type: 'line',
            data: {
                labels: chartData.labels,
                datasets: [{
                    label: 'Muslo (cm)',
                    data: chartData.thighs,
                    borderColor: '#06B6D4',
                    backgroundColor: 'transparent',
                    borderWidth: 2,
                    tension: 0.4,
                    pointRadius: 4,
                    pointBackgroundColor: '#06B6D4'
                }, {
                    label: 'Pantorrilla (cm)',
                    data: chartData.calves,
                    borderColor: '#7C3AED',
                    backgroundColor: 'transparent',
                    borderWidth: 2,
                    tension: 0.4,
                    pointRadius: 4,
                    pointBackgroundColor: '#7C3AED'
                }]
            },
            options: {
                ...commonOptions,
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom',
                        labels: {
                            color: document.documentElement.classList.contains('dark') ? '#9CA3AF' : '#6B7280'
                        }
                    }
                }
            }
        });
    }

    // Gráfica de TMB y TDEE
    if (document.getElementById('metabolismChart')) {
        new Chart(document.getElementById('metabolismChart'), {
            type: 'line',
            data: {
                labels: chartData.labels,
                datasets: [{
                    label: 'TMB (kcal/día)',
                    data: chartData.tmbs,
                    borderColor: '#10B981',
                    backgroundColor: 'transparent',
                    borderWidth: 2,
                    tension: 0.4,
                    pointRadius: 4,
                    pointBackgroundColor: '#10B981'
                }, {
                    label: 'TDEE (kcal/día)',
                    data: chartData.tdees,
                    borderColor: '#F97316',
                    backgroundColor: 'transparent',
                    borderWidth: 2,
                    tension: 0.4,
                    pointRadius: 4,
                    pointBackgroundColor: '#F97316'
                }]
            },
            options: {
                ...commonOptions,
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom',
                        labels: {
                            color: document.documentElement.classList.contains('dark') ? '#9CA3AF' : '#6B7280'
                        }
                    }
                }
            }
        });
    }

    // Gráfica de Índices (WHR y WHtR)
    if (document.getElementById('indicesChart')) {
        new Chart(document.getElementById('indicesChart'), {
            type: 'line',
            data: {
                labels: chartData.labels,
                datasets: [{
                    label: 'WHR (Índice Cintura-Cadera)',
                    data: chartData.whrs,
                    borderColor: '#F59E0B',
                    backgroundColor: 'transparent',
                    borderWidth: 2,
                    tension: 0.4,
                    pointRadius: 4,
                    pointBackgroundColor: '#F59E0B'
                }, {
                    label: 'WHtR (Índice Cintura-Altura)',
                    data: chartData.whts,
                    borderColor: '#EF4444',
                    backgroundColor: 'transparent',
                    borderWidth: 2,
                    tension: 0.4,
                    pointRadius: 4,
                    pointBackgroundColor: '#EF4444'
                }]
            },
            options: {
                ...commonOptions,
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom',
                        labels: {
                            color: document.documentElement.classList.contains('dark') ? '#9CA3AF' : '#6B7280'
                        }
                    }
                }
            }
        });
    }
});
</script>
@endpush
