@extends('layouts.app')

@section('title', 'Detalle del Paciente - NutriFit')

@section('content')
<body class="min-h-screen bg-gradient-to-br from-green-50 via-emerald-50 to-teal-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 flex flex-col">
    @include('layouts.header')

    <main class="flex-grow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
            <!-- Breadcrumb -->
            <nav class="mb-6 flex items-center gap-2 text-sm">
                <a href="{{ route('nutricionista.dashboard') }}" class="text-green-600 dark:text-green-400 hover:underline transition-colors">Inicio</a>
                <span class="material-symbols-outlined text-gray-400 text-sm">chevron_right</span>
                <a href="{{ route('nutricionista.patients.index') }}" class="text-green-600 dark:text-green-400 hover:underline transition-colors">Mis Pacientes</a>
                <span class="material-symbols-outlined text-gray-400 text-sm">chevron_right</span>
                <span class="text-gray-700 dark:text-gray-300 font-medium">Detalle del Paciente</span>
            </nav>

            <!-- Header -->
            <div class="mb-8 bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-4 sm:p-6 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between gap-3">
                    <div class="flex items-center gap-3 flex-1 min-w-0">
                        <a href="{{ route('nutricionista.patients.index') }}" class="text-gray-700 dark:text-gray-300 hover:text-green-600 dark:hover:text-green-400 hover:scale-110 transition-all">
                            <span class="material-symbols-outlined text-2xl">arrow_back</span>
                        </a>
                        <div class="min-w-0 flex-1">
                            <h1 class="text-2xl sm:text-3xl font-bold bg-gradient-to-r from-green-600 to-emerald-600 dark:from-green-400 dark:to-emerald-400 bg-clip-text text-transparent">
                                Perfil del Paciente
                            </h1>
                            <p class="text-sm sm:text-base text-gray-700 dark:text-gray-300 mt-1 truncate">
                                Información completa y estadísticas
                            </p>
                        </div>
                    </div>
                    <div class="flex-shrink-0 hidden sm:flex items-center">
                        <span class="material-symbols-outlined text-3xl sm:text-4xl text-green-600 dark:text-green-400">person</span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6" x-data="{ showPhotoModal: false }">
                <!-- Columna Principal -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Información del Paciente -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden border border-gray-200 dark:border-gray-700">
                        <div class="bg-gradient-to-r from-green-600 to-emerald-600 p-6">
                            <div class="flex items-center space-x-4">
                                <div class="w-20 h-20 rounded-full overflow-hidden flex items-center justify-center shadow-lg border-4 border-white dark:border-gray-800 {{ $patient->personalData?->profile_photo ? 'cursor-pointer hover:opacity-90 transition' : '' }}"
                                     @if($patient->personalData?->profile_photo) @click="showPhotoModal = true" @endif>
                                    @if($patient->personalData?->profile_photo)
                                        <img src="{{ asset('storage/' . $patient->personalData->profile_photo) }}" 
                                             alt="{{ $patient->name }}" 
                                             class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full bg-white flex items-center justify-center text-green-600 text-2xl font-bold">
                                            {{ $patient->initials() }}
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <h2 class="text-2xl font-bold text-white mb-1">{{ $patient->name }}</h2>
                                    <p class="text-green-100 flex items-center gap-2">
                                        <span class="material-symbols-outlined text-sm">email</span>
                                        {{ $patient->email }}
                                    </p>
                                </div>
                                <span class="px-4 py-2 text-sm font-semibold rounded-full {{ $patient->isActive() ? 'bg-white text-green-600' : 'bg-gray-200 text-gray-700' }}">
                                    {{ ucfirst($patient->userState->name) }}
                                </span>
                            </div>
                        </div>

                        <!-- Datos Personales -->
                        @if($patient->personalData)
                            <div class="p-6 border-t border-gray-200 dark:border-gray-700">
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                                    <span class="material-symbols-outlined text-green-600">badge</span>
                                    Datos Personales
                                </h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @if($patient->personalData->cedula)
                                <div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Cédula</p>
                                    <p class="text-base font-semibold text-gray-900 dark:text-white">
                                        {{ $patient->personalData->cedula }}
                                    </p>
                                </div>
                                @endif
                                @if($patient->personalData->phone)
                                <div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Teléfono</p>
                                    <p class="text-base font-semibold text-gray-900 dark:text-white">
                                        {{ $patient->personalData->phone }}
                                    </p>
                                </div>
                                @endif
                                @if($patient->personalData->birth_date)
                                <div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Fecha de Nacimiento</p>
                                    <p class="text-base font-semibold text-gray-900 dark:text-white">
                                        {{ \Carbon\Carbon::parse($patient->personalData->birth_date)->format('d/m/Y') }}
                                        <span class="text-sm text-gray-600 dark:text-gray-400">({{ \Carbon\Carbon::parse($patient->personalData->birth_date)->age }} años)</span>
                                    </p>
                                </div>
                                @endif
                                @if($patient->personalData->gender)
                                <div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Género</p>
                                    <p class="text-base font-semibold text-gray-900 dark:text-white">
                                        @php
                                            $genderLabels = [
                                                'male' => 'Masculino',
                                                'female' => 'Femenino',
                                                'other' => 'Otro'
                                            ];
                                        @endphp
                                        {{ $genderLabels[$patient->personalData->gender] ?? ucfirst($patient->personalData->gender) }}
                                    </p>
                                </div>
                                @endif
                                @if($patient->personalData->address)
                                <div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Dirección</p>
                                    <p class="text-base font-semibold text-gray-900 dark:text-white">
                                        {{ $patient->personalData->address }}
                                    </p>
                                </div>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Próxima Cita y Estadísticas -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Próxima Cita -->
                    @if($nextAppointment)
                        <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 border-2 border-blue-200 dark:border-blue-700 rounded-xl shadow-lg p-6">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                                <span class="material-symbols-outlined text-blue-600 dark:text-blue-400">event</span>
                                Próxima Cita
                            </h3>
                            <div class="space-y-3">
                                <div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Fecha y Hora</p>
                                    <p class="text-lg font-bold text-gray-900 dark:text-white">
                                        {{ \Carbon\Carbon::parse($nextAppointment->start_time)->format('d/m/Y') }}
                                    </p>
                                    <p class="text-base font-semibold text-blue-600 dark:text-blue-400">
                                        {{ \Carbon\Carbon::parse($nextAppointment->start_time)->format('H:i') }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Tipo</p>
                                    <p class="text-base font-semibold text-gray-900 dark:text-white capitalize">{{ str_replace('_', ' ', $nextAppointment->appointment_type) }}</p>
                                </div>
                                <a 
                                    href="{{ route('nutricionista.appointments.show', $nextAppointment) }}"
                                    class="block w-full text-center px-4 py-3 bg-blue-600 dark:bg-blue-700 text-white font-semibold rounded-lg hover:bg-blue-700 dark:hover:bg-blue-600 hover:scale-105 transition-all mt-4"
                                >
                                    <span class="material-symbols-outlined text-sm align-middle">visibility</span>
                                    Ver Cita
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="bg-gray-50 dark:bg-gray-700/50 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-xl shadow-lg p-6 text-center">
                            <span class="material-symbols-outlined text-5xl text-gray-400 dark:text-gray-600 mb-3 block">event_busy</span>
                            <h3 class="text-base font-bold text-gray-700 dark:text-gray-300 mb-2">Sin citas programadas</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">No hay citas próximas para este paciente</p>
                        </div>
                    @endif

                    <!-- Estadísticas -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                            <span class="material-symbols-outlined text-green-600">analytics</span>
                            Estadísticas de Citas
                        </h3>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-gray-600 dark:text-gray-400 text-lg">event</span>
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Total</span>
                                </div>
                                <span class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['total_appointments'] }}</span>
                            </div>
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <div class="flex items-center gap-2">
                                        <span class="material-symbols-outlined text-green-600 dark:text-green-400 text-lg">check_circle</span>
                                        <span class="text-sm text-gray-600 dark:text-gray-400">Completadas</span>
                                    </div>
                                    <span class="text-xl font-bold text-green-600 dark:text-green-400">{{ $stats['completed'] }}</span>
                                </div>
                                @if($stats['total_appointments'] > 0)
                                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                        <div class="bg-green-600 dark:bg-green-500 h-2 rounded-full" style="width: {{ ($stats['completed'] / $stats['total_appointments']) * 100 }}%"></div>
                                    </div>
                                @endif
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-blue-600 dark:text-blue-400 text-lg">schedule</span>
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Pendientes</span>
                                </div>
                                <span class="text-xl font-bold text-blue-600 dark:text-blue-400">{{ $stats['pending'] }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-red-600 dark:text-red-400 text-lg">cancel</span>
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Canceladas</span>
                                </div>
                                <span class="text-xl font-bold text-red-600 dark:text-red-400">{{ $stats['cancelled'] }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Última Atención (destacada) -->
                @if($lastAttention)
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                                <span class="material-symbols-outlined text-green-600">medical_services</span>
                                Última Atención Registrada
                            </h3>
                            <span class="text-xs text-gray-500 dark:text-gray-400">
                                {{ \Carbon\Carbon::parse($lastAttention->created_at)->format('d/m/Y H:i') }}
                            </span>
                        </div>

                        @if($lastAttention->attentionData)
                            <div class="grid grid-cols-3 gap-4 mb-6">
                                <div class="p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800">
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="material-symbols-outlined text-blue-600 dark:text-blue-400 text-lg">scale</span>
                                        <p class="text-xs text-gray-600 dark:text-gray-400">Peso</p>
                                    </div>
                                    <p class="text-xl font-bold text-gray-900 dark:text-white">{{ $lastAttention->attentionData->weight }} <span class="text-sm font-normal">kg</span></p>
                                </div>
                                <div class="p-4 bg-green-50 dark:bg-green-900/20 rounded-lg border border-green-200 dark:border-green-800">
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="material-symbols-outlined text-green-600 dark:text-green-400 text-lg">height</span>
                                        <p class="text-xs text-gray-600 dark:text-gray-400">Altura</p>
                                    </div>
                                    <p class="text-xl font-bold text-gray-900 dark:text-white">{{ $lastAttention->attentionData->height }} <span class="text-sm font-normal">cm</span></p>
                                </div>
                                <div class="p-4 bg-purple-50 dark:bg-purple-900/20 rounded-lg border border-purple-200 dark:border-purple-800">
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="material-symbols-outlined text-purple-600 dark:text-purple-400 text-lg">monitor_weight</span>
                                        <p class="text-xs text-gray-600 dark:text-gray-400">IMC</p>
                                    </div>
                                    <p class="text-xl font-bold text-gray-900 dark:text-white">{{ $lastAttention->attentionData->bmi }}</p>
                                </div>
                            </div>
                        @endif

                        <div class="space-y-4">
                            <div>
                                <p class="text-sm font-semibold text-gray-700 dark:text-gray-200 mb-2 flex items-center gap-2">
                                    <span class="material-symbols-outlined text-sm">description</span>
                                    Diagnóstico
                                </p>
                                <p class="text-sm text-gray-600 dark:text-gray-300 bg-gray-50 dark:bg-gray-700 p-3 rounded-lg">{{ $lastAttention->diagnosis }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-700 dark:text-gray-200 mb-2 flex items-center gap-2">
                                    <span class="material-symbols-outlined text-sm">health_and_safety</span>
                                    Recomendaciones
                                </p>
                                <p class="text-sm text-gray-600 dark:text-gray-300 bg-gray-50 dark:bg-gray-700 p-3 rounded-lg">{{ $lastAttention->recommendations }}</p>
                            </div>
                        </div>

                        <!-- Botón Ver Historial Completo - Destacado -->
                        <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <a href="{{ route('nutricionista.patients.history', $patient) }}" 
                               class="flex items-center justify-center gap-2 w-full px-6 py-4 bg-gradient-to-r from-green-600 to-emerald-600 text-white font-bold rounded-xl hover:from-green-700 hover:to-emerald-700 hover:shadow-xl hover:scale-105 transition-all text-base shadow-lg">
                                <span class="material-symbols-outlined text-xl">monitoring</span>
                                Ver Historial Clínico Completo
                                <span class="material-symbols-outlined text-xl">arrow_forward</span>
                            </a>
                            <p class="text-xs text-center text-gray-500 dark:text-gray-400 mt-2">Consulta todas las atenciones y gráficas de progreso</p>
                        </div>
                    </div>
                @endif

                <!-- Historial de Citas -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined text-green-600">history</span>
                        Historial de Citas
                    </h3>
                    
                    @if($appointments->isEmpty())
                        <div class="text-center py-12">
                            <span class="material-symbols-outlined text-6xl text-gray-300 dark:text-gray-600 mb-3 block">event_note</span>
                            <p class="text-gray-600 dark:text-gray-400">No hay citas registradas</p>
                        </div>
                    @else
                        <div class="space-y-3">
                            @foreach($appointments as $appointment)
                                <div class="border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 hover:border-green-300 dark:hover:border-green-600 transition-all hover:shadow-md">
                                    <div class="flex items-start justify-between gap-4">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-3 mb-2 flex-wrap">
                                                <span class="text-sm font-semibold text-gray-900 dark:text-white flex items-center gap-1">
                                                    <span class="material-symbols-outlined text-sm">calendar_today</span>
                                                    {{ \Carbon\Carbon::parse($appointment->start_time)->format('d/m/Y H:i') }}
                                                </span>
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full
                                                    {{ $appointment->appointmentState->name === 'completada' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : '' }}
                                                    {{ $appointment->appointmentState->name === 'pendiente' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400' : '' }}
                                                    {{ $appointment->appointmentState->name === 'cancelada' ? 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400' : '' }}
                                                    {{ $appointment->appointmentState->name === 'vencida' ? 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-400' : '' }}">
                                                    {{ ucfirst($appointment->appointmentState->name) }}
                                                </span>
                                            </div>
                                            <p class="text-sm text-gray-600 dark:text-gray-300">
                                                <span class="capitalize">{{ str_replace('_', ' ', $appointment->appointment_type) }}</span> • 
                                                {{ $appointment->duration }} min • 
                                                <span class="font-semibold">${{ number_format($appointment->price, 2) }}</span>
                                            </p>
                                            @if($appointment->notes)
                                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-2 italic">{{ $appointment->notes }}</p>
                                            @endif
                                        </div>
                                        <a 
                                            href="{{ route('nutricionista.appointments.show', $appointment) }}"
                                            class="flex-shrink-0 px-4 py-2 text-sm bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 font-semibold rounded-lg hover:bg-green-200 dark:hover:bg-green-900/50 transition-all border border-green-200 dark:border-green-800 hover:scale-105"
                                        >
                                            <span class="material-symbols-outlined text-sm align-middle">visibility</span>
                                            <span class="hidden sm:inline ml-1">Ver</span>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Acciones Rápidas -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined text-green-600">quick_reference_all</span>
                        Acciones Rápidas
                    </h3>
                    <div class="space-y-3">
                        <a 
                            href="{{ route('nutricionista.patients.index') }}"
                            class="block w-full text-center px-4 py-3 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 font-semibold rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors"
                        >
                            Ver Todos los Pacientes
                        </a>
                        <a 
                            href="{{ route('nutricionista.dashboard') }}"
                            class="block w-full text-center px-4 py-3 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 font-semibold rounded-lg hover:bg-green-200 dark:hover:bg-green-900/50 transition-colors border border-green-200 dark:border-green-800"
                        >
                            Ir al Dashboard
                        </a>
                    </div>
                </div>
            </div>

            <!-- Modal de Foto de Perfil -->
            <div x-show="showPhotoModal && {{ $patient->personalData?->profile_photo ? 'true' : 'false' }}"
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
                            Foto de Perfil - {{ $patient->name }}
                        </h3>
                        <button type="button" @click="showPhotoModal = false" class="text-white hover:text-gray-200 transition">
                            <span class="material-symbols-outlined">close</span>
                        </button>
                    </div>
                    
                    <!-- Image -->
                    <div class="p-6 overflow-auto max-h-[calc(90vh-140px)]">
                        @if($patient->personalData?->profile_photo)
                            <img src="{{ asset('storage/' . $patient->personalData->profile_photo) }}" 
                                 alt="{{ $patient->name }}"
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
