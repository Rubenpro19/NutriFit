@extends('layouts.app')

@section('title', 'Detalle de Cita - NutriFit')

@section('content')
<body class="min-h-screen bg-gradient-to-br from-purple-50 via-pink-50 to-rose-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 flex flex-col">
    @include('layouts.header')

    <main class="container mx-auto px-4 py-8 flex-grow">
        <!-- Breadcrumb -->
        <nav class="mb-6 flex items-center gap-2 text-sm">
            <a href="{{ route('paciente.dashboard') }}" class="text-purple-600 dark:text-purple-400 hover:underline">Dashboard</a>
            <span class="material-symbols-outlined text-gray-400 text-sm">chevron_right</span>
            <a href="{{ route('paciente.appointments.index') }}" class="text-purple-600 dark:text-purple-400 hover:underline">Mis Citas</a>
            <span class="material-symbols-outlined text-gray-400 text-sm">chevron_right</span>
            <span class="text-gray-600 dark:text-gray-400">Detalle</span>
        </nav>

        @if(session('success'))
            <div class="mb-6 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-800 dark:text-green-200 px-4 py-3 rounded-lg flex items-center gap-3">
                <span class="material-symbols-outlined">check_circle</span>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-800 dark:text-red-200 px-4 py-3 rounded-lg flex items-center gap-3">
                <span class="material-symbols-outlined">error</span>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Contenido Principal -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Información del Nutricionista -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 border border-gray-200 dark:border-gray-700">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined text-purple-600">person</span>
                        Nutricionista
                    </h2>
                    <div class="flex items-start gap-4">
                        <div class="w-20 h-20 bg-gradient-to-br from-purple-500 to-pink-500 rounded-full flex items-center justify-center text-white font-bold text-2xl flex-shrink-0">
                            {{ substr($appointment->nutricionista->name, 0, 1) }}
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                                {{ $appointment->nutricionista->name }}
                            </h3>
                            <div class="space-y-1 text-sm text-gray-600 dark:text-gray-400">
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-sm">email</span>
                                    <span>{{ $appointment->nutricionista->email }}</span>
                                </div>
                                @if($appointment->nutricionista->personalData && $appointment->nutricionista->personalData->phone)
                                    <div class="flex items-center gap-2">
                                        <span class="material-symbols-outlined text-sm">call</span>
                                        <span>{{ $appointment->nutricionista->personalData->phone }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Detalles de la Cita -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 border border-gray-200 dark:border-gray-700">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined text-purple-600">event</span>
                        Detalles de la Cita
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-purple-50 dark:bg-purple-900/20 rounded-xl p-4">
                            <div class="flex items-center gap-2 text-purple-600 dark:text-purple-400 mb-1">
                                <span class="material-symbols-outlined text-sm">calendar_month</span>
                                <span class="text-xs font-semibold uppercase">Fecha</span>
                            </div>
                            <p class="text-gray-900 dark:text-white font-semibold">
                                {{ $appointment->start_time->format('d/m/Y') }}
                            </p>
                        </div>

                        <div class="bg-pink-50 dark:bg-pink-900/20 rounded-xl p-4">
                            <div class="flex items-center gap-2 text-pink-600 dark:text-pink-400 mb-1">
                                <span class="material-symbols-outlined text-sm">schedule</span>
                                <span class="text-xs font-semibold uppercase">Hora</span>
                            </div>
                            <p class="text-gray-900 dark:text-white font-semibold">
                                {{ $appointment->start_time->format('h:i A') }} - {{ $appointment->end_time->format('h:i A') }}
                            </p>
                        </div>

                        <div class="bg-purple-50 dark:bg-purple-900/20 rounded-xl p-4">
                            <div class="flex items-center gap-2 text-purple-600 dark:text-purple-400 mb-1">
                                <span class="material-symbols-outlined text-sm">category</span>
                                <span class="text-xs font-semibold uppercase">Tipo</span>
                            </div>
                            <p class="text-gray-900 dark:text-white font-semibold">
                                {{ $appointment->appointment_type === 'primera_vez' ? 'Primera vez' : 'Seguimiento' }}
                            </p>
                        </div>

                        <div class="bg-pink-50 dark:bg-pink-900/20 rounded-xl p-4">
                            <div class="flex items-center gap-2 text-pink-600 dark:text-pink-400 mb-1">
                                <span class="material-symbols-outlined text-sm">timer</span>
                                <span class="text-xs font-semibold uppercase">Duración</span>
                            </div>
                            <p class="text-gray-900 dark:text-white font-semibold">
                                {{ $appointment->start_time->diffInMinutes($appointment->end_time) }} minutos
                            </p>
                        </div>

                        <div class="bg-purple-50 dark:bg-purple-900/20 rounded-xl p-4">
                            <div class="flex items-center gap-2 text-purple-600 dark:text-purple-400 mb-1">
                                <span class="material-symbols-outlined text-sm">payments</span>
                                <span class="text-xs font-semibold uppercase">Precio</span>
                            </div>
                            <p class="text-gray-900 dark:text-white font-semibold">
                                ${{ number_format($appointment->price, 2) }}
                            </p>
                        </div>

                        <div class="bg-pink-50 dark:bg-pink-900/20 rounded-xl p-4">
                            <div class="flex items-center gap-2 text-pink-600 dark:text-pink-400 mb-1">
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
                            <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Motivo de la Consulta:</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $appointment->reason }}</p>
                        </div>
                    @endif

                    @if($appointment->notes)
                        <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                            <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Notas:</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $appointment->notes }}</p>
                        </div>
                    @endif
                </div>

                <!-- Información de Atención (solo si está completada) -->
                @if($appointment->attention)
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 border border-gray-200 dark:border-gray-700">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                            <span class="material-symbols-outlined text-green-600">medical_services</span>
                            Datos de la Atención
                        </h2>

                        <!-- Datos Antropométricos -->
                        @if($appointment->attention->attentionData)
                            <div class="mb-6">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Medidas Antropométricas</h3>
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                    @if($appointment->attention->attentionData->weight)
                                        <div class="bg-blue-50 dark:bg-blue-900/20 rounded-xl p-4 text-center">
                                            <span class="material-symbols-outlined text-blue-600 dark:text-blue-400 text-3xl mb-1">scale</span>
                                            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $appointment->attention->attentionData->weight }}</p>
                                            <p class="text-xs text-gray-600 dark:text-gray-400">kg</p>
                                        </div>
                                    @endif

                                    @if($appointment->attention->attentionData->height)
                                        <div class="bg-purple-50 dark:bg-purple-900/20 rounded-xl p-4 text-center">
                                            <span class="material-symbols-outlined text-purple-600 dark:text-purple-400 text-3xl mb-1">straighten</span>
                                            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $appointment->attention->attentionData->height }}</p>
                                            <p class="text-xs text-gray-600 dark:text-gray-400">cm</p>
                                        </div>
                                    @endif

                                    @if($appointment->attention->attentionData->bmi)
                                        <div class="bg-green-50 dark:bg-green-900/20 rounded-xl p-4 text-center">
                                            <span class="material-symbols-outlined text-green-600 dark:text-green-400 text-3xl mb-1">monitoring</span>
                                            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $appointment->attention->attentionData->bmi }}</p>
                                            <p class="text-xs text-gray-600 dark:text-gray-400">IMC</p>
                                        </div>
                                    @endif

                                    @if($appointment->attention->attentionData->waist)
                                        <div class="bg-yellow-50 dark:bg-yellow-900/20 rounded-xl p-4 text-center">
                                            <span class="material-symbols-outlined text-yellow-600 dark:text-yellow-400 text-3xl mb-1">fitness_center</span>
                                            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $appointment->attention->attentionData->waist }}</p>
                                            <p class="text-xs text-gray-600 dark:text-gray-400">cm cintura</p>
                                        </div>
                                    @endif

                                    @if($appointment->attention->attentionData->hip)
                                        <div class="bg-pink-50 dark:bg-pink-900/20 rounded-xl p-4 text-center">
                                            <span class="material-symbols-outlined text-pink-600 dark:text-pink-400 text-3xl mb-1">accessibility</span>
                                            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $appointment->attention->attentionData->hip }}</p>
                                            <p class="text-xs text-gray-600 dark:text-gray-400">cm cadera</p>
                                        </div>
                                    @endif

                                    @if($appointment->attention->attentionData->body_fat)
                                        <div class="bg-orange-50 dark:bg-orange-900/20 rounded-xl p-4 text-center">
                                            <span class="material-symbols-outlined text-orange-600 dark:text-orange-400 text-3xl mb-1">analytics</span>
                                            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $appointment->attention->attentionData->body_fat }}</p>
                                            <p class="text-xs text-gray-600 dark:text-gray-400">% grasa</p>
                                        </div>
                                    @endif

                                    @if($appointment->attention->attentionData->blood_pressure)
                                        <div class="bg-red-50 dark:bg-red-900/20 rounded-xl p-4 text-center">
                                            <span class="material-symbols-outlined text-red-600 dark:text-red-400 text-3xl mb-1">favorite</span>
                                            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $appointment->attention->attentionData->blood_pressure }}</p>
                                            <p class="text-xs text-gray-600 dark:text-gray-400">Presión</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <!-- Diagnóstico -->
                        @if($appointment->attention->diagnosis)
                            <div class="mb-6">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3 flex items-center gap-2">
                                    <span class="material-symbols-outlined text-blue-600">description</span>
                                    Diagnóstico
                                </h3>
                                <div class="bg-blue-50 dark:bg-blue-900/20 rounded-xl p-4 border-l-4 border-blue-600">
                                    <p class="text-gray-700 dark:text-gray-300">{{ $appointment->attention->diagnosis }}</p>
                                </div>
                            </div>
                        @endif

                        <!-- Recomendaciones -->
                        @if($appointment->attention->recommendations)
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3 flex items-center gap-2">
                                    <span class="material-symbols-outlined text-green-600">task_alt</span>
                                    Recomendaciones
                                </h3>
                                <div class="bg-green-50 dark:bg-green-900/20 rounded-xl p-4 border-l-4 border-green-600">
                                    <p class="text-gray-700 dark:text-gray-300">{{ $appointment->attention->recommendations }}</p>
                                </div>
                            </div>
                        @endif

                        <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                Atención registrada el {{ $appointment->attention->created_at->format('d/m/Y \a \l\a\s h:i A') }}
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
                        <form method="POST" action="{{ route('paciente.appointments.cancel', $appointment) }}" onsubmit="return confirm('¿Estás seguro de cancelar esta cita?')">
                            @csrf
                            <button type="submit" class="w-full flex items-center justify-center gap-2 bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 font-semibold py-3 px-4 rounded-xl hover:bg-red-200 dark:hover:bg-red-900/50 transition mb-3">
                                <span class="material-symbols-outlined">cancel</span>
                                Cancelar Cita
                            </button>
                        </form>
                    @elseif($appointment->appointmentState->name === 'completada')
                        <div class="text-center py-4 mb-3">
                            <span class="material-symbols-outlined text-6xl text-green-600 dark:text-green-400 mb-2">check_circle</span>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Cita completada exitosamente</p>
                        </div>
                    @endif

                    <a href="{{ route('paciente.appointments.index') }}" class="w-full flex items-center justify-center gap-2 bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-400 font-semibold py-3 px-4 rounded-xl hover:bg-purple-200 dark:hover:bg-purple-900/50 transition">
                        <span class="material-symbols-outlined">arrow_back</span>
                        Volver al Historial
                    </a>

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
    </main>

    @include('layouts.footer')
</body>
@endsection
