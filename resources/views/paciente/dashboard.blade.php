@extends('layouts.app')

@section('title', 'Dashboard Paciente - NutriFit')

@section('content')

    <body class="min-h-screen bg-gradient-to-br from-green-50 via-emerald-50 to-rose-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 flex flex-col">
        @include('layouts.header')

        <main class="flex-grow">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
            <!-- Header -->
            <div class="mb-8 rounded-2xl bg-gradient-to-r from-green-600 to-emerald-600 p-6 md:p-8 text-white shadow-lg">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <h1 class="text-3xl font-bold mb-2">¡Hola, {{ auth()->user()->name }}!</h1>
                        <p class="text-green-100">Bienvenido a tu panel de nutrición personalizada</p>
                    </div>
                    <div class="hidden sm:block">
                        <div class="w-16 h-16 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center">
                            <span class="material-symbols-outlined text-4xl text-white">spa</span>
                        </div>
                    </div>
                </div>
            </div>

            @if (session('success'))
                <div class="mb-6 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-800 dark:text-green-200 px-4 py-3 rounded-lg flex items-center gap-3">
                    <span class="material-symbols-outlined">check_circle</span>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-800 dark:text-red-200 px-4 py-3 rounded-lg flex items-center gap-3">
                    <span class="material-symbols-outlined">error</span>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <div class="grid gap-6 lg:grid-cols-3">
                {{-- Columna Principal --}}
                <div class="lg:col-span-2 space-y-6">
                    @if ($nextAppointment)
                        {{-- Próxima Cita --}}
                        <div class="rounded-2xl border-2 border-green-500 bg-white p-6 shadow-lg dark:border-green-400 dark:bg-gray-800">
                            <div class="flex items-center gap-2 mb-6">
                                <span class="material-symbols-outlined text-green-600 dark:text-green-400 text-3xl">upcoming</span>
                                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Tu Próxima Cita</h2>
                            </div>

                            <div class="bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 rounded-xl p-4 sm:p-6">
                                <div class="flex items-start gap-3 sm:gap-4 mb-4 sm:mb-6">
                                    @if($nextAppointment->nutricionista->personalData?->profile_photo)
                                        <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-full overflow-hidden shadow-lg flex-shrink-0">
                                            <img src="{{ asset('storage/' . $nextAppointment->nutricionista->personalData->profile_photo) }}" 
                                                 alt="{{ $nextAppointment->nutricionista->name }}" 
                                                 class="w-full h-full object-cover">
                                        </div>
                                    @else
                                        <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-full bg-gradient-to-br from-green-600 to-emerald-600 flex items-center justify-center text-white font-bold text-xl sm:text-2xl flex-shrink-0 shadow-lg">
                                            {{ $nextAppointment->nutricionista->initials() }}
                                        </div>
                                    @endif
                                    <div class="flex-1 min-w-0">
                                        <h3 class="font-bold text-lg sm:text-2xl text-gray-900 dark:text-white mb-1 truncate">
                                            {{ $nextAppointment->nutricionista->name }}
                                        </h3>
                                        <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 mb-2">Nutricionista Profesional</p>
                                        <div class="flex items-center gap-2 text-xs sm:text-sm text-gray-500 dark:text-gray-500">
                                            <span class="material-symbols-outlined text-base sm:text-lg flex-shrink-0">email</span>
                                            <span class="truncate">{{ $nextAppointment->nutricionista->email }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4 mb-4 sm:mb-6">
                                    <div class="bg-white dark:bg-gray-700 rounded-xl p-3 sm:p-4 shadow">
                                        <div class="flex items-center gap-2 sm:gap-3 mb-2">
                                            <span class="material-symbols-outlined text-green-600 dark:text-green-400 text-xl sm:text-2xl">calendar_today</span>
                                            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Fecha</p>
                                        </div>
                                        <p class="font-bold text-base sm:text-lg text-gray-900 dark:text-white break-words">
                                            {{ \Carbon\Carbon::parse($nextAppointment->start_time)->locale('es')->isoFormat('dddd, D [de] MMMM') }}
                                        </p>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">
                                            {{ \Carbon\Carbon::parse($nextAppointment->start_time)->locale('es')->isoFormat('YYYY') }}
                                        </p>
                                    </div>

                                    <div class="bg-white dark:bg-gray-700 rounded-xl p-3 sm:p-4 shadow">
                                        <div class="flex items-center gap-2 sm:gap-3 mb-2">
                                            <span class="material-symbols-outlined text-green-600 dark:text-green-400 text-xl sm:text-2xl">schedule</span>
                                            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Hora</p>
                                        </div>
                                        <p class="font-bold text-base sm:text-lg text-gray-900 dark:text-white">
                                            {{ \Carbon\Carbon::parse($nextAppointment->start_time)->format('h:i A') }}
                                        </p>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">Duración: 45 min</p>
                                    </div>
                                </div>

                                @if ($nextAppointment->reason)
                                    <div class="bg-white dark:bg-gray-700 rounded-xl p-3 sm:p-4 mb-4 sm:mb-6 shadow">
                                        <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase mb-2">Motivo de consulta</p>
                                        <p class="text-sm sm:text-base text-gray-900 dark:text-white break-words">{{ $nextAppointment->reason }}</p>
                                    </div>
                                @endif

                                <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
                                    <a href="{{ route('paciente.appointments.show', $nextAppointment) }}"
                                        class="flex-1 rounded-xl bg-gradient-to-r from-green-600 to-emerald-600 px-4 sm:px-6 py-3 sm:py-4 text-center text-sm sm:text-base font-bold text-white transition hover:from-green-700 hover:to-emerald-700 flex items-center justify-center gap-2 shadow-lg hover:shadow-xl">
                                        <span class="material-symbols-outlined text-xl">visibility</span>
                                        <span class="truncate">Ver Detalles</span>
                                    </a>
                                    <div x-data="{ showModal: false, submitting: false }" class="flex-1">
                                        <button type="button" @click="showModal = true"
                                            class="w-full rounded-xl bg-red-600 px-4 sm:px-6 py-3 sm:py-4 text-center text-sm sm:text-base font-bold text-white transition hover:bg-red-700 flex items-center justify-center gap-2 shadow-lg hover:shadow-xl">
                                            <span class="material-symbols-outlined text-xl">cancel</span>
                                            <span class="truncate">Cancelar Cita</span>
                                        </button>

                                        <!-- Modal de Confirmación -->
                                        <div x-show="showModal" x-cloak
                                            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm"
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
                                                    <form method="POST" action="{{ route('paciente.appointments.cancel', $nextAppointment) }}" class="flex-1" @submit="submitting = true">
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
                                </div>
                            </div>
                        </div>
                    @else
                        {{-- Call to Action: Agendar Cita --}}
                        <div class="rounded-2xl border-2 border-dashed border-green-300 dark:border-green-600 bg-gradient-to-br from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 p-8 shadow-lg text-center">
                            <div class="mb-6">
                                <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-gradient-to-br from-green-600 to-emerald-600 mb-4 shadow-lg">
                                    <span class="material-symbols-outlined text-5xl text-white">calendar_add_on</span>
                                </div>
                                <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-3">
                                    ¡Agenda tu próxima consulta!
                                </h2>
                                <p class="text-lg text-gray-600 dark:text-gray-400 mb-2">
                                    Continúa tu seguimiento nutricional con un profesional
                                </p>
                                <p class="text-sm text-gray-500 dark:text-gray-500">
                                    Selecciona el mejor horario para tu próxima cita
                                </p>
                            </div>

                            <a href="{{ route('paciente.booking.index') }}"
                                class="inline-flex items-center gap-3 rounded-xl bg-gradient-to-r from-green-600 to-emerald-600 px-8 py-4 text-lg font-bold text-white transition hover:from-green-700 hover:to-emerald-700 shadow-xl hover:shadow-2xl hover:scale-105">
                                <span class="material-symbols-outlined text-2xl">add_circle</span>
                                Agendar Nueva Cita
                            </a>

                            <div class="mt-8 pt-6 border-t border-green-200 dark:border-green-700">
                                
                                <div class="relative">
                                    <!-- Línea de progreso -->
                                    <div class="absolute top-6 left-0 right-0 h-0.5 bg-gradient-to-r from-green-300 via-blue-300 to-emerald-300 dark:from-green-700 dark:via-blue-700 dark:to-emerald-700" style="margin: 0 10%;"></div>
                                    
                                    <div class="relative flex items-start justify-between px-4">
                                        <!-- Paso 1 -->
                                        <div class="flex-1 text-center">
                                            <div class="relative inline-flex items-center justify-center mb-3">
                                                <div class="w-14 h-14 rounded-full bg-gradient-to-br from-green-500 to-green-600 dark:from-green-600 dark:to-green-700 flex items-center justify-center border-4 border-white dark:border-gray-800 relative z-10">
                                                    <span class="text-2xl font-bold text-white">1</span>
                                                </div>
                                            </div>
                                            <div class="px-2">
                                                <div class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-green-100 dark:bg-green-900/30 mb-2">
                                                    <span class="material-symbols-outlined text-lg text-green-600 dark:text-green-400">search</span>
                                                </div>
                                                <p class="text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">Elige tu nutricionista</p>
                                                <p class="text-[10px] text-gray-500 dark:text-gray-500">Explora perfiles</p>
                                            </div>
                                        </div>
                                        
                                        <!-- Paso 2 -->
                                        <div class="flex-1 text-center">
                                            <div class="relative inline-flex items-center justify-center mb-3">
                                                <div class="w-14 h-14 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 dark:from-blue-600 dark:to-blue-700 flex items-center justify-center border-4 border-white dark:border-gray-800 relative z-10">
                                                    <span class="text-2xl font-bold text-white">2</span>
                                                </div>
                                            </div>
                                            <div class="px-2">
                                                <div class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-blue-100 dark:bg-blue-900/30 mb-2">
                                                    <span class="material-symbols-outlined text-lg text-blue-600 dark:text-blue-400">event_available</span>
                                                </div>
                                                <p class="text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">Selecciona horario</p>
                                                <p class="text-[10px] text-gray-500 dark:text-gray-500">Elige fecha y hora</p>
                                            </div>
                                        </div>
                                        
                                        <!-- Paso 3 -->
                                        <div class="flex-1 text-center">
                                            <div class="relative inline-flex items-center justify-center mb-3">
                                                <div class="w-14 h-14 rounded-full bg-gradient-to-br from-emerald-500 to-emerald-600 dark:from-emerald-600 dark:to-emerald-700 flex items-center justify-center border-4 border-white dark:border-gray-800 relative z-10">
                                                    <span class="text-2xl font-bold text-white">3</span>
                                                </div>
                                            </div>
                                            <div class="px-2">
                                                <div class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-emerald-100 dark:bg-emerald-900/30 mb-2">
                                                    <span class="material-symbols-outlined text-lg text-emerald-600 dark:text-emerald-400">check_circle</span>
                                                </div>
                                                <p class="text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">¡Listo para tu cita!</p>
                                                <p class="text-[10px] text-gray-500 dark:text-gray-500">Confirma tu reserva</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Últimas Citas --}}
                    @if ($recentAppointments->count() > 0)
                        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-lg dark:border-gray-700 dark:bg-gray-800">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                                    <span class="material-symbols-outlined text-green-600 dark:text-green-400">history</span>
                                    Citas Recientes
                                </h3>
                                <a href="{{ route('paciente.appointments.index') }}" 
                                   class="text-sm text-green-600 dark:text-green-400 hover:underline font-semibold">
                                    Ver todas →
                                </a>
                            </div>

                            <div class="space-y-3">
                                @foreach($recentAppointments->take(3) as $appointment)
                                    <div class="flex items-center gap-4 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                                        @if($appointment->nutricionista->personalData?->profile_photo)
                                            <div class="w-12 h-12 rounded-full overflow-hidden flex-shrink-0">
                                                <img src="{{ asset('storage/' . $appointment->nutricionista->personalData->profile_photo) }}" 
                                                     alt="{{ $appointment->nutricionista->name }}" 
                                                     class="w-full h-full object-cover">
                                            </div>
                                        @else
                                            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-green-600 to-emerald-600 flex items-center justify-center text-white font-bold flex-shrink-0">
                                                {{ $appointment->nutricionista->initials() }}
                                            </div>
                                        @endif
                                        <div class="flex-1 min-w-0">
                                            <p class="font-semibold text-gray-900 dark:text-white truncate">
                                                {{ $appointment->nutricionista->name }}
                                            </p>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                                {{ \Carbon\Carbon::parse($appointment->start_time)->locale('es')->isoFormat('D MMM, h:mm A') }}
                                            </p>
                                        </div>
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold
                                            @if($appointment->appointmentState->name === 'completada') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400
                                            @elseif($appointment->appointmentState->name === 'cancelada') bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400
                                            @elseif($appointment->appointmentState->name === 'vencida') bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-400
                                            @else bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400
                                            @endif">
                                            {{ ucfirst($appointment->appointmentState->name) }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Columna Lateral --}}
                <div class="space-y-6">
                    {{-- Accesos Rápidos --}}
                    <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-lg dark:border-gray-700 dark:bg-gray-800">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                            <span class="material-symbols-outlined text-green-600 dark:text-green-400">bolt</span>
                            Accesos Rápidos
                        </h3>
                        
                        <div class="space-y-3">
                            <a href="{{ route('paciente.profile') }}"
                               class="flex items-center gap-3 p-4 rounded-xl bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 hover:from-green-100 hover:to-emerald-100 dark:hover:from-green-900/30 dark:hover:to-emerald-900/30 transition group">
                                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-green-600 to-emerald-600 flex items-center justify-center group-hover:scale-110 transition">
                                    <span class="material-symbols-outlined text-white">person</span>
                                </div>
                                <div class="flex-1">
                                    <p class="font-semibold text-gray-900 dark:text-white">Mi Perfil</p>
                                    <p class="text-xs text-gray-600 dark:text-gray-400">Editar información personal</p>
                                </div>
                                <span class="material-symbols-outlined text-gray-400 group-hover:text-green-600 dark:group-hover:text-green-400">chevron_right</span>
                            </a>

                            <a href="{{ route('paciente.appointments.index') }}"
                               class="flex items-center gap-3 p-4 rounded-xl bg-gradient-to-r from-blue-50 to-cyan-50 dark:from-blue-900/20 dark:to-cyan-900/20 hover:from-blue-100 hover:to-cyan-100 dark:hover:from-blue-900/30 dark:hover:to-cyan-900/30 transition group">
                                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-600 to-cyan-600 flex items-center justify-center group-hover:scale-110 transition">
                                    <span class="material-symbols-outlined text-white">calendar_month</span>
                                </div>
                                <div class="flex-1">
                                    <p class="font-semibold text-gray-900 dark:text-white">Mis Citas</p>
                                    <p class="text-xs text-gray-600 dark:text-gray-400">Ver todas tus citas</p>
                                </div>
                                <span class="material-symbols-outlined text-gray-400 group-hover:text-blue-600 dark:group-hover:text-blue-400">chevron_right</span>
                            </a>

                            <a href="{{ route('paciente.history') }}"
                               class="flex items-center gap-3 p-4 rounded-xl bg-gradient-to-r from-purple-50 to-violet-50 dark:from-purple-900/20 dark:to-violet-900/20 hover:from-purple-100 hover:to-violet-100 dark:hover:from-purple-900/30 dark:hover:to-violet-900/30 transition group">
                                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-purple-600 to-violet-600 flex items-center justify-center group-hover:scale-110 transition">
                                    <span class="material-symbols-outlined text-white">monitoring</span>
                                </div>
                                <div class="flex-1">
                                    <p class="font-semibold text-gray-900 dark:text-white">Mi Historial Clínico</p>
                                    <p class="text-xs text-gray-600 dark:text-gray-400">Ver progreso y gráficas</p>
                                </div>
                                <span class="material-symbols-outlined text-gray-400 group-hover:text-purple-600 dark:group-hover:text-purple-400">chevron_right</span>
                            </a>

                            @if(!$nextAppointment)
                                <a href="{{ route('paciente.booking.index') }}"
                                   class="flex items-center gap-3 p-4 rounded-xl bg-gradient-to-r from-purple-50 to-pink-50 dark:from-purple-900/20 dark:to-pink-900/20 hover:from-purple-100 hover:to-pink-100 dark:hover:from-purple-900/30 dark:hover:to-pink-900/30 transition group">
                                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-purple-600 to-pink-600 flex items-center justify-center group-hover:scale-110 transition">
                                        <span class="material-symbols-outlined text-white">add_circle</span>
                                    </div>
                                    <div class="flex-1">
                                        <p class="font-semibold text-gray-900 dark:text-white">Agendar Cita</p>
                                        <p class="text-xs text-gray-600 dark:text-gray-400">Nueva consulta</p>
                                    </div>
                                    <span class="material-symbols-outlined text-gray-400 group-hover:text-purple-600 dark:group-hover:text-purple-400">chevron_right</span>
                                </a>
                            @endif
                        </div>
                    </div>

                    {{-- Estadísticas --}}
                    @if($stats['total'] > 0)
                        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-lg dark:border-gray-700 dark:bg-gray-800">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                                <span class="material-symbols-outlined text-green-600 dark:text-green-400">analytics</span>
                                Tus Estadísticas
                            </h3>
                            
                            <div class="space-y-4">
                                <div class="bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 rounded-xl p-4">
                                    <div class="flex items-center justify-between mb-1">
                                        <p class="text-sm font-semibold text-gray-600 dark:text-gray-400">Total de Citas</p>
                                        <span class="material-symbols-outlined text-green-600 dark:text-green-400">event</span>
                                    </div>
                                    <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $stats['total'] }}</p>
                                </div>

                                <div class="bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 rounded-xl p-4">
                                    <div class="flex items-center justify-between mb-1">
                                        <p class="text-sm font-semibold text-gray-600 dark:text-gray-400">Completadas</p>
                                        <span class="material-symbols-outlined text-green-600 dark:text-green-400">check_circle</span>
                                    </div>
                                    <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $stats['completadas'] }}</p>
                                </div>

                                <div class="bg-gradient-to-r from-blue-50 to-cyan-50 dark:from-blue-900/20 dark:to-cyan-900/20 rounded-xl p-4">
                                    <div class="flex items-center justify-between mb-1">
                                        <p class="text-sm font-semibold text-gray-600 dark:text-gray-400">Pendientes</p>
                                        <span class="material-symbols-outlined text-blue-600 dark:text-blue-400">pending</span>
                                    </div>
                                    <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $stats['pendientes'] }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Info Card --}}
                    <div class="rounded-2xl border border-gray-200 bg-gradient-to-br from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 p-6 shadow-lg dark:border-gray-700">
                        <div class="flex items-start gap-3 mb-3">
                            <span class="material-symbols-outlined text-green-600 dark:text-green-400 text-2xl">info</span>
                            <div>
                                <h4 class="font-bold text-gray-900 dark:text-white mb-1">¿Sabías que?</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    Solo puedes tener una cita activa a la vez. Esto asegura que recibas la atención personalizada que mereces.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </div>
        </main>

        @include('layouts.footer')
        
        <!-- Toast de Éxito - Cita Agendada -->
        @if(session('booking_success'))
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
                        <div class="flex-shrink-0">
                            <div class="bg-green-100 dark:bg-green-900/30 rounded-full p-2">
                                <span class="material-symbols-outlined text-green-600 dark:text-green-400 text-2xl">event_available</span>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-bold text-gray-900 dark:text-white mb-1">
                                ¡Cita Agendada!
                            </h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                {{ session('booking_success') }}
                            </p>
                        </div>
                        <button @click="show = false"
                                class="flex-shrink-0 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors">
                            <span class="material-symbols-outlined">close</span>
                        </button>
                    </div>
                </div>
                <div class="h-1 bg-gray-200 dark:bg-gray-700">
                    <div class="h-full bg-green-500 transition-all duration-100" style="width: 100%; animation: shrink 5s linear forwards;"></div>
                </div>
            </div>
        </div>

        @endif
        
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
        @endif

        <style>
            @keyframes shrink {
                from { width: 100%; }
                to { width: 0%; }
            }
        </style>
    </body>
@endsection
