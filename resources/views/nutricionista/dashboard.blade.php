@extends('layouts.app')

@section('title', 'Dashboard Nutricionista')

@section('content')
<body class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-green-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
    @include('layouts.header')

    <main class="container mx-auto px-4 py-8">
        {{-- Mensaje de Bienvenida --}}
        <div class="mb-8 rounded-2xl bg-gradient-to-r from-blue-600 to-green-600 p-6 md:p-8 text-white shadow-lg">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <div class="flex-1">
                    <h1 class="text-2xl md:text-3xl font-bold">¡Buen día, {{ auth()->user()->name }}!</h1>
                    <p class="mt-2 text-blue-100">Aquí está el resumen de su jornada.</p>
                </div>
                <div class="flex flex-col sm:flex-row gap-3">
                    <a href="{{ route('nutricionista.patients.index') }}" 
                       class="flex items-center justify-center gap-2 rounded-lg bg-white px-4 md:px-6 py-3 text-blue-600 font-semibold transition hover:bg-blue-50 whitespace-nowrap">
                        <span class="material-symbols-outlined">groups</span>
                        Mis Pacientes
                    </a>
                    <a href="{{ route('nutricionista.schedules.index') }}" 
                       class="flex items-center justify-center gap-2 rounded-lg bg-white px-4 md:px-6 py-3 text-green-600 font-semibold transition hover:bg-green-50 whitespace-nowrap">
                        <span class="material-symbols-outlined">schedule</span>
                        Gestionar Horarios
                    </a>
                </div>
            </div>
        </div>

        {{-- Estadísticas --}}
        <div class="mb-8 grid gap-6 md:grid-cols-2">
            {{-- Citas de Hoy --}}
            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-lg dark:border-gray-700 dark:bg-gray-800">
                <div class="text-center">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Citas de Hoy</p>
                    <p class="mt-2 text-5xl font-bold text-blue-600 dark:text-blue-400">{{ $stats['appointments_today'] }}</p>
                </div>
            </div>

            {{-- Citas de la Semana --}}
            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-lg dark:border-gray-700 dark:bg-gray-800">
                <div class="text-center">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Citas de la Semana</p>
                    <p class="mt-2 text-5xl font-bold text-green-600 dark:text-green-400">{{ $stats['appointments_this_week'] }}</p>
                </div>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-3">
            {{-- Próxima Cita --}}
            <div class="rounded-2xl border-2 border-blue-500 bg-white p-6 shadow-lg dark:border-blue-400 dark:bg-gray-800">
                <h2 class="mb-4 text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                    <span class="material-symbols-outlined text-blue-600 dark:text-blue-400">upcoming</span>
                    Próxima Cita
                </h2>
                
                @if($nextAppointment)
                    <div class="space-y-4">
                        {{-- Card del paciente --}}
                        <div class="bg-gradient-to-r from-blue-50 to-cyan-50 dark:from-blue-900/20 dark:to-cyan-900/20 rounded-lg p-4">
                            <div class="flex items-start gap-3 mb-4">
                                <div class="flex-shrink-0">
                                    <div class="w-14 h-14 rounded-full bg-gradient-to-br from-blue-600 to-cyan-600 flex items-center justify-center text-white font-bold text-lg">
                                        {{ $nextAppointment->paciente->initials() }}
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h3 class="font-semibold text-lg text-gray-900 dark:text-white truncate">
                                        {{ $nextAppointment->paciente->name }}
                                    </h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        {{ $nextAppointment->paciente->email }}
                                    </p>
                                </div>
                            </div>

                            {{-- Detalles de la cita --}}
                            <div class="space-y-2.5">
                                <div class="flex items-center gap-2 text-gray-700 dark:text-gray-300">
                                    <span class="material-symbols-outlined text-lg text-blue-600 dark:text-blue-400">calendar_today</span>
                                    <span class="text-sm font-medium">
                                        {{ \Carbon\Carbon::parse($nextAppointment->start_time)->locale('es')->isoFormat('dddd, D [de] MMMM, YYYY') }}
                                    </span>
                                </div>
                                
                                <div class="flex items-center gap-2 text-gray-700 dark:text-gray-300">
                                    <span class="material-symbols-outlined text-lg text-blue-600 dark:text-blue-400">schedule</span>
                                    <span class="text-sm font-medium">
                                        {{ \Carbon\Carbon::parse($nextAppointment->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($nextAppointment->end_time)->format('h:i A') }}
                                    </span>
                                </div>

                                @if($nextAppointment->appointment_type)
                                    <div class="flex items-center gap-2 text-gray-700 dark:text-gray-300">
                                        <span class="material-symbols-outlined text-lg text-blue-600 dark:text-blue-400">medical_services</span>
                                        <span class="text-sm font-medium">
                                            {{ ucfirst(str_replace('_', ' ', $nextAppointment->appointment_type)) }}
                                        </span>
                                    </div>
                                @endif

                                @if($nextAppointment->reason)
                                    <div class="flex items-start gap-2 text-gray-700 dark:text-gray-300 pt-2 border-t border-gray-200 dark:border-gray-600">
                                        <span class="material-symbols-outlined text-lg text-blue-600 dark:text-blue-400">description</span>
                                        <div class="flex-1">
                                            <span class="text-xs font-medium text-gray-500 dark:text-gray-400 block mb-1">Motivo:</span>
                                            <span class="text-sm">{{ $nextAppointment->reason }}</span>
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

            {{-- Agenda para Hoy --}}
            <div class="lg:col-span-2 rounded-2xl border border-gray-200 bg-white p-6 shadow-lg dark:border-gray-700 dark:bg-gray-800">
                <h2 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">Agenda para Hoy</h2>
                
                @if($todayAppointments->count() > 0)
                    <div class="space-y-3">
                        @foreach($todayAppointments as $appointment)
                            <div class="flex items-center justify-between rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-700">
                                <div class="flex-1">
                                    <p class="font-semibold text-gray-900 dark:text-white">{{ $appointment->paciente->name }}</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        {{ \Carbon\Carbon::parse($appointment->start_time)->format('h:i A') }} - {{ $appointment->appointment_type ? ucfirst(str_replace('_', ' ', $appointment->appointment_type)) : 'Consulta general' }}
                                    </p>
                                </div>
                                <a href="{{ route('nutricionista.appointments.show', $appointment) }}" 
                                   class="rounded-lg bg-white px-4 py-2 text-sm font-medium text-blue-600 transition hover:bg-gray-100 dark:bg-gray-600 dark:text-blue-400 dark:hover:bg-gray-500">
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
    </main>

    @include('layouts.footer')
</body>
@endsection
