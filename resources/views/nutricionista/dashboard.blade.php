@extends('layouts.app')

@section('title', 'Dashboard Nutricionista')

@section('content')
<body class="min-h-screen bg-gradient-to-br from-green-50 via-emerald-50 to-rose-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 flex flex-col">
    @include('layouts.header')

    <main class="flex-grow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
            
            {{-- Banner informativo cuando no hay horarios configurados --}}
            @if(!$hasSchedules)
                <div class="mb-6 rounded-xl bg-gradient-to-r from-blue-500 to-cyan-500 p-5 shadow-lg animate-pulse-slow">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center">
                                <span class="material-symbols-outlined text-white text-3xl">schedule</span>
                            </div>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-bold text-white mb-1 flex items-center gap-2">
                                <span class="material-symbols-outlined text-xl">info</span>
                                ¡Configura tus horarios para empezar!
                            </h3>
                            <p class="text-blue-50 text-sm mb-3">
                                Para que los pacientes puedan agendar citas contigo, primero debes establecer tu disponibilidad semanal.
                            </p>
                            <a href="{{ route('nutricionista.schedules.index') }}" 
                               class="inline-flex items-center gap-2 rounded-lg bg-white px-5 py-2.5 text-sm font-semibold text-blue-600 transition-all duration-200 hover:bg-blue-50 hover:shadow-lg hover:scale-105 active:scale-95">
                                <span class="material-symbols-outlined">settings</span>
                                Configurar Horarios Ahora
                                <span class="material-symbols-outlined">arrow_forward</span>
                            </a>
                        </div>
                        <button onclick="this.parentElement.parentElement.style.display='none'" 
                                class="flex-shrink-0 text-white/70 hover:text-white transition-colors">
                            <span class="material-symbols-outlined">close</span>
                        </button>
                    </div>
                </div>
            @endif

            {{-- Mensaje de Bienvenida --}}
            <div class="mb-8 rounded-2xl bg-gradient-to-r from-blue-600 to-green-600 p-4 sm:p-6 md:p-8 text-white shadow-lg">
                <div class="flex flex-col gap-4 sm:gap-5 lg:gap-6">
                    <div class="flex-1">
                        <h1 class="text-xl sm:text-2xl md:text-3xl font-bold">¡Buen día, {{ auth()->user()->name }}!</h1>
                        <p class="mt-2 text-sm sm:text-base text-blue-100">Aquí está el resumen de su jornada.</p>
                    </div>
                    {{-- Grid responsive de botones: 1 col en móvil, 2 cols en tablet, 4 cols en desktop grande --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-2 sm:gap-3">
                        <a href="{{ route('nutricionista.appointments.index') }}" 
                           class="flex items-center justify-center gap-2 rounded-lg bg-white px-3 sm:px-4 py-2.5 sm:py-3 text-blue-600 text-sm sm:text-base font-semibold transition-all duration-200 hover:bg-blue-50 hover:shadow-lg hover:scale-105 active:scale-95">
                            <span class="material-symbols-outlined text-xl sm:text-2xl">calendar_month</span>
                            <span class="truncate">Mis Citas</span>
                        </a>
                        <a href="{{ route('nutricionista.appointments.create') }}" 
                           class="flex items-center justify-center gap-2 rounded-lg bg-white px-3 sm:px-4 py-2.5 sm:py-3 text-purple-600 text-sm sm:text-base font-semibold transition-all duration-200 hover:bg-purple-50 hover:shadow-lg hover:scale-105 active:scale-95">
                            <span class="material-symbols-outlined text-xl sm:text-2xl">add_circle</span>
                            <span class="truncate">Asignar Cita</span>
                        </a>
                        <a href="{{ route('nutricionista.patients.index') }}" 
                           class="flex items-center justify-center gap-2 rounded-lg bg-white px-3 sm:px-4 py-2.5 sm:py-3 text-cyan-600 text-sm sm:text-base font-semibold transition-all duration-200 hover:bg-cyan-50 hover:shadow-lg hover:scale-105 active:scale-95">
                            <span class="material-symbols-outlined text-xl sm:text-2xl">groups</span>
                            <span class="truncate">Mis Pacientes</span>
                        </a>
                        <a href="{{ route('nutricionista.schedules.index') }}" 
                           class="flex items-center justify-center gap-2 rounded-lg bg-white px-3 sm:px-4 py-2.5 sm:py-3 text-green-600 text-sm sm:text-base font-semibold transition-all duration-200 hover:bg-green-50 hover:shadow-lg hover:scale-105 active:scale-95 relative">
                            @if(!$hasSchedules)
                                <span class="absolute -top-1 -right-1 flex h-3 w-3">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-3 w-3 bg-red-500"></span>
                                </span>
                            @endif
                            <span class="material-symbols-outlined text-xl sm:text-2xl">schedule</span>
                            <span class="truncate">Gestionar Horarios</span>
                        </a>
                    </div>
                </div>
            </div>

            <div class="grid gap-6 lg:grid-cols-3">
                {{-- Próxima Cita o Configuración Requerida --}}
                @if(!$hasSchedules)
                    {{-- Card de Configuración Requerida --}}
                    <div class="rounded-2xl border-2 border-orange-500 bg-gradient-to-br from-orange-50 to-red-50 p-4 sm:p-6 shadow-xl dark:border-orange-400 dark:from-orange-900/20 dark:to-red-900/20">
                        <div class="text-center">
                            {{-- Icono animado --}}
                            <div class="mb-3 sm:mb-4 flex justify-center">
                                <div class="relative">
                                    <div class="absolute inset-0 rounded-full bg-orange-500/20 animate-ping"></div>
                                    <div class="relative w-16 h-16 sm:w-20 sm:h-20 rounded-full bg-gradient-to-br from-orange-500 to-red-500 flex items-center justify-center shadow-lg">
                                        <span class="material-symbols-outlined text-white text-4xl sm:text-5xl">settings_alert</span>
                                    </div>
                                </div>
                            </div>

                            {{-- Título y descripción --}}
                            <h2 class="mb-2 sm:mb-3 text-xl sm:text-2xl font-bold text-gray-900 dark:text-white">
                                Configuración Pendiente
                            </h2>
                            <p class="mb-2 text-xs sm:text-sm text-gray-700 dark:text-gray-300 font-medium">
                                Para empezar a trabajar, necesitas:
                            </p>

                            {{-- Checklist --}}
                            <div class="mb-4 sm:mb-6 space-y-2">
                                <div class="flex items-center gap-2 sm:gap-3 bg-white/60 dark:bg-gray-800/60 rounded-lg p-2.5 sm:p-3 backdrop-blur-sm">
                                    <div class="flex-shrink-0 w-5 h-5 sm:w-6 sm:h-6 rounded-full bg-orange-500 flex items-center justify-center">
                                        <span class="material-symbols-outlined text-white text-xs sm:text-sm">priority_high</span>
                                    </div>
                                    <span class="text-xs sm:text-sm font-medium text-gray-800 dark:text-gray-200 text-left">
                                        Configurar tus horarios de disponibilidad
                                    </span>
                                </div>
                                <div class="flex items-center gap-2 sm:gap-3 bg-white/40 dark:bg-gray-800/40 rounded-lg p-2.5 sm:p-3">
                                    <div class="flex-shrink-0 w-5 h-5 sm:w-6 sm:h-6 rounded-full bg-gray-300 dark:bg-gray-600 flex items-center justify-center">
                                        <span class="material-symbols-outlined text-gray-600 dark:text-gray-400 text-xs sm:text-sm">schedule</span>
                                    </div>
                                    <span class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 text-left">
                                        Luego podrás asignar citas a tus pacientes
                                    </span>
                                </div>
                            </div>

                            {{-- Botón de acción principal --}}
                            <a href="{{ route('nutricionista.schedules.index') }}" 
                               class="block w-full rounded-lg sm:rounded-xl bg-gradient-to-r from-orange-500 to-red-500 px-4 sm:px-6 py-2.5 sm:py-4 text-center font-bold text-white transition-all duration-200 hover:from-orange-600 hover:to-red-600 hover:shadow-2xl hover:scale-105 active:scale-95 flex items-center justify-center gap-1.5 sm:gap-2 text-sm sm:text-lg">
                                <span class="material-symbols-outlined text-lg sm:text-2xl">schedule</span>
                                <span class="truncate">Configurar Mis Horarios</span>
                                <span class="material-symbols-outlined text-lg sm:text-2xl">arrow_forward</span>
                            </a>

                            {{-- Mensaje motivacional --}}
                            <p class="mt-3 sm:mt-4 text-xs text-gray-600 dark:text-gray-400">
                                <span class="material-symbols-outlined text-xs sm:text-sm align-middle">timer</span>
                                Solo te tomará unos minutos
                            </p>
                        </div>
                    </div>
                @else
                    {{-- Card de Próxima Cita (existente) --}}
                    <div class="rounded-2xl border-2 border-blue-500 bg-white p-6 shadow-lg dark:border-blue-400 dark:bg-gray-800 overflow-hidden">
                        <h2 class="mb-4 text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                            <span class="material-symbols-outlined text-blue-600 dark:text-blue-400">upcoming</span>
                            Próxima Cita
                        </h2>
                        
                        @if($nextAppointment)
                        <div class="space-y-4">
                            {{-- Card del paciente --}}
                            <div class="bg-gradient-to-r from-blue-50 to-cyan-50 dark:from-blue-900/20 dark:to-cyan-900/20 rounded-lg p-4 overflow-hidden">
                                <div class="flex items-center gap-3 mb-4">
                                    <div class="flex-shrink-0">
                                        <div class="w-12 h-12 rounded-full overflow-hidden flex items-center justify-center">
                                            @if($nextAppointment->paciente->personalData?->profile_photo)
                                                <img src="{{ asset('storage/' . $nextAppointment->paciente->personalData->profile_photo) }}" 
                                                     alt="{{ $nextAppointment->paciente->name }}" 
                                                     class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full bg-gradient-to-br from-blue-600 to-cyan-600 flex items-center justify-center text-white font-bold text-base">
                                                    {{ $nextAppointment->paciente->initials() }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h3 class="font-semibold text-base text-gray-900 dark:text-white break-words">
                                            {{ $nextAppointment->paciente->name }}
                                        </h3>
                                        <p class="text-xs text-gray-600 dark:text-gray-400 truncate">
                                            {{ $nextAppointment->paciente->email }}
                                        </p>
                                    </div>
                                </div>

                                {{-- Detalles de la cita --}}
                                <div class="space-y-2.5">
                                    <div class="flex items-center gap-2 text-gray-700 dark:text-gray-300">
                                        <span class="material-symbols-outlined text-base text-blue-600 dark:text-blue-400 flex-shrink-0">calendar_today</span>
                                        <span class="text-xs font-medium truncate">
                                            {{ \Carbon\Carbon::parse($nextAppointment->start_time)->locale('es')->isoFormat('dddd, D [de] MMMM, YYYY') }}
                                        </span>
                                    </div>
                                    
                                    <div class="flex items-center gap-2 text-gray-700 dark:text-gray-300">
                                        <span class="material-symbols-outlined text-base text-blue-600 dark:text-blue-400 flex-shrink-0">schedule</span>
                                        <span class="text-xs font-medium">
                                            {{ \Carbon\Carbon::parse($nextAppointment->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($nextAppointment->end_time)->format('h:i A') }}
                                        </span>
                                    </div>

                                    @if($nextAppointment->appointment_type)
                                        <div class="flex items-center gap-2 text-gray-700 dark:text-gray-300">
                                            <span class="material-symbols-outlined text-base text-blue-600 dark:text-blue-400 flex-shrink-0">medical_services</span>
                                            <span class="text-xs font-medium truncate">
                                                {{ ucfirst(str_replace('_', ' ', $nextAppointment->appointment_type)) }}
                                            </span>
                                        </div>
                                    @endif

                                    @if($nextAppointment->reason)
                                        <div class="flex items-start gap-2 text-gray-700 dark:text-gray-300 pt-2 border-t border-gray-200 dark:border-gray-600">
                                            <span class="material-symbols-outlined text-base text-blue-600 dark:text-blue-400 flex-shrink-0">description</span>
                                            <div class="flex-1 min-w-0">
                                                <span class="text-xs font-medium text-gray-500 dark:text-gray-400 block mb-1">Motivo:</span>
                                                <span class="text-xs line-clamp-2">{{ $nextAppointment->reason }}</span>
                                            </div>
                                        </div>
                                    @endif

                                    {{-- Estado --}}
                                    <div class="flex items-center gap-2 pt-2">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium
                                            @if($nextAppointment->appointmentState->name === 'confirmada') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400
                                            @elseif($nextAppointment->appointmentState->name === 'pendiente') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400
                                            @else bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-400
                                            @endif">
                                            <span class="material-symbols-outlined text-sm mr-1">{{ $nextAppointment->appointmentState->name === 'confirmada' ? 'check_circle' : 'schedule' }}</span>
                                            {{ ucfirst($nextAppointment->appointmentState->name) }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            {{-- Botón de acción --}}
                            <a href="{{ route('nutricionista.appointments.show', $nextAppointment) }}" 
                            class="block w-full rounded-lg bg-gradient-to-r from-blue-600 to-cyan-600 px-4 py-3 text-center font-semibold text-white transition hover:from-blue-700 hover:to-cyan-700 flex items-center justify-center gap-2">
                                <span class="material-symbols-outlined">assignment</span>
                                Gestionar Cita
                            </a>
                        </div>
                        @else
                            <div class="py-8 text-center">
                                <span class="material-symbols-outlined text-6xl text-gray-300 dark:text-gray-600">event_busy</span>
                                <p class="mt-4 text-sm text-gray-500 dark:text-gray-400">No hay citas próximas</p>
                            </div>
                        @endif
                    </div>
                @endif

                {{-- Agenda para Hoy --}}
                <div class="lg:col-span-2 rounded-2xl border border-gray-200 bg-white p-6 shadow-lg dark:border-gray-700 dark:bg-gray-800">
                    <h2 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">Agenda para Hoy</h2>
                    
                    @if($todayAppointments->count() > 0)
                        <div class="space-y-3">
                            @foreach($todayAppointments as $appointment)
                                <div class="flex items-center justify-between rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-700">
                                    <div class="flex items-center gap-3 flex-1">
                                        <div class="w-12 h-12 rounded-full overflow-hidden flex items-center justify-center flex-shrink-0">
                                            @if($appointment->paciente->personalData?->profile_photo)
                                                <img src="{{ asset('storage/' . $appointment->paciente->personalData->profile_photo) }}" 
                                                     alt="{{ $appointment->paciente->name }}" 
                                                     class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full bg-gradient-to-br from-blue-500 to-cyan-500 flex items-center justify-center text-white font-bold text-sm">
                                                    {{ $appointment->paciente->initials() }}
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="font-semibold text-gray-900 dark:text-white truncate">{{ $appointment->paciente->name }}</p>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                                {{ \Carbon\Carbon::parse($appointment->start_time)->format('h:i A') }} - {{ $appointment->appointment_type ? ucfirst(str_replace('_', ' ', $appointment->appointment_type)) : 'Consulta general' }}
                                            </p>
                                        </div>
                                    </div>
                                    <a href="{{ route('nutricionista.appointments.show', $appointment) }}" 
                                       class="rounded-lg bg-white px-4 py-2 text-sm font-medium text-blue-600 transition hover:bg-gray-100 dark:bg-gray-600 dark:text-blue-400 dark:hover:bg-gray-500 flex-shrink-0">
                                        Gestionar
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="py-12 text-center">
                            <span class="material-symbols-outlined text-6xl text-gray-300 dark:text-gray-600">event_available</span>
                            <p class="mt-4 text-sm text-gray-500 dark:text-gray-400">No hay citas programadas para hoy</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Todas las Citas Pendientes (Próximas 4 Semanas) --}}
            <div class="mt-6 sm:mt-8 rounded-xl sm:rounded-2xl border border-gray-200 bg-white shadow-lg dark:border-gray-700 dark:bg-gray-800 overflow-hidden">
                <div class="bg-gradient-to-r from-blue-600 to-green-600 px-4 sm:px-6 py-4 sm:py-5">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                        <div class="flex-1">
                            <h2 class="text-xl sm:text-2xl font-bold text-white flex items-center gap-2">
                                <span class="material-symbols-outlined text-2xl sm:text-3xl">calendar_month</span>
                                Agenda Completa
                            </h2>
                            <p class="text-blue-100 text-xs sm:text-sm mt-1">Todas tus citas pendientes de las próximas 4 semanas</p>
                        </div>
                        <div class="bg-white/20 backdrop-blur-sm px-3 sm:px-4 py-1.5 sm:py-2 rounded-lg flex-shrink-0">
                            <p class="text-xs sm:text-sm text-white font-medium">Total: {{ $upcomingAppointments->flatten()->count() }} citas</p>
                        </div>
                    </div>
                </div>

                <div class="p-6">
                    @if($upcomingAppointments->count() > 0)
                        <div class="space-y-8">
                            @php
                                $weekNumber = 0; // Contador relativo de semanas
                            @endphp
                            @foreach($upcomingAppointments as $weekKey => $weekAppointments)
                                @php
                                    $weekNumber++;
                                    // Obtener la primera cita de la semana para calcular el rango
                                    $firstAppointment = $weekAppointments->flatten()->first();
                                    $weekStart = \Carbon\Carbon::parse($firstAppointment->start_time)->startOfWeek();
                                    $weekEnd = \Carbon\Carbon::parse($firstAppointment->start_time)->endOfWeek();
                                    $isCurrentWeek = now()->between($weekStart, $weekEnd);
                                @endphp

                                {{-- Encabezado de Semana --}}
                                <div class="border-2 @if($isCurrentWeek) border-blue-500 dark:border-blue-400 @else border-gray-300 dark:border-gray-600 @endif rounded-lg sm:rounded-xl overflow-hidden">
                                    <div class="@if($isCurrentWeek) bg-gradient-to-r from-blue-500 to-cyan-500 @else bg-gradient-to-r from-gray-500 to-gray-600 @endif px-4 sm:px-6 py-3 sm:py-4">
                                        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2 sm:gap-0">
                                            <div class="flex items-center gap-2 sm:gap-3">
                                                <div class="bg-white/20 backdrop-blur-sm rounded-lg p-1.5 sm:p-2">
                                                    <span class="material-symbols-outlined text-white text-xl sm:text-2xl">calendar_view_week</span>
                                                </div>
                                                <div>
                                                    <h3 class="text-lg sm:text-xl font-bold text-white flex flex-wrap items-center gap-1.5 sm:gap-2">
                                                        <span>Semana {{ $weekNumber }}</span>
                                                        @if($isCurrentWeek)
                                                            <span class="inline-flex items-center px-2 sm:px-3 py-0.5 sm:py-1 rounded-full text-xs font-medium bg-white/30 backdrop-blur-sm">
                                                                <span class="material-symbols-outlined text-xs sm:text-sm mr-1">schedule</span>
                                                                Semana Actual
                                                            </span>
                                                        @endif
                                                    </h3>
                                                    <p class="text-white/90 text-xs sm:text-sm">
                                                        {{ $weekStart->locale('es')->isoFormat('D [de] MMMM') }} - {{ $weekEnd->locale('es')->isoFormat('D [de] MMMM, YYYY') }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="bg-white/20 backdrop-blur-sm px-3 sm:px-4 py-1.5 sm:py-2 rounded-lg flex-shrink-0">
                                                <p class="text-xs sm:text-sm text-white font-medium">
                                                    {{ $weekAppointments->flatten()->count() }} {{ $weekAppointments->flatten()->count() === 1 ? 'cita' : 'citas' }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Días de la semana --}}
                                    <div class="p-3 sm:p-6 space-y-4 sm:space-y-6">
                                        @foreach($weekAppointments as $date => $appointments)
                                            @php
                                                $dateCarbon = \Carbon\Carbon::parse($date);
                                                $isToday = $dateCarbon->isToday();
                                                $isTomorrow = $dateCarbon->isTomorrow();
                                            @endphp
                                            
                                            <div class="border-l-4 @if($isToday) border-blue-500 @elseif($isTomorrow) border-green-500 @else border-cyan-500 @endif pl-3 sm:pl-6">
                                                {{-- Encabezado de la Fecha --}}
                                                <div class="mb-4 sm:mb-4">
                                                    <div class="flex items-center gap-2.5 sm:gap-3 mb-2.5">
                                                        <div class="@if($isToday) bg-blue-100 dark:bg-blue-900/30 @elseif($isTomorrow) bg-green-100 dark:bg-green-900/30 @else bg-cyan-100 dark:bg-cyan-900/30 @endif rounded-lg px-3 sm:px-4 py-1.5 sm:py-2 flex-shrink-0">
                                                            <p class="text-xl sm:text-2xl font-bold @if($isToday) text-blue-600 dark:text-blue-400 @elseif($isTomorrow) text-green-600 dark:text-green-400 @else text-cyan-600 dark:text-cyan-400 @endif">
                                                                {{ $dateCarbon->format('d') }}
                                                            </p>
                                                            <p class="text-xs font-medium @if($isToday) text-blue-600 dark:text-blue-400 @elseif($isTomorrow) text-green-600 dark:text-green-400 @else text-cyan-600 dark:text-cyan-400 @endif uppercase">
                                                                {{ $dateCarbon->format('M') }}
                                                            </p>
                                                        </div>
                                                        <div class="flex-1 min-w-0">
                                                            <h3 class="text-base sm:text-xl font-bold text-gray-900 dark:text-white capitalize break-words">
                                                                <span class="block sm:inline">{{ $dateCarbon->locale('es')->isoFormat('dddd, D [de] MMMM') }}</span>
                                                                @if($isToday)
                                                                    <span class="inline-flex items-center px-2 sm:px-3 py-0.5 sm:py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400 mt-1 sm:mt-0 sm:ml-2">
                                                                        <span class="material-symbols-outlined text-xs sm:text-sm mr-1">today</span>
                                                                        Hoy
                                                                    </span>
                                                                @elseif($isTomorrow)
                                                                    <span class="inline-flex items-center px-2 sm:px-3 py-0.5 sm:py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400 mt-1 sm:mt-0 sm:ml-2">
                                                                        <span class="material-symbols-outlined text-xs sm:text-sm mr-1">event</span>
                                                                        Mañana
                                                                    </span>
                                                                @endif
                                                            </h3>
                                                            <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400">
                                                                {{ $appointments->count() }} {{ $appointments->count() === 1 ? 'cita programada' : 'citas programadas' }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- Lista de Citas del Día --}}
                                                <div class="space-y-4 sm:space-y-0 sm:grid sm:gap-4 md:grid-cols-2">
                                        @foreach($appointments as $appointment)
                                            <div class="group bg-gradient-to-br from-gray-50 to-white dark:from-gray-700/50 dark:to-gray-800/50 rounded-lg sm:rounded-xl border-2 border-gray-200 dark:border-gray-600 p-4 sm:p-4 hover:shadow-lg hover:border-blue-300 dark:hover:border-blue-500 transition-all duration-200">
                                                {{-- Header con hora --}}
                                                <div class="flex items-start justify-between mb-3 sm:mb-3">
                                                    <div class="flex items-center gap-2 sm:gap-2.5">
                                                        <span class="material-symbols-outlined text-blue-600 dark:text-blue-400 text-lg sm:text-2xl flex-shrink-0">schedule</span>
                                                        <div>
                                                            <p class="font-bold text-gray-900 dark:text-white text-sm sm:text-base">
                                                                {{ \Carbon\Carbon::parse($appointment->start_time)->format('h:i A') }}
                                                            </p>
                                                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                                                {{ \Carbon\Carbon::parse($appointment->end_time)->format('h:i A') }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <span class="inline-flex items-center px-2 sm:px-2.5 py-0.5 sm:py-1 rounded-full text-xs font-medium flex-shrink-0
                                                        @if($appointment->appointmentState->name === 'confirmada') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400
                                                        @elseif($appointment->appointmentState->name === 'pendiente') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400
                                                        @else bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-400
                                                        @endif">
                                                        {{ ucfirst($appointment->appointmentState->name) }}
                                                    </span>
                                                </div>

                                                {{-- Información del Paciente --}}
                                                <div class="flex items-center gap-3 sm:gap-3 mb-3 sm:mb-3 pb-3 sm:pb-3 border-b border-gray-200 dark:border-gray-600">
                                                    <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full flex items-center justify-center flex-shrink-0 overflow-hidden">
                                                        @if($appointment->paciente->personalData?->profile_photo)
                                                            <img src="{{ asset('storage/' . $appointment->paciente->personalData->profile_photo) }}" 
                                                                alt="{{ $appointment->paciente->name }}" 
                                                                class="w-full h-full object-cover">
                                                        @else
                                                            <div class="w-full h-full bg-gradient-to-br from-blue-500 to-cyan-500 flex items-center justify-center text-white font-bold text-xs sm:text-sm">
                                                                {{ $appointment->paciente->initials() }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="flex-1 min-w-0">
                                                        <p class="font-semibold text-gray-900 dark:text-white text-sm sm:text-base truncate">
                                                            {{ $appointment->paciente->name }}
                                                        </p>
                                                        <p class="text-xs text-gray-600 dark:text-gray-400 truncate">
                                                            {{ $appointment->paciente->email }}
                                                        </p>
                                                    </div>
                                                </div>

                                                {{-- Detalles de la Cita --}}
                                                <div class="space-y-2 sm:space-y-2 mb-3 sm:mb-3">
                                                    @if($appointment->appointment_type)
                                                        <div class="flex items-center gap-2 sm:gap-2 text-xs sm:text-sm text-gray-700 dark:text-gray-300">
                                                            <span class="material-symbols-outlined text-sm sm:text-base text-blue-600 dark:text-blue-400 flex-shrink-0">medical_services</span>
                                                            <span class="font-medium truncate">{{ ucfirst(str_replace('_', ' ', $appointment->appointment_type)) }}</span>
                                                        </div>
                                                    @endif

                                                    @if($appointment->reason)
                                                        <div class="flex items-start gap-2 sm:gap-2 text-xs sm:text-sm text-gray-700 dark:text-gray-300">
                                                            <span class="material-symbols-outlined text-sm sm:text-base text-blue-600 dark:text-blue-400 flex-shrink-0">description</span>
                                                            <span class="line-clamp-2">{{ $appointment->reason }}</span>
                                                        </div>
                                                    @endif

                                                    @if($appointment->price)
                                                        <div class="flex items-center gap-2 sm:gap-2 text-xs sm:text-sm text-gray-700 dark:text-gray-300">
                                                            <span class="material-symbols-outlined text-sm sm:text-base text-green-600 dark:text-green-400 flex-shrink-0">payments</span>
                                                            <span class="font-semibold">Pago: ${{ number_format($appointment->price, 2) }}</span>
                                                        </div>
                                                    @endif
                                                </div>

                                                {{-- Botón de Acción --}}
                                                <a href="{{ route('nutricionista.appointments.show', $appointment) }}" 
                                                class="flex items-center justify-center gap-2 sm:gap-2 w-full rounded-lg bg-gradient-to-r from-blue-600 to-cyan-600 px-4 sm:px-4 py-2.5 sm:py-2.5 text-xs sm:text-sm font-semibold text-white transition hover:from-blue-700 hover:to-cyan-700 group-hover:shadow-md">
                                                    <span class="material-symbols-outlined text-base sm:text-lg">assignment</span>
                                                    Gestionar Cita
                                                </a>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="py-16 text-center">
                            <span class="material-symbols-outlined text-7xl text-gray-300 dark:text-gray-600 mb-4">event_available</span>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">No hay citas pendientes</h3>
                            <p class="text-gray-600 dark:text-gray-400 mb-6">
                                No tienes citas programadas para las próximas 4 semanas
                            </p>
                            <a href="{{ route('nutricionista.appointments.create') }}" 
                            class="inline-flex items-center gap-2 rounded-lg bg-gradient-to-r from-blue-600 to-cyan-600 px-6 py-3 font-semibold text-white transition hover:from-blue-700 hover:to-cyan-700 shadow-lg hover:shadow-xl">
                                <span class="material-symbols-outlined">add_circle</span>
                                Asignar Nueva Cita
                            </a>
                        </div>
                    @endif
                </div>
            </div>
            
        </div>
        
    </main>

    @include('layouts.footer')

</body>
@endsection
