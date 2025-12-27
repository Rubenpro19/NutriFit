@extends('layouts.app')

@section('title', 'Detalle de Cita - NutriFit')

@section('content')
<body class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-green-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
    @include('layouts.header')

    <main class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
    <!-- Breadcrumb -->
    <nav class="mb-6 text-sm text-gray-600 dark:text-gray-400">
        <a href="{{ route('nutricionista.dashboard') }}" class="hover:text-emerald-600 dark:hover:text-emerald-400">Dashboard</a>
        <span class="mx-2">/</span>
        <span class="text-gray-900 dark:text-white">Detalle de Cita</span>
    </nav>

    <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center gap-3 mb-2">
                <a href="{{ route('nutricionista.dashboard') }}" class="text-gray-600 dark:text-gray-400 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">
                    <span class="material-symbols-outlined text-2xl">arrow_back</span>
                </a>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                    Detalle de Cita
                </h1>
            </div>
            <p class="text-gray-600 dark:text-gray-400 ml-11">
                Información completa del paciente y la cita
            </p>
        </div>

        @if(session('success'))
            <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-800 dark:text-green-200 rounded-xl p-4 mb-6">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-800 dark:text-red-200 rounded-xl p-4 mb-6">
                {{ session('error') }}
            </div>
        @endif

        <div class="grid lg:grid-cols-3 gap-6">
            <!-- Información Principal -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Información del Paciente -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 border border-gray-200 dark:border-gray-700">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined text-emerald-600">person</span>
                        Información del Paciente
                    </h2>
                    
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-20 h-20 rounded-full bg-gradient-to-br from-emerald-400 to-emerald-600 flex items-center justify-center text-white text-3xl font-bold shadow-lg">
                            {{ strtoupper(substr($appointment->paciente->name, 0, 1)) }}
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                {{ $appointment->paciente->name }}
                            </h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 flex items-center gap-1 mt-1">
                                <span class="material-symbols-outlined text-base">email</span>
                                {{ $appointment->paciente->email }}
                            </p>
                            @if($appointment->paciente->personalData)
                                <p class="text-sm text-gray-600 dark:text-gray-400 flex items-center gap-1 mt-1">
                                    <span class="material-symbols-outlined text-base">phone</span>
                                    {{ $appointment->paciente->personalData->phone ?? 'No disponible' }}
                                </p>
                                @if($appointment->paciente->personalData->date_of_birth)
                                    <p class="text-sm text-gray-600 dark:text-gray-400 flex items-center gap-1 mt-1">
                                        <span class="material-symbols-outlined text-base">cake</span>
                                        {{ \Carbon\Carbon::parse($appointment->paciente->personalData->date_of_birth)->age }} años
                                    </p>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Detalles de la Cita -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 border border-gray-200 dark:border-gray-700">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined text-emerald-600">event</span>
                        Detalles de la Cita
                    </h2>

                    <div class="grid md:grid-cols-2 gap-4">
                        <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-4">
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Fecha</p>
                            <p class="text-lg font-semibold text-gray-900 dark:text-white">
                                {{ \Carbon\Carbon::parse($appointment->start_time)->format('d/m/Y') }}
                            </p>
                        </div>

                        <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-4">
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Hora</p>
                            <p class="text-lg font-semibold text-gray-900 dark:text-white">
                                {{ \Carbon\Carbon::parse($appointment->start_time)->format('H:i') }}
                            </p>
                        </div>

                        <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-4">
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Tipo de Consulta</p>
                            <p class="text-sm font-semibold text-gray-900 dark:text-white">
                                {{ $appointment->appointment_type === 'primera_vez' ? 'Primera vez' : ($appointment->appointment_type === 'seguimiento' ? 'Seguimiento' : 'Control') }}
                            </p>
                        </div>

                        <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-4">
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Duración</p>
                            <p class="text-sm font-semibold text-gray-900 dark:text-white">
                                45 minutos
                            </p>
                        </div>

                        <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-4">
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Precio</p>
                            <p class="text-lg font-bold text-emerald-600 dark:text-emerald-400">
                                ${{ number_format($appointment->price, 2) }}
                            </p>
                        </div>

                        <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-4">
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Estado</p>
                            @php
                                $stateColors = [
                                    'pendiente' => 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300',
                                    'completada' => 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300',
                                    'cancelada' => 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300',
                                    'vencida' => 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300',
                                ];
                            @endphp
                            <span class="inline-block px-3 py-1 rounded-lg text-sm font-semibold {{ $stateColors[$appointment->appointmentState->name] ?? 'bg-gray-100 text-gray-700' }}">
                                {{ ucfirst($appointment->appointmentState->name) }}
                            </span>
                        </div>
                    </div>

                    @if($appointment->reason)
                        <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">Motivo de la Consulta</p>
                            <p class="text-sm text-gray-900 dark:text-white">
                                {{ $appointment->reason }}
                            </p>
                        </div>
                    @endif
                </div>

                <!-- Registro de Atención (solo si existe) -->
                @if($appointment->attention && $appointment->attention->attentionData)
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 border border-gray-200 dark:border-gray-700">
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                            <span class="material-symbols-outlined text-emerald-600">clinical_notes</span>
                            Registro de Atención
                        </h2>

                        <!-- Datos Antropométricos -->
                        <div class="mb-6">
                            <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Medidas Antropométricas</h3>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                <div class="bg-emerald-50 dark:bg-emerald-900/20 rounded-lg p-3 border border-emerald-200 dark:border-emerald-800">
                                    <p class="text-xs text-emerald-600 dark:text-emerald-400 mb-1">Peso</p>
                                    <p class="text-lg font-bold text-emerald-700 dark:text-emerald-300">
                                        {{ $appointment->attention->attentionData->weight }} kg
                                    </p>
                                </div>
                                <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-3 border border-blue-200 dark:border-blue-800">
                                    <p class="text-xs text-blue-600 dark:text-blue-400 mb-1">Altura</p>
                                    <p class="text-lg font-bold text-blue-700 dark:text-blue-300">
                                        {{ $appointment->attention->attentionData->height }} cm
                                    </p>
                                </div>
                                <div class="bg-purple-50 dark:bg-purple-900/20 rounded-lg p-3 border border-purple-200 dark:border-purple-800">
                                    <p class="text-xs text-purple-600 dark:text-purple-400 mb-1">IMC</p>
                                    <p class="text-lg font-bold text-purple-700 dark:text-purple-300">
                                        {{ number_format($appointment->attention->attentionData->bmi, 2) }}
                                    </p>
                                </div>
                                @if($appointment->attention->attentionData->body_fat)
                                    <div class="bg-orange-50 dark:bg-orange-900/20 rounded-lg p-3 border border-orange-200 dark:border-orange-800">
                                        <p class="text-xs text-orange-600 dark:text-orange-400 mb-1">Grasa Corporal</p>
                                        <p class="text-lg font-bold text-orange-700 dark:text-orange-300">
                                            {{ $appointment->attention->attentionData->body_fat }}%
                                        </p>
                                    </div>
                                @endif
                                @if($appointment->attention->attentionData->waist)
                                    <div class="bg-pink-50 dark:bg-pink-900/20 rounded-lg p-3 border border-pink-200 dark:border-pink-800">
                                        <p class="text-xs text-pink-600 dark:text-pink-400 mb-1">Cintura</p>
                                        <p class="text-lg font-bold text-pink-700 dark:text-pink-300">
                                            {{ $appointment->attention->attentionData->waist }} cm
                                        </p>
                                    </div>
                                @endif
                                @if($appointment->attention->attentionData->hip)
                                    <div class="bg-indigo-50 dark:bg-indigo-900/20 rounded-lg p-3 border border-indigo-200 dark:border-indigo-800">
                                        <p class="text-xs text-indigo-600 dark:text-indigo-400 mb-1">Cadera</p>
                                        <p class="text-lg font-bold text-indigo-700 dark:text-indigo-300">
                                            {{ $appointment->attention->attentionData->hip }} cm
                                        </p>
                                    </div>
                                @endif
                                @if($appointment->attention->attentionData->blood_pressure)
                                    <div class="bg-red-50 dark:bg-red-900/20 rounded-lg p-3 border border-red-200 dark:border-red-800">
                                        <p class="text-xs text-red-600 dark:text-red-400 mb-1">Presión Arterial</p>
                                        <p class="text-lg font-bold text-red-700 dark:text-red-300">
                                            {{ $appointment->attention->attentionData->blood_pressure }}
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Diagnóstico -->
                        <div class="mb-4">
                            <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Diagnóstico</h3>
                            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-4">
                                <p class="text-sm text-gray-900 dark:text-white whitespace-pre-line">
                                    {{ $appointment->attention->diagnosis }}
                                </p>
                            </div>
                        </div>

                        <!-- Recomendaciones -->
                        <div>
                            <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Recomendaciones</h3>
                            <div class="bg-emerald-50 dark:bg-emerald-900/20 rounded-xl p-4 border border-emerald-200 dark:border-emerald-800">
                                <p class="text-sm text-gray-900 dark:text-white whitespace-pre-line">
                                    {{ $appointment->attention->recommendations }}
                                </p>
                            </div>
                        </div>

                        <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                Atención registrada el {{ $appointment->attention->created_at->format('d/m/Y') }}
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

                        <!-- Botón de Cancelar -->
                        <form method="POST" action="{{ route('nutricionista.appointments.cancel', $appointment) }}" onsubmit="return confirm('¿Estás seguro de cancelar esta cita?')">
                            @csrf
                            <button type="submit" class="w-full flex items-center justify-center gap-2 bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 font-semibold py-3 px-4 rounded-xl hover:bg-red-200 dark:hover:bg-red-900/50 transition">
                                <span class="material-symbols-outlined">cancel</span>
                                Cancelar Cita
                            </button>
                        </form>
                    @elseif($appointment->appointmentState->name === 'completada')
                        <div class="text-center py-4">
                            <span class="material-symbols-outlined text-6xl text-green-600 dark:text-green-400 mb-2">check_circle</span>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Esta cita ya fue completada</p>
                        </div>
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
                                <span class="text-gray-900 dark:text-white">{{ $appointment->created_at->format('d/m/Y') }}</span>
                            </div>
                            @if($appointment->appointmentState->name === 'completada' && $appointment->attention)
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Completada el:</span>
                                    <span class="text-gray-900 dark:text-white">{{ $appointment->attention->created_at->format('d/m/Y') }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </main>

    @include('layouts.footer')
</body>
@endsection
