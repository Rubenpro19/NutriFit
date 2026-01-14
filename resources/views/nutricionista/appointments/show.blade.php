@extends('layouts.app')

@section('title', 'Detalle de Cita - NutriFit')

@section('content')
<body class="min-h-screen bg-gradient-to-br from-green-50 via-emerald-50 to-rose-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 flex flex-col">
    @include('layouts.header')

    <main class="flex-grow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
            <!-- Breadcrumb -->
            <nav class="mb-6 flex items-center gap-2 text-sm">
        <a href="{{ route('nutricionista.dashboard') }}" class="text-green-600 dark:text-green-400 hover:underline transition-colors">Inicio</a>
        <span class="material-symbols-outlined text-gray-400 text-sm">chevron_right</span>
        <span class="text-gray-700 dark:text-gray-300 font-medium">Detalle de Cita</span>
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
                            Detalle de Cita
                        </h1>
                        <p class="text-sm sm:text-base text-gray-700 dark:text-gray-300 mt-1 truncate">
                            Información completa del paciente y la cita
                        </p>
                    </div>
                </div>
                <div class="flex-shrink-0 hidden sm:flex items-center">
                    <span class="material-symbols-outlined text-3xl sm:text-4xl text-green-600 dark:text-green-400">event_note</span>
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
                                ¡Atención Registrada con Éxito!
                            </h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">
                                {{ session('success') }}
                            </p>
                            
                            <!-- Botón Ver Historial -->
                            <a href="{{ route('nutricionista.patients.show', $appointment->paciente) }}"
                               class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white text-sm font-semibold rounded-lg hover:bg-green-700 transition-all duration-200 shadow-sm hover:shadow-md">
                                <span class="material-symbols-outlined text-base">medical_information</span>
                                Ver Historial del Paciente
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

        @if(session('error'))
            <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-800 dark:text-red-200 rounded-xl p-4 mb-6">
                {{ session('error') }}
            </div>
        @endif

        <div class="grid lg:grid-cols-3 gap-6" x-data="{ showPhotoModal: false }">
            <!-- Información Principal -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Información del Paciente (Colapsable) -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700" x-data="{ expanded: false }">
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
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 max-h-0"
                         x-transition:enter-end="opacity-100 max-h-screen"
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="opacity-100 max-h-screen"
                         x-transition:leave-end="opacity-0 max-h-0"
                         class="border-t border-gray-200 dark:border-gray-700 overflow-hidden">
                        <div class="p-6">
                    
                    <!-- Foto y Nombre -->
                    <div class="flex items-center gap-4 mb-6 pb-4 border-b border-gray-200 dark:border-gray-700">
                        <div class="w-20 h-20 rounded-full overflow-hidden flex items-center justify-center flex-shrink-0 shadow-lg {{ $appointment->paciente->personalData?->profile_photo ? 'cursor-pointer hover:opacity-90 transition' : '' }}"
                             @if($appointment->paciente->personalData?->profile_photo) @click="showPhotoModal = true" @endif>
                            @if($appointment->paciente->personalData?->profile_photo)
                                <img src="{{ asset('storage/' . $appointment->paciente->personalData->profile_photo) }}" 
                                     alt="{{ $appointment->paciente->name }}" 
                                     class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-emerald-400 to-emerald-600 flex items-center justify-center text-white text-3xl font-bold">
                                    {{ strtoupper(substr($appointment->paciente->name, 0, 1)) }}
                                </div>
                            @endif
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                {{ $appointment->paciente->name }}
                            </h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 flex items-center gap-1 mt-1">
                                <span class="material-symbols-outlined text-base">email</span>
                                {{ $appointment->paciente->email }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-500 flex items-center gap-1 mt-1">
                                <span class="material-symbols-outlined text-sm">event</span>
                                Paciente desde {{ $appointment->paciente->created_at->format('d/m/Y') }}
                            </p>
                        </div>
                    </div>

                    <!-- Datos Personales en Grid -->
                    <div class="grid md:grid-cols-2 gap-4">
                        @if($appointment->paciente->personalData)
                            <!-- Cédula -->
                            @if($appointment->paciente->personalData->cedula)
                                <div class="bg-green-50 dark:bg-green-900/20 rounded-xl p-4">
                                    <div class="flex items-center gap-2 text-green-600 dark:text-green-400 mb-1">
                                        <span class="material-symbols-outlined text-sm">badge</span>
                                        <span class="text-xs font-semibold uppercase">Cédula</span>
                                    </div>
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white">
                                        {{ $appointment->paciente->personalData->cedula }}
                                    </p>
                                </div>
                            @endif

                            <!-- Teléfono -->
                            <div class="bg-green-50 dark:bg-green-900/20 rounded-xl p-4">
                                <div class="flex items-center gap-2 text-green-600 dark:text-green-400 mb-1">
                                    <span class="material-symbols-outlined text-sm">phone</span>
                                    <span class="text-xs font-semibold uppercase">Teléfono</span>
                                </div>
                                <p class="text-sm font-semibold text-gray-900 dark:text-white">
                                    {{ $appointment->paciente->personalData->phone ?? 'No disponible' }}
                                </p>
                            </div>

                            <!-- Fecha de Nacimiento y Edad -->
                            @if($appointment->paciente->personalData->birth_date)
                                <div class="bg-green-50 dark:bg-green-900/20 rounded-xl p-4">
                                    <div class="flex items-center gap-2 text-green-600 dark:text-green-400 mb-1">
                                        <span class="material-symbols-outlined text-sm">cake</span>
                                        <span class="text-xs font-semibold uppercase">Fecha de Nacimiento</span>
                                    </div>
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white">
                                        {{ \Carbon\Carbon::parse($appointment->paciente->personalData->birth_date)->format('d/m/Y') }}
                                        <span class="text-xs text-gray-500 dark:text-gray-400">({{ \Carbon\Carbon::parse($appointment->paciente->personalData->birth_date)->age }} años)</span>
                                    </p>
                                </div>
                            @endif

                            <!-- Género -->
                            @if($appointment->paciente->personalData->gender)
                                <div class="bg-green-50 dark:bg-green-900/20 rounded-xl p-4">
                                    <div class="flex items-center gap-2 text-green-600 dark:text-green-400 mb-1">
                                        <span class="material-symbols-outlined text-sm">wc</span>
                                        <span class="text-xs font-semibold uppercase">Género</span>
                                    </div>
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white">
                                        @php
                                            $genderLabels = [
                                                'male' => 'Masculino',
                                                'female' => 'Femenino',
                                                'other' => 'Otro'
                                            ];
                                        @endphp
                                        {{ $genderLabels[$appointment->paciente->personalData->gender] ?? 'No especificado' }}
                                    </p>
                                </div>
                            @endif

                            <!-- Dirección -->
                            @if($appointment->paciente->personalData->address)
                                <div class="bg-green-50 dark:bg-green-900/20 rounded-xl p-4 md:col-span-2">
                                    <div class="flex items-center gap-2 text-green-600 dark:text-green-400 mb-1">
                                        <span class="material-symbols-outlined text-sm">home</span>
                                        <span class="text-xs font-semibold uppercase">Dirección</span>
                                    </div>
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white">
                                        {{ $appointment->paciente->personalData->address }}
                                    </p>
                                </div>
                            @endif
                        @endif
                    </div>

                    <!-- Botón de Acceso al Perfil Completo -->
                    <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('nutricionista.patients.show', $appointment->paciente) }}" 
                           class="flex items-center justify-center gap-2 w-full px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white font-bold rounded-xl hover:from-green-700 hover:to-emerald-700 hover:shadow-xl hover:scale-105 transition-all shadow-lg">
                            <span class="material-symbols-outlined">person_search</span>
                            Ver Perfil Completo del Paciente
                            <span class="material-symbols-outlined">arrow_forward</span>
                        </a>
                    </div>
                        </div>
                    </div>
                </div>

                <!-- Detalles de la Cita -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 border border-gray-200 dark:border-gray-700">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined text-green-600">event</span>
                        Detalles de la Cita
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-green-50 dark:bg-green-900/20 rounded-xl p-4">
                            <div class="flex items-center gap-2 text-green-600 dark:text-green-400 mb-1">
                                <span class="material-symbols-outlined text-sm">calendar_month</span>
                                <span class="text-xs font-semibold uppercase">Fecha</span>
                            </div>
                            <p class="text-gray-900 dark:text-white font-semibold">
                                {{ $appointment->start_time->format('d/m/Y') }}
                            </p>
                        </div>

                        <div class="bg-emerald-50 dark:bg-emerald-900/20 rounded-xl p-4">
                            <div class="flex items-center gap-2 text-emerald-600 dark:text-emerald-400 mb-1">
                                <span class="material-symbols-outlined text-sm">schedule</span>
                                <span class="text-xs font-semibold uppercase">Hora</span>
                            </div>
                            <p class="text-gray-900 dark:text-white font-semibold">
                                {{ $appointment->start_time->format('h:i A') }} -
                                {{ $appointment->end_time->format('h:i A') }}
                            </p>
                        </div>

                        <div class="bg-green-50 dark:bg-green-900/20 rounded-xl p-4">
                            <div class="flex items-center gap-2 text-green-600 dark:text-green-400 mb-1">
                                <span class="material-symbols-outlined text-sm">category</span>
                                <span class="text-xs font-semibold uppercase">Tipo</span>
                            </div>
                            <p class="text-gray-900 dark:text-white font-semibold">
                                {{ $appointment->appointment_type === 'primera_vez' ? 'Primera vez' : 'Seguimiento' }}
                            </p>
                        </div>

                        <div class="bg-emerald-50 dark:bg-emerald-900/20 rounded-xl p-4">
                            <div class="flex items-center gap-2 text-emerald-600 dark:text-emerald-400 mb-1">
                                <span class="material-symbols-outlined text-sm">timer</span>
                                <span class="text-xs font-semibold uppercase">Duración</span>
                            </div>
                            <p class="text-gray-900 dark:text-white font-semibold">
                                {{ $appointment->start_time->diffInMinutes($appointment->end_time) }} minutos
                            </p>
                        </div>

                        <div class="bg-green-50 dark:bg-green-900/20 rounded-xl p-4">
                            <div class="flex items-center gap-2 text-green-600 dark:text-green-400 mb-1">
                                <span class="material-symbols-outlined text-sm">payments</span>
                                <span class="text-xs font-semibold uppercase">Precio</span>
                            </div>
                            <p class="text-gray-900 dark:text-white font-semibold">
                                ${{ number_format($appointment->price, 2) }}
                            </p>
                        </div>

                        <div class="bg-emerald-50 dark:bg-emerald-900/20 rounded-xl p-4">
                            <div class="flex items-center gap-2 text-emerald-600 dark:text-emerald-400 mb-1">
                                <span class="material-symbols-outlined text-sm">info</span>
                                <span class="text-xs font-semibold uppercase">Estado</span>
                            </div>
                            @php
                                $statusClasses = [
                                    'pendiente' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400',
                                    'completada' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
                                    'cancelada' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400',
                                    'vencida' => 'bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-400',
                                ];
                            @endphp
                            <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold {{ $statusClasses[$appointment->appointmentState->name] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($appointment->appointmentState->name) }}
                            </span>
                        </div>
                    </div>

                    @if($appointment->reason)
                        <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-2 flex items-center gap-1">
                                <span class="material-symbols-outlined text-sm">assignment</span>
                                Motivo de la Consulta
                            </p>
                            <div class="bg-amber-50 dark:bg-amber-900/20 rounded-lg p-3 border border-amber-200 dark:border-amber-800">
                                <p class="text-sm text-gray-900 dark:text-white">
                                    {{ $appointment->reason }}
                                </p>
                            </div>
                        </div>
                    @endif

                    @if($appointment->notes)
                        <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-2 flex items-center gap-1">
                                <span class="material-symbols-outlined text-sm">description</span>
                                Notas de la Consulta
                            </p>
                            <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-3 border border-blue-200 dark:border-blue-800">
                                <p class="text-sm text-gray-900 dark:text-white">
                                    {{ $appointment->notes }}
                                </p>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Datos de Atención (solo si existe) -->
                @if($appointment->attention && $appointment->attention->attentionData)
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 border border-gray-200 dark:border-gray-700">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                            <span class="material-symbols-outlined text-emerald-600">clinical_notes</span>
                            Datos de la Atención
                        </h2>

                        <!-- Medidas Básicas -->
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3 flex items-center gap-2">
                                <span class="material-symbols-outlined text-blue-600">straighten</span>
                                Medidas Básicas
                            </h3>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                <div class="bg-emerald-50 dark:bg-emerald-900/20 rounded-xl p-4 border border-emerald-200 dark:border-emerald-800">
                                    <p class="text-xs text-emerald-600 dark:text-emerald-400 font-semibold mb-1">Peso</p>
                                    <p class="text-lg font-bold text-gray-900 dark:text-white">
                                        {{ $appointment->attention->attentionData->weight }} kg
                                    </p>
                                </div>
                                <div class="bg-blue-50 dark:bg-blue-900/20 rounded-xl p-4 border border-blue-200 dark:border-blue-800">
                                    <p class="text-xs text-blue-600 dark:text-blue-400 font-semibold mb-1">Altura</p>
                                    <p class="text-lg font-bold text-gray-900 dark:text-white">
                                        {{ $appointment->attention->attentionData->height }} cm
                                    </p>
                                </div>
                                <div class="bg-purple-50 dark:bg-purple-900/20 rounded-xl p-4 border border-purple-200 dark:border-purple-800">
                                    <p class="text-xs text-purple-600 dark:text-purple-400 font-semibold mb-1">Nivel de Actividad</p>
                                    <p class="text-sm font-bold text-gray-900 dark:text-white">
                                        @php
                                            $activityLabels = [
                                                'sedentary' => 'Sedentario',
                                                'light' => 'Ligero',
                                                'moderate' => 'Moderado',
                                                'active' => 'Activo',
                                                'very_active' => 'Muy activo'
                                            ];
                                        @endphp
                                        {{ $activityLabels[$appointment->attention->attentionData->activity_level] ?? 'N/A' }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Medidas Corporales -->
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3 flex items-center gap-2">
                                <span class="material-symbols-outlined text-teal-600">accessibility</span>
                                Medidas Corporales
                            </h3>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                @if($appointment->attention->attentionData->waist)
                                    <div class="bg-pink-50 dark:bg-pink-900/20 rounded-lg p-3 border border-pink-200 dark:border-pink-800">
                                        <p class="text-xs text-pink-600 dark:text-pink-400 font-semibold mb-1">Cintura</p>
                                        <p class="text-lg font-bold text-gray-900 dark:text-white">
                                            {{ $appointment->attention->attentionData->waist }} cm
                                        </p>
                                    </div>
                                @endif
                                @if($appointment->attention->attentionData->hip)
                                    <div class="bg-indigo-50 dark:bg-indigo-900/20 rounded-lg p-3 border border-indigo-200 dark:border-indigo-800">
                                        <p class="text-xs text-indigo-600 dark:text-indigo-400 font-semibold mb-1">Cadera</p>
                                        <p class="text-lg font-bold text-gray-900 dark:text-white">
                                            {{ $appointment->attention->attentionData->hip }} cm
                                        </p>
                                    </div>
                                @endif
                                @if($appointment->attention->attentionData->neck)
                                    <div class="bg-cyan-50 dark:bg-cyan-900/20 rounded-lg p-3 border border-cyan-200 dark:border-cyan-800">
                                        <p class="text-xs text-cyan-600 dark:text-cyan-400 font-semibold mb-1">Cuello</p>
                                        <p class="text-lg font-bold text-gray-900 dark:text-white">
                                            {{ $appointment->attention->attentionData->neck }} cm
                                        </p>
                                    </div>
                                @endif
                                @if($appointment->attention->attentionData->wrist)
                                    <div class="bg-teal-50 dark:bg-teal-900/20 rounded-lg p-3 border border-teal-200 dark:border-teal-800">
                                        <p class="text-xs text-teal-600 dark:text-teal-400 font-semibold mb-1">Muñeca</p>
                                        <p class="text-lg font-bold text-gray-900 dark:text-white">
                                            {{ $appointment->attention->attentionData->wrist }} cm
                                        </p>
                                    </div>
                                @endif
                                @if($appointment->attention->attentionData->arm_contracted)
                                    <div class="bg-amber-50 dark:bg-amber-900/20 rounded-lg p-3 border border-amber-200 dark:border-amber-800">
                                        <p class="text-xs text-amber-600 dark:text-amber-400 font-semibold mb-1">Brazo Contraído</p>
                                        <p class="text-lg font-bold text-gray-900 dark:text-white">
                                            {{ $appointment->attention->attentionData->arm_contracted }} cm
                                        </p>
                                    </div>
                                @endif
                                @if($appointment->attention->attentionData->arm_relaxed)
                                    <div class="bg-lime-50 dark:bg-lime-900/20 rounded-lg p-3 border border-lime-200 dark:border-lime-800">
                                        <p class="text-xs text-lime-600 dark:text-lime-400 font-semibold mb-1">Brazo Relajado</p>
                                        <p class="text-lg font-bold text-gray-900 dark:text-white">
                                            {{ $appointment->attention->attentionData->arm_relaxed }} cm
                                        </p>
                                    </div>
                                @endif
                                @if($appointment->attention->attentionData->thigh)
                                    <div class="bg-sky-50 dark:bg-sky-900/20 rounded-lg p-3 border border-sky-200 dark:border-sky-800">
                                        <p class="text-xs text-sky-600 dark:text-sky-400 font-semibold mb-1">Pierna</p>
                                        <p class="text-lg font-bold text-gray-900 dark:text-white">
                                            {{ $appointment->attention->attentionData->thigh }} cm
                                        </p>
                                    </div>
                                @endif
                                @if($appointment->attention->attentionData->calf)
                                    <div class="bg-violet-50 dark:bg-violet-900/20 rounded-lg p-3 border border-violet-200 dark:border-violet-800">
                                        <p class="text-xs text-violet-600 dark:text-violet-400 font-semibold mb-1">Pantorrilla</p>
                                        <p class="text-lg font-bold text-gray-900 dark:text-white">
                                            {{ $appointment->attention->attentionData->calf }} cm
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Análisis Antropométrico -->
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3 flex items-center gap-2">
                                <span class="material-symbols-outlined text-orange-600">analytics</span>
                                Análisis Antropométrico
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- IMC con interpretación -->
                                <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4 border border-blue-200 dark:border-blue-800">
                                    <div class="flex items-center justify-between mb-2">
                                        <p class="text-xs text-blue-600 dark:text-blue-400 font-semibold">Índice de Masa Corporal (IMC)</p>
                                        <span class="material-symbols-outlined text-blue-600 dark:text-blue-400 text-xl">monitoring</span>
                                    </div>
                                    <p class="text-2xl font-bold text-gray-900 dark:text-white mb-2">{{ number_format($appointment->attention->attentionData->bmi, 1) }}</p>
                                    @php
                                        $bmi = $appointment->attention->attentionData->bmi;
                                        if ($bmi < 18.5) {
                                            $bmiCategory = 'Bajo peso';
                                            $bmiColor = 'text-yellow-600 dark:text-yellow-400';
                                            $bmiBg = 'bg-yellow-100 dark:bg-yellow-900/30';
                                        } elseif ($bmi < 25) {
                                            $bmiCategory = 'Peso normal';
                                            $bmiColor = 'text-green-600 dark:text-green-400';
                                            $bmiBg = 'bg-green-100 dark:bg-green-900/30';
                                        } elseif ($bmi < 30) {
                                            $bmiCategory = 'Sobrepeso';
                                            $bmiColor = 'text-orange-600 dark:text-orange-400';
                                            $bmiBg = 'bg-orange-100 dark:bg-orange-900/30';
                                        } else {
                                            $bmiCategory = 'Obesidad';
                                            $bmiColor = 'text-red-600 dark:text-red-400';
                                            $bmiBg = 'bg-red-100 dark:bg-red-900/30';
                                        }
                                    @endphp
                                    <span class="inline-block px-2 py-1 rounded-lg text-xs font-semibold {{ $bmiColor }} {{ $bmiBg }}">{{ $bmiCategory }}</span>
                                </div>

                                @if($appointment->attention->attentionData->body_fat)
                                    <!-- Porcentaje de Grasa con interpretación -->
                                    <div class="bg-rose-50 dark:bg-rose-900/20 rounded-lg p-4 border border-rose-200 dark:border-rose-800">
                                        <div class="flex items-center justify-between mb-2">
                                            <p class="text-xs text-rose-600 dark:text-rose-400 font-semibold">% Grasa Corporal</p>
                                            <span class="material-symbols-outlined text-rose-600 dark:text-rose-400 text-xl">water_drop</span>
                                        </div>
                                        <p class="text-2xl font-bold text-gray-900 dark:text-white mb-2">{{ number_format($appointment->attention->attentionData->body_fat, 1) }}%</p>
                                        @php
                                            $bodyFat = $appointment->attention->attentionData->body_fat;
                                            $gender = $appointment->paciente->personalData->gender ?? 'male';
                                            
                                            if ($gender === 'male') {
                                                if ($bodyFat < 6) {
                                                    $fatCategory = 'Esencial';
                                                    $fatColor = 'text-blue-600 dark:text-blue-400';
                                                } elseif ($bodyFat < 14) {
                                                    $fatCategory = 'Atlético';
                                                    $fatColor = 'text-green-600 dark:text-green-400';
                                                } elseif ($bodyFat < 18) {
                                                    $fatCategory = 'Fitness';
                                                    $fatColor = 'text-emerald-600 dark:text-emerald-400';
                                                } elseif ($bodyFat < 25) {
                                                    $fatCategory = 'Aceptable';
                                                    $fatColor = 'text-yellow-600 dark:text-yellow-400';
                                                } else {
                                                    $fatCategory = 'Alto';
                                                    $fatColor = 'text-red-600 dark:text-red-400';
                                                }
                                            } else {
                                                if ($bodyFat < 14) {
                                                    $fatCategory = 'Esencial';
                                                    $fatColor = 'text-blue-600 dark:text-blue-400';
                                                } elseif ($bodyFat < 21) {
                                                    $fatCategory = 'Atlético';
                                                    $fatColor = 'text-green-600 dark:text-green-400';
                                                } elseif ($bodyFat < 25) {
                                                    $fatCategory = 'Fitness';
                                                    $fatColor = 'text-emerald-600 dark:text-emerald-400';
                                                } elseif ($bodyFat < 32) {
                                                    $fatCategory = 'Aceptable';
                                                    $fatColor = 'text-yellow-600 dark:text-yellow-400';
                                                } else {
                                                    $fatCategory = 'Alto';
                                                    $fatColor = 'text-red-600 dark:text-red-400';
                                                }
                                            }
                                        @endphp
                                        <p class="text-xs {{ $fatColor }} font-semibold">{{ $fatCategory }}</p>
                                    </div>
                                @endif

                                @if($appointment->attention->attentionData->tmb)
                                    <!-- TMB con descripción -->
                                    <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-4 border border-green-200 dark:border-green-800">
                                        <div class="flex items-center justify-between mb-2">
                                            <p class="text-xs text-green-600 dark:text-green-400 font-semibold">Tasa Metabólica Basal (TMB)</p>
                                            <span class="material-symbols-outlined text-green-600 dark:text-green-400 text-xl">local_fire_department</span>
                                        </div>
                                        <p class="text-2xl font-bold text-gray-900 dark:text-white mb-1">{{ number_format($appointment->attention->attentionData->tmb, 0) }} <span class="text-sm font-normal">kcal/día</span></p>
                                        <p class="text-xs text-gray-600 dark:text-gray-400">Calorías en reposo absoluto</p>
                                    </div>
                                @endif

                                @if($appointment->attention->attentionData->tdee)
                                    <!-- TDEE con descripción -->
                                    <div class="bg-purple-50 dark:bg-purple-900/20 rounded-lg p-4 border border-purple-200 dark:border-purple-800">
                                        <div class="flex items-center justify-between mb-2">
                                            <p class="text-xs text-purple-600 dark:text-purple-400 font-semibold">Gasto Energético Total (TDEE)</p>
                                            <span class="material-symbols-outlined text-purple-600 dark:text-purple-400 text-xl">bolt</span>
                                        </div>
                                        <p class="text-2xl font-bold text-gray-900 dark:text-white mb-1">{{ number_format($appointment->attention->attentionData->tdee, 0) }} <span class="text-sm font-normal">kcal/día</span></p>
                                        <p class="text-xs text-gray-600 dark:text-gray-400">Calorías con actividad incluida</p>
                                    </div>
                                @endif

                                @if($appointment->attention->attentionData->whr)
                                    <!-- Índice Cintura-Cadera con interpretación -->
                                    <div class="bg-orange-50 dark:bg-orange-900/20 rounded-lg p-4 border border-orange-200 dark:border-orange-800">
                                        <div class="flex items-center justify-between mb-2">
                                            <p class="text-xs text-orange-600 dark:text-orange-400 font-semibold">Índice Cintura-Cadera (WHR)</p>
                                            <span class="material-symbols-outlined text-orange-600 dark:text-orange-400 text-xl">straighten</span>
                                        </div>
                                        <p class="text-2xl font-bold text-gray-900 dark:text-white mb-2">{{ number_format($appointment->attention->attentionData->whr, 3) }}</p>
                                        @php
                                            $whr = $appointment->attention->attentionData->whr;
                                            $gender = $appointment->paciente->personalData->gender ?? 'male';
                                            
                                            if ($gender === 'male') {
                                                if ($whr < 0.90) {
                                                    $whrCategory = 'Bajo riesgo';
                                                    $whrColor = 'text-green-600 dark:text-green-400';
                                                } elseif ($whr < 1.0) {
                                                    $whrCategory = 'Riesgo moderado';
                                                    $whrColor = 'text-yellow-600 dark:text-yellow-400';
                                                } else {
                                                    $whrCategory = 'Riesgo alto';
                                                    $whrColor = 'text-red-600 dark:text-red-400';
                                                }
                                            } else {
                                                if ($whr < 0.80) {
                                                    $whrCategory = 'Bajo riesgo';
                                                    $whrColor = 'text-green-600 dark:text-green-400';
                                                } elseif ($whr < 0.85) {
                                                    $whrCategory = 'Riesgo moderado';
                                                    $whrColor = 'text-yellow-600 dark:text-yellow-400';
                                                } else {
                                                    $whrCategory = 'Riesgo alto';
                                                    $whrColor = 'text-red-600 dark:text-red-400';
                                                }
                                            }
                                        @endphp
                                        <p class="text-xs {{ $whrColor }} font-semibold">{{ $whrCategory }} cardiovascular</p>
                                    </div>
                                @endif

                                @if($appointment->attention->attentionData->wht)
                                    <!-- Índice Cintura-Altura con interpretación -->
                                    <div class="bg-yellow-50 dark:bg-yellow-900/20 rounded-lg p-4 border border-yellow-200 dark:border-yellow-800">
                                        <div class="flex items-center justify-between mb-2">
                                            <p class="text-xs text-yellow-600 dark:text-yellow-400 font-semibold">Índice Cintura-Altura (WHtR)</p>
                                            <span class="material-symbols-outlined text-yellow-600 dark:text-yellow-400 text-xl">height</span>
                                        </div>
                                        <p class="text-2xl font-bold text-gray-900 dark:text-white mb-2">{{ number_format($appointment->attention->attentionData->wht, 3) }}</p>
                                        @php
                                            $wht = $appointment->attention->attentionData->wht;
                                            
                                            if ($wht < 0.40) {
                                                $whtCategory = 'Extremadamente delgado';
                                                $whtColor = 'text-blue-600 dark:text-blue-400';
                                            } elseif ($wht < 0.50) {
                                                $whtCategory = 'Saludable';
                                                $whtColor = 'text-green-600 dark:text-green-400';
                                            } elseif ($wht < 0.60) {
                                                $whtCategory = 'Sobrepeso';
                                                $whtColor = 'text-yellow-600 dark:text-yellow-400';
                                            } else {
                                                $whtCategory = 'Alto riesgo';
                                                $whtColor = 'text-red-600 dark:text-red-400';
                                            }
                                        @endphp
                                        <p class="text-xs {{ $whtColor }} font-semibold">{{ $whtCategory }}</p>
                                    </div>
                                @endif

                                @if($appointment->attention->attentionData->frame_index)
                                    <!-- Complexión Ósea con interpretación -->
                                    <div class="bg-indigo-50 dark:bg-indigo-900/20 rounded-lg p-4 border border-indigo-200 dark:border-indigo-800">
                                        <div class="flex items-center justify-between mb-2">
                                            <p class="text-xs text-indigo-600 dark:text-indigo-400 font-semibold">Complexión Ósea</p>
                                            <span class="material-symbols-outlined text-indigo-600 dark:text-indigo-400 text-xl">skeleton</span>
                                        </div>
                                        <p class="text-2xl font-bold text-gray-900 dark:text-white mb-2">{{ number_format($appointment->attention->attentionData->frame_index, 2) }}</p>
                                        @php
                                            $frameIndex = $appointment->attention->attentionData->frame_index;
                                            $gender = $appointment->paciente->personalData->gender ?? 'male';
                                            
                                            if ($gender === 'male') {
                                                if ($frameIndex < 10.4) {
                                                    $frameCategory = 'Pequeña';
                                                    $frameColor = 'text-blue-600 dark:text-blue-400';
                                                } elseif ($frameIndex < 11) {
                                                    $frameCategory = 'Mediana';
                                                    $frameColor = 'text-green-600 dark:text-green-400';
                                                } else {
                                                    $frameCategory = 'Grande';
                                                    $frameColor = 'text-purple-600 dark:text-purple-400';
                                                }
                                            } else {
                                                if ($frameIndex < 10.1) {
                                                    $frameCategory = 'Pequeña';
                                                    $frameColor = 'text-blue-600 dark:text-blue-400';
                                                } elseif ($frameIndex < 11) {
                                                    $frameCategory = 'Mediana';
                                                    $frameColor = 'text-green-600 dark:text-green-400';
                                                } else {
                                                    $frameCategory = 'Grande';
                                                    $frameColor = 'text-purple-600 dark:text-purple-400';
                                                }
                                            }
                                        @endphp
                                        <p class="text-xs {{ $frameColor }} font-semibold">Estructura {{ strtolower($frameCategory) }}</p>
                                    </div>
                                @endif
                                <!-- Calorías Objetivo -->
                                @if($appointment->attention->attentionData->target_calories)
                                    <div class="bg-emerald-50 dark:bg-emerald-900/20 rounded-lg p-4 border border-emerald-200 dark:border-emerald-800">
                                        <div class="flex items-center justify-between mb-2">
                                            <p class="text-xs text-emerald-600 dark:text-emerald-400 font-semibold">Calorías Objetivo</p>
                                            <span class="material-symbols-outlined text-emerald-600 dark:text-emerald-400 text-xl">restaurant</span>
                                        </div>
                                        <p class="text-2xl font-bold text-gray-900 dark:text-white mb-1">{{ number_format($appointment->attention->attentionData->target_calories, 0) }} <span class="text-sm font-normal">kcal/día</span></p>
                                        @if($appointment->attention->attentionData->nutrition_goal)
                                            @php
                                                $goalLabels = [
                                                    'deficit' => ['label' => 'Déficit calórico', 'color' => 'text-red-600 dark:text-red-400'],
                                                    'maintenance' => ['label' => 'Mantenimiento', 'color' => 'text-blue-600 dark:text-blue-400'],
                                                    'surplus' => ['label' => 'Superávit calórico', 'color' => 'text-green-600 dark:text-green-400'],
                                                ];
                                                $goal = $goalLabels[$appointment->attention->attentionData->nutrition_goal] ?? ['label' => 'N/A', 'color' => 'text-gray-600'];
                                            @endphp
                                            <p class="text-xs {{ $goal['color'] }} font-semibold">{{ $goal['label'] }}</p>
                                        @endif
                                    </div>
                                @endif                            </div>
                        </div>

                        <!-- Macronutrientes Objetivo (si existen) -->
                        @if($appointment->attention->attentionData->protein_grams || $appointment->attention->attentionData->carbs_grams || $appointment->attention->attentionData->fat_grams)
                            <div class="mb-6">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3 flex items-center gap-2">
                                    <span class="material-symbols-outlined text-amber-600">nutrition</span>
                                    Macronutrientes Objetivo
                                </h3>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    @if($appointment->attention->attentionData->protein_grams)
                                        <div class="bg-red-50 dark:bg-red-900/20 rounded-lg p-4 border border-red-200 dark:border-red-800">
                                            <div class="flex items-center justify-between mb-2">
                                                <p class="text-xs text-red-600 dark:text-red-400 font-semibold">Proteínas</p>
                                                <span class="material-symbols-outlined text-red-600 dark:text-red-400 text-lg">egg</span>
                                            </div>
                                            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($appointment->attention->attentionData->protein_grams, 0) }}g</p>
                                            @if($appointment->attention->attentionData->protein_percentage)
                                                <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">{{ number_format($appointment->attention->attentionData->protein_percentage, 0) }}% del total</p>
                                            @endif
                                        </div>
                                    @endif

                                    @if($appointment->attention->attentionData->carbs_grams)
                                        <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4 border border-blue-200 dark:border-blue-800">
                                            <div class="flex items-center justify-between mb-2">
                                                <p class="text-xs text-blue-600 dark:text-blue-400 font-semibold">Carbohidratos</p>
                                                <span class="material-symbols-outlined text-blue-600 dark:text-blue-400 text-lg">grain</span>
                                            </div>
                                            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($appointment->attention->attentionData->carbs_grams, 0) }}g</p>
                                            @if($appointment->attention->attentionData->carbs_percentage)
                                                <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">{{ number_format($appointment->attention->attentionData->carbs_percentage, 0) }}% del total</p>
                                            @endif
                                        </div>
                                    @endif

                                    @if($appointment->attention->attentionData->fat_grams)
                                        <div class="bg-yellow-50 dark:bg-yellow-900/20 rounded-lg p-4 border border-yellow-200 dark:border-yellow-800">
                                            <div class="flex items-center justify-between mb-2">
                                                <p class="text-xs text-yellow-600 dark:text-yellow-400 font-semibold">Grasas</p>
                                                <span class="material-symbols-outlined text-yellow-600 dark:text-yellow-400 text-lg">water_drop</span>
                                            </div>
                                            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($appointment->attention->attentionData->fat_grams, 0) }}g</p>
                                            @if($appointment->attention->attentionData->fat_percentage)
                                                <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">{{ number_format($appointment->attention->attentionData->fat_percentage, 0) }}% del total</p>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <!-- Distribución de Equivalentes -->
                        @if($appointment->attention->attentionData->eq_cereales !== null || 
                            $appointment->attention->attentionData->eq_verduras !== null || 
                            $appointment->attention->attentionData->eq_frutas !== null ||
                            $appointment->attention->attentionData->eq_lacteo !== null ||
                            $appointment->attention->attentionData->eq_animal !== null ||
                            $appointment->attention->attentionData->eq_aceites !== null ||
                            $appointment->attention->attentionData->eq_grasas_prot !== null ||
                            $appointment->attention->attentionData->eq_leguminosas !== null)
                            <div class="mb-6">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3 flex items-center gap-2">
                                    <span class="material-symbols-outlined text-purple-600">restaurant_menu</span>
                                    Distribución de Equivalentes por Grupo
                                </h3>
                                
                                <div class="bg-purple-50 dark:bg-purple-900/20 rounded-xl p-4 border border-purple-200 dark:border-purple-800 mb-4">
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                        @if($appointment->attention->attentionData->eq_cereales !== null)
                                            <div class="text-center">
                                                <p class="text-xs text-gray-600 dark:text-gray-400 mb-1">🌾 Cereales</p>
                                                <p class="text-2xl font-bold text-purple-600 dark:text-purple-400">{{ number_format($appointment->attention->attentionData->eq_cereales, 1) }}</p>
                                                <p class="text-xs text-gray-500 dark:text-gray-500">equivalentes</p>
                                            </div>
                                        @endif

                                        @if($appointment->attention->attentionData->eq_verduras !== null)
                                            <div class="text-center">
                                                <p class="text-xs text-gray-600 dark:text-gray-400 mb-1">🥬 Verduras</p>
                                                <p class="text-2xl font-bold text-green-600 dark:text-green-400">{{ number_format($appointment->attention->attentionData->eq_verduras, 1) }}</p>
                                                <p class="text-xs text-gray-500 dark:text-gray-500">equivalentes</p>
                                            </div>
                                        @endif

                                        @if($appointment->attention->attentionData->eq_frutas !== null)
                                            <div class="text-center">
                                                <p class="text-xs text-gray-600 dark:text-gray-400 mb-1">🍎 Frutas</p>
                                                <p class="text-2xl font-bold text-red-600 dark:text-red-400">{{ number_format($appointment->attention->attentionData->eq_frutas, 1) }}</p>
                                                <p class="text-xs text-gray-500 dark:text-gray-500">equivalentes</p>
                                            </div>
                                        @endif

                                        @if($appointment->attention->attentionData->eq_lacteo !== null)
                                            <div class="text-center">
                                                <p class="text-xs text-gray-600 dark:text-gray-400 mb-1">🥛 Lácteos</p>
                                                <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ number_format($appointment->attention->attentionData->eq_lacteo, 1) }}</p>
                                                <p class="text-xs text-gray-500 dark:text-gray-500">equivalentes</p>
                                            </div>
                                        @endif

                                        @if($appointment->attention->attentionData->eq_animal !== null)
                                            <div class="text-center">
                                                <p class="text-xs text-gray-600 dark:text-gray-400 mb-1">🍗 Origen Animal</p>
                                                <p class="text-2xl font-bold text-orange-600 dark:text-orange-400">{{ number_format($appointment->attention->attentionData->eq_animal, 1) }}</p>
                                                <p class="text-xs text-gray-500 dark:text-gray-500">equivalentes</p>
                                            </div>
                                        @endif

                                        @if($appointment->attention->attentionData->eq_aceites !== null)
                                            <div class="text-center">
                                                <p class="text-xs text-gray-600 dark:text-gray-400 mb-1">🫒 Aceites</p>
                                                <p class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">{{ number_format($appointment->attention->attentionData->eq_aceites, 1) }}</p>
                                                <p class="text-xs text-gray-500 dark:text-gray-500">equivalentes</p>
                                            </div>
                                        @endif

                                        @if($appointment->attention->attentionData->eq_grasas_prot !== null)
                                            <div class="text-center">
                                                <p class="text-xs text-gray-600 dark:text-gray-400 mb-1">🥜 Grasas c/Proteína</p>
                                                <p class="text-2xl font-bold text-amber-600 dark:text-amber-400">{{ number_format($appointment->attention->attentionData->eq_grasas_prot, 1) }}</p>
                                                <p class="text-xs text-gray-500 dark:text-gray-500">equivalentes</p>
                                            </div>
                                        @endif

                                        @if($appointment->attention->attentionData->eq_leguminosas !== null)
                                            <div class="text-center">
                                                <p class="text-xs text-gray-600 dark:text-gray-400 mb-1">🫘 Leguminosas</p>
                                                <p class="text-2xl font-bold text-teal-600 dark:text-teal-400">{{ number_format($appointment->attention->attentionData->eq_leguminosas, 1) }}</p>
                                                <p class="text-xs text-gray-500 dark:text-gray-500">equivalentes</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Total de Calorías de Equivalentes -->
                                @if($appointment->attention->attentionData->total_calories_equivalents)
                                    <div class="bg-gradient-to-r from-emerald-50 to-green-50 dark:from-emerald-900/20 dark:to-green-900/20 rounded-xl p-5 border-2 border-emerald-300 dark:border-emerald-700">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <p class="text-sm text-emerald-700 dark:text-emerald-300 font-semibold mb-1">Total de Calorías de Equivalentes</p>
                                                <p class="text-3xl font-bold text-emerald-900 dark:text-emerald-100">
                                                    {{ number_format($appointment->attention->attentionData->total_calories_equivalents, 0) }} <span class="text-lg">kcal</span>
                                                </p>
                                            </div>
                                            
                                            @if($appointment->attention->attentionData->target_calories)
                                                @php
                                                    $percentage = ($appointment->attention->attentionData->total_calories_equivalents / $appointment->attention->attentionData->target_calories) * 100;
                                                    
                                                    if ($percentage >= 90 && $percentage <= 110) {
                                                        $statusColor = 'text-green-600 dark:text-green-400';
                                                        $statusBg = 'bg-green-100 dark:bg-green-900/30';
                                                        $statusIcon = 'check_circle';
                                                    } elseif ($percentage >= 85 && $percentage <= 115) {
                                                        $statusColor = 'text-yellow-600 dark:text-yellow-400';
                                                        $statusBg = 'bg-yellow-100 dark:bg-yellow-900/30';
                                                        $statusIcon = 'warning';
                                                    } else {
                                                        $statusColor = 'text-red-600 dark:text-red-400';
                                                        $statusBg = 'bg-red-100 dark:bg-red-900/30';
                                                        $statusIcon = 'error';
                                                    }
                                                @endphp
                                                <div class="text-right">
                                                    <div class="flex items-center gap-2 justify-end mb-1">
                                                        <span class="material-symbols-outlined {{ $statusColor }} text-2xl">{{ $statusIcon }}</span>
                                                        <span class="text-2xl font-bold {{ $statusColor }}">{{ number_format($percentage, 2) }}%</span>
                                                    </div>
                                                    <p class="text-xs text-gray-600 dark:text-gray-400">
                                                        del objetivo ({{ number_format($appointment->attention->attentionData->target_calories, 0) }} kcal)
                                                    </p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endif

                        <!-- Diagnóstico -->
                        <div class="mb-6">
                            <h3 
                                class="text-lg font-semibold text-gray-900 dark:text-white mb-3 flex items-center gap-2">
                                <span class="material-symbols-outlined text-blue-600">description</span>
                                Diagnóstico
                            </h3>
                            <div class="bg-blue-50 dark:bg-blue-900/20 rounded-xl p-4 border-l-4 border-blue-600">
                                <p class="text-gray-700 dark:text-gray-300">
                                    {{ $appointment->attention->diagnosis }}
                                </p>
                            </div>
                        </div>

                        <!-- Recomendaciones -->
                        <div>
                            <h3
                                class="text-lg font-semibold text-gray-900 dark:text-white mb-3 flex items-center gap-2">
                                <span class="material-symbols-outlined text-green-600">task_alt</span>
                                Recomendaciones
                            </h3>
                            <div class="bg-green-50 dark:bg-green-900/20 rounded-xl p-4 border-l-4 border-green-600">
                                <p class="text-gray-700 dark:text-gray-300">
                                    {{ $appointment->attention->recommendations }}</p>
                            </div>
                        </div>

                        <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                Atención registrada el {{ ucfirst($appointment->attention->created_at->isoFormat('dddd, D/M/Y')) }}
                                        a las {{ $appointment->attention->created_at->format('h:i A') }}
                            </p>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar de Acciones -->
            <div class="lg:col-span-1">
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 border border-gray-200 dark:border-gray-700 sticky top-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Acciones</h3>

                    @if($appointment->appointmentState->name === 'pendiente')
                        <!-- Botón de Iniciar Atención -->
                        <a href="{{ route('nutricionista.attentions.create', $appointment) }}" 
                           class="w-full mb-3 flex items-center justify-center gap-2 bg-gradient-to-r from-blue-600 to-emerald-600 text-white font-semibold py-3 px-4 rounded-xl hover:from-blue-700 hover:to-emerald-700 transition shadow-lg">
                            <span class="material-symbols-outlined">medical_services</span>
                            Iniciar Atención
                        </a>

                        <!-- Botón de Reagendar -->
                        <a href="{{ route('nutricionista.appointments.reschedule', $appointment) }}" 
                           class="w-full mb-3 flex items-center justify-center gap-2 bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400 font-semibold py-3 px-4 rounded-xl hover:bg-amber-200 dark:hover:bg-amber-900/50 transition border-2 border-amber-300 dark:border-amber-700">
                            <span class="material-symbols-outlined">event_repeat</span>
                            Reagendar Cita
                        </a>

                        <!-- Botón de Cancelar con Modal -->
                        <div x-data="{ showModal: false, cancelling: false }">
                            <button type="button" @click="showModal = true"
                                class="w-full flex items-center justify-center gap-2 bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 font-semibold py-3 px-4 rounded-xl hover:bg-red-200 dark:hover:bg-red-900/50 transition">
                                <span class="material-symbols-outlined">cancel</span>
                                Cancelar Cita
                            </button>

                            <!-- Modal de Confirmación -->
                            <div x-show="showModal" x-cloak
                                class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/30 backdrop-blur-sm"
                                @click.self="showModal = false">
                                <div @click.away="showModal = false"
                                    class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full p-6 transform transition-all"
                                    x-transition:enter="transition ease-out duration-300"
                                    x-transition:enter-start="opacity-0 scale-90"
                                    x-transition:enter-end="opacity-100 scale-100"
                                    x-transition:leave="transition ease-in duration-200"
                                    x-transition:leave-start="opacity-100 scale-100"
                                    x-transition:leave-end="opacity-0 scale-90">
                                    
                                    <div class="text-center mb-6">
                                        <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 dark:bg-red-900/30 mb-4">
                                            <span class="material-symbols-outlined text-4xl text-red-600 dark:text-red-400">warning</span>
                                        </div>
                                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">
                                            ¿Cancelar esta cita?
                                        </h3>
                                        <p class="text-gray-600 dark:text-gray-400">
                                            Esta acción no se puede deshacer. El paciente será notificado de la cancelación.
                                        </p>
                                    </div>

                                    <div class="flex gap-3">
                                        <button type="button" 
                                            @click="showModal = false"
                                            :disabled="cancelling"
                                            class="flex-1 px-4 py-3 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 font-semibold rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition disabled:opacity-50 disabled:cursor-not-allowed">
                                            No, mantener
                                        </button>
                                        <form method="POST" action="{{ route('nutricionista.appointments.cancel', $appointment) }}" class="flex-1" @submit="cancelling = true">
                                            @csrf
                                            <button type="submit"
                                                :disabled="cancelling"
                                                class="w-full px-4 py-3 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2">
                                                <svg x-show="cancelling" class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                </svg>
                                                <span x-text="cancelling ? 'Cancelando...' : 'Sí, cancelar'"></span>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @elseif($appointment->appointmentState->name === 'completada')
                        <div class="text-center py-4 mb-4">
                            <span class="material-symbols-outlined text-6xl text-green-600 dark:text-green-400 mb-2">check_circle</span>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Esta cita ya fue completada</p>
                        </div>

                        @if($appointment->attention && $appointment->attention->attentionData)
                            <!-- Botón de Editar Atención -->
                            <div class="mb-3">
                                <a href="{{ route('nutricionista.attentions.edit', $appointment) }}" 
                                   class="w-full flex items-center justify-center gap-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold py-3 px-4 rounded-xl hover:from-blue-700 hover:to-indigo-700 transition shadow-lg">
                                    <span class="material-symbols-outlined">edit</span>
                                    Editar Atención
                                </a>
                            </div>

                            <!-- Botones de PDF -->
                            <div class="space-y-3">
                                <a href="{{ route('nutricionista.attentions.pdf.download', $appointment) }}" 
                                   class="w-full flex items-center justify-center gap-2 bg-gradient-to-r from-red-500 to-rose-600 text-white font-semibold py-3 px-4 rounded-xl hover:from-red-600 hover:to-rose-700 transition shadow-lg">
                                    <span class="material-symbols-outlined">picture_as_pdf</span>
                                    Descargar PDF
                                </a>
                                
                                <a href="{{ route('nutricionista.attentions.pdf.view', $appointment) }}" 
                                   target="_blank"
                                   class="w-full flex items-center justify-center gap-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 font-semibold py-3 px-4 rounded-xl hover:bg-gray-200 dark:hover:bg-gray-600 transition border border-gray-300 dark:border-gray-600">
                                    <span class="material-symbols-outlined">visibility</span>
                                    Ver PDF
                                </a>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-4">
                            <span class="material-symbols-outlined text-6xl text-gray-300 dark:text-gray-600 mb-2">info</span>
                            <p class="text-sm text-gray-600 dark:text-gray-400">No hay acciones disponibles para esta cita</p>
                        </div>
                    @endif

                    <!-- Información adicional -->
                    <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <h4 class="font-semibold text-gray-900 dark:text-white mb-3 text-sm">Información</h4>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Creada el:</span>
                                <span class="text-gray-900 dark:text-white">{{ ucfirst($appointment->created_at->isoFormat('dddd, DD/MM/YYYY')) }} {{ $appointment->created_at->format('h:i A') }}</span>
                            </div>
                            @if($appointment->appointmentState->name === 'completada' && $appointment->attention)
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Completada el:</span>
                                    <span class="text-gray-900 dark:text-white">{{ ucfirst($appointment->attention->created_at->isoFormat('dddd, DD/MM/YYYY')) }} {{ $appointment->attention->created_at->format('h:i A') }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal de Foto de Perfil -->
            <div x-show="showPhotoModal && {{ $appointment->paciente->personalData?->profile_photo ? 'true' : 'false' }}"
                 x-cloak
                 @keydown.escape.window="showPhotoModal = false"
                 class="fixed inset-0 z-50 flex items-center justify-center p-4"
                 style="display: none;">
                
                <!-- Overlay -->
                <div class="fixed inset-0 bg-black/60 dark:bg-black/80" @click="showPhotoModal = false"></div>
                
                <!-- Modal Content -->
                <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-hidden"
                     @click.stop>
                    
                    <!-- Header -->
                    <div class="bg-gradient-to-r from-green-600 to-emerald-600 px-6 py-4 flex items-center justify-between">
                        <h3 class="text-lg font-bold text-white flex items-center gap-2">
                            <span class="material-symbols-outlined">photo_camera</span>
                            Foto de Perfil - {{ $appointment->paciente->name }}
                        </h3>
                        <button type="button" @click="showPhotoModal = false" class="text-white hover:text-gray-200 transition">
                            <span class="material-symbols-outlined">close</span>
                        </button>
                    </div>
                    
                    <!-- Image -->
                    <div class="p-6 overflow-auto max-h-[calc(90vh-140px)]">
                        @if($appointment->paciente->personalData?->profile_photo)
                            <img src="{{ asset('storage/' . $appointment->paciente->personalData->profile_photo) }}" 
                                 alt="{{ $appointment->paciente->name }}"
                                 class="w-full h-auto rounded-lg">
                        @endif
                    </div>
                    
                    <!-- Footer -->
                    <div class="bg-gray-50 dark:bg-gray-700 px-6 py-4 flex justify-end">
                        <button type="button" @click="showPhotoModal = false" 
                                class="px-6 py-2 bg-gray-200 dark:bg-gray-600 text-gray-800 dark:text-gray-200 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-500 transition">
                            Cerrar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </main>

    @include('layouts.footer')
</body>
@endsection
