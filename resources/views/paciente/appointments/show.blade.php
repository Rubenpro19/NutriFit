@extends('layouts.app')

@section('title', 'Detalle de Cita - NutriFit')

@section('content')

    <body
        class="min-h-screen bg-gradient-to-br from-green-50 via-emerald-50 to-rose-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 flex flex-col">
        @include('layouts.header')

        <main class="flex-grow">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
                <!-- Breadcrumb -->
                <nav class="mb-6 flex items-center gap-2 text-sm">
                    <a href="{{ route('paciente.dashboard') }}"
                        class="text-green-600 dark:text-green-400 hover:underline transition-colors">Inicio</a>
                    <span class="material-symbols-outlined text-gray-400 text-sm">chevron_right</span>
                    <a href="{{ route('paciente.appointments.index') }}"
                        class="text-green-600 dark:text-green-400 hover:underline transition-colors">Mis Citas</a>
                    <span class="material-symbols-outlined text-gray-400 text-sm">chevron_right</span>
                    <span class="text-gray-700 dark:text-gray-300 font-medium">Detalle</span>
                </nav>

                <!-- Header -->
                <div
                    class="mb-8 bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-4 sm:p-6 border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between gap-3">
                        <div class="flex items-center gap-3 flex-1 min-w-0">
                            <a href="{{ route('paciente.appointments.index') }}"
                                class="text-gray-700 dark:text-gray-300 hover:text-green-600 dark:hover:text-green-400 hover:scale-110 transition-all">
                                <span class="material-symbols-outlined text-2xl">arrow_back</span>
                            </a>
                            <div class="min-w-0 flex-1">
                                <h1
                                    class="text-2xl sm:text-3xl font-bold bg-gradient-to-r from-green-600 to-emerald-600 dark:from-green-400 dark:to-emerald-400 bg-clip-text text-transparent">
                                    Detalle de Cita
                                </h1>
                                <p class="text-sm sm:text-base text-gray-700 dark:text-gray-300 mt-1 truncate">
                                    Información completa de tu cita médica
                                </p>
                            </div>
                        </div>
                        <div class="flex-shrink-0 hidden sm:flex items-center">
                            <span
                                class="material-symbols-outlined text-3xl sm:text-4xl text-green-600 dark:text-green-400">event_note</span>
                        </div>
                    </div>
                </div>

                @if(session('success'))
                    <div
                        class="mb-6 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-800 dark:text-green-200 px-4 py-3 rounded-xl flex items-center gap-3">
                        <span class="material-symbols-outlined">check_circle</span>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if(session('error'))
                    <div
                        class="mb-6 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-800 dark:text-red-200 px-4 py-3 rounded-xl flex items-center gap-3">
                        <span class="material-symbols-outlined">error</span>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Contenido Principal -->
                    <div class="lg:col-span-2 space-y-6">
                        <!-- Información del Nutricionista -->
                        <div
                            class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 border border-gray-200 dark:border-gray-700">
                            <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                                <span class="material-symbols-outlined text-green-600">person</span>
                                Nutricionista
                            </h2>
                            <div class="flex items-start gap-4">
                                <div
                                    class="w-20 h-20 bg-gradient-to-br from-green-500 to-emerald-500 rounded-full flex items-center justify-center text-white font-bold text-2xl flex-shrink-0">
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
                        <div
                            class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 border border-gray-200 dark:border-gray-700">
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
                                    <span
                                        class="inline-block px-3 py-1 rounded-full text-xs font-semibold {{ $statusClasses[$appointment->appointmentState->name] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ ucfirst($appointment->appointmentState->name) }}
                                    </span>
                                </div>
                            </div>

                            @if($appointment->reason)
                                <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                                    <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Motivo de la
                                        Consulta:</h3>
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
                            <div
                                class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 border border-gray-200 dark:border-gray-700">
                                <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                                    <span class="material-symbols-outlined text-green-600">medical_services</span>
                                    Datos de la Atención
                                </h2>

                                <!-- Datos Antropométricos -->
                                @if($appointment->attention->attentionData)
                                    <div class="mb-6">
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Medidas Antropométricas
                                        </h3>
                                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                            @if($appointment->attention->attentionData->weight)
                                                <div class="bg-blue-50 dark:bg-blue-900/20 rounded-xl p-4 text-center">
                                                    <span
                                                        class="material-symbols-outlined text-blue-600 dark:text-blue-400 text-3xl mb-1">scale</span>
                                                    <p class="text-2xl font-bold text-gray-900 dark:text-white">
                                                        {{ $appointment->attention->attentionData->weight }}</p>
                                                    <p class="text-xs text-gray-600 dark:text-gray-400">kg</p>
                                                </div>
                                            @endif

                                            @if($appointment->attention->attentionData->height)
                                                <div class="bg-green-50 dark:bg-green-900/20 rounded-xl p-4 text-center">
                                                    <span
                                                        class="material-symbols-outlined text-green-600 dark:text-green-400 text-3xl mb-1">straighten</span>
                                                    <p class="text-2xl font-bold text-gray-900 dark:text-white">
                                                        {{ $appointment->attention->attentionData->height }}</p>
                                                    <p class="text-xs text-gray-600 dark:text-gray-400">cm</p>
                                                </div>
                                            @endif

                                            @if($appointment->attention->attentionData->bmi)
                                                <div class="bg-green-50 dark:bg-green-900/20 rounded-xl p-4 text-center">
                                                    <span
                                                        class="material-symbols-outlined text-green-600 dark:text-green-400 text-3xl mb-1">monitoring</span>
                                                    <p class="text-2xl font-bold text-gray-900 dark:text-white">
                                                        {{ $appointment->attention->attentionData->bmi }}</p>
                                                    <p class="text-xs text-gray-600 dark:text-gray-400">IMC</p>
                                                </div>
                                            @endif

                                            @if($appointment->attention->attentionData->waist)
                                                <div class="bg-yellow-50 dark:bg-yellow-900/20 rounded-xl p-4 text-center">
                                                    <span
                                                        class="material-symbols-outlined text-yellow-600 dark:text-yellow-400 text-3xl mb-1">fitness_center</span>
                                                    <p class="text-2xl font-bold text-gray-900 dark:text-white">
                                                        {{ $appointment->attention->attentionData->waist }}</p>
                                                    <p class="text-xs text-gray-600 dark:text-gray-400">cm cintura</p>
                                                </div>
                                            @endif

                                            @if($appointment->attention->attentionData->hip)
                                                <div class="bg-emerald-50 dark:bg-emerald-900/20 rounded-xl p-4 text-center">
                                                    <span
                                                        class="material-symbols-outlined text-emerald-600 dark:text-emerald-400 text-3xl mb-1">accessibility</span>
                                                    <p class="text-2xl font-bold text-gray-900 dark:text-white">
                                                        {{ $appointment->attention->attentionData->hip }}</p>
                                                    <p class="text-xs text-gray-600 dark:text-gray-400">cm cadera</p>
                                                </div>
                                            @endif

                                            @if($appointment->attention->attentionData->body_fat)
                                                <div class="bg-orange-50 dark:bg-orange-900/20 rounded-xl p-4 text-center">
                                                    <span
                                                        class="material-symbols-outlined text-orange-600 dark:text-orange-400 text-3xl mb-1">analytics</span>
                                                    <p class="text-2xl font-bold text-gray-900 dark:text-white">
                                                        {{ $appointment->attention->attentionData->body_fat }}</p>
                                                    <p class="text-xs text-gray-600 dark:text-gray-400">% grasa</p>
                                                </div>
                                            @endif

                                            @if($appointment->attention->attentionData->blood_pressure)
                                                <div class="bg-red-50 dark:bg-red-900/20 rounded-xl p-4 text-center">
                                                    <span
                                                        class="material-symbols-outlined text-red-600 dark:text-red-400 text-3xl mb-1">favorite</span>
                                                    <p class="text-2xl font-bold text-gray-900 dark:text-white">
                                                        {{ $appointment->attention->attentionData->blood_pressure }}</p>
                                                    <p class="text-xs text-gray-600 dark:text-gray-400">Presión</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                <!-- Diagnóstico -->
                                @if($appointment->attention->diagnosis)
                                    <div class="mb-6">
                                        <h3
                                            class="text-lg font-semibold text-gray-900 dark:text-white mb-3 flex items-center gap-2">
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
                                @endif

                                <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        Atención registrada el
                                        {{ $appointment->attention->created_at->format('d/m/Y \a \l\a\s h:i A') }}
                                    </p>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Sidebar de Acciones -->
                    <div class="lg:col-span-1">
                        <div
                            class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 border border-gray-200 dark:border-gray-700 sticky top-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Acciones</h3>

                            @if($appointment->appointmentState->name === 'pendiente')
                                <div x-data="{ showModal: false, submitting: false }">
                                    <button type="button" @click="showModal = true"
                                        class="w-full flex items-center justify-center gap-2 bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 font-semibold py-3 px-4 rounded-xl hover:bg-red-200 dark:hover:bg-red-900/50 transition mb-3">
                                        <span class="material-symbols-outlined">cancel</span>
                                        Cancelar Cita
                                    </button>

                                    <!-- Modal de Confirmación -->
                                    <div x-show="showModal" x-cloak
                                        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/30 backdrop-blur-sm"
                                        @click.self="!submitting && (showModal = false)">
                                        <div @click.away="!submitting && (showModal = false)"
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
                                                    Esta acción no se puede deshacer. El nutricionista será notificado de la cancelación.
                                                </p>
                                            </div>

                                            <div class="flex gap-3">
                                                <button type="button" @click="showModal = false"
                                                    :disabled="submitting"
                                                    class="flex-1 px-4 py-3 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 font-semibold rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition disabled:opacity-50 disabled:cursor-not-allowed">
                                                    No, mantener
                                                </button>
                                                <form method="POST" action="{{ route('paciente.appointments.cancel', $appointment) }}" class="flex-1" @submit="submitting = true">
                                                    @csrf
                                                    <button type="submit"
                                                        :disabled="submitting"
                                                        class="w-full px-4 py-3 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2">
                                                        <svg x-show="submitting" class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                        </svg>
                                                        <span x-text="submitting ? 'Cancelando...' : 'Sí, cancelar'"></span>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @elseif($appointment->appointmentState->name === 'completada')
                                <div class="text-center py-4 mb-3">
                                    <span
                                        class="material-symbols-outlined text-6xl text-green-600 dark:text-green-400 mb-2">check_circle</span>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Cita completada exitosamente</p>
                                </div>
                            @endif

                            <a href="{{ route('paciente.appointments.index') }}"
                                class="w-full flex items-center justify-center gap-2 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 font-semibold py-3 px-4 rounded-xl hover:bg-green-200 dark:hover:bg-green-900/50 transition">
                                <span class="material-symbols-outlined">arrow_back</span>
                                Volver al Historial
                            </a>

                            <!-- Información adicional -->
                            <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                                <h4 class="font-semibold text-gray-900 dark:text-white mb-3 text-sm">Información</h4>
                                <div class="space-y-2 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">Creada el:</span>
                                        <span
                                            class="text-gray-900 dark:text-white">{{ $appointment->created_at->format('d/m/Y') }}</span>
                                    </div>
                                    @if($appointment->appointmentState->name === 'completada' && $appointment->attention)
                                        <div class="flex justify-between">
                                            <span class="text-gray-600 dark:text-gray-400">Completada el:</span>
                                            <span
                                                class="text-gray-900 dark:text-white">{{ $appointment->attention->created_at->format('d/m/Y') }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </main>

        @include('layouts.footer')

    <!-- Toast de Éxito - Cita Cancelada -->
    @if(session('cancellation_success'))
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
                class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl border-l-4 border-red-500 overflow-hidden">
            <div class="p-4">
                <div class="flex items-start gap-3">
                    <div class="flex-shrink-0">
                        <div class="bg-red-100 dark:bg-red-900/30 rounded-full p-2">
                            <span class="material-symbols-outlined text-red-600 dark:text-red-400 text-2xl">cancel</span>
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="text-sm font-bold text-gray-900 dark:text-white mb-1">
                            Cita Cancelada
                        </h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            {{ session('cancellation_success') }}
                        </p>
                    </div>
                    <button @click="show = false"
                            class="flex-shrink-0 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>
            </div>
            <div class="h-1 bg-gray-200 dark:bg-gray-700">
                <div class="h-full bg-red-500 transition-all duration-100" style="width: 100%; animation: shrink 5s linear forwards;"></div>
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
</body>
@endsection