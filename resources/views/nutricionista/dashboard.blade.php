@extends('layouts.app')

@section('title', 'Dashboard Nutricionista')

@section('content')
<body class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-green-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
    @include('layouts.header')

    <main class="container mx-auto px-4 py-8">
        {{-- Mensaje de Bienvenida --}}
        <div class="mb-8 rounded-2xl bg-gradient-to-r from-blue-600 to-green-600 p-8 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold">¡Buen día, {{ auth()->user()->name }}!</h1>
                    <p class="mt-2 text-blue-100">Aquí está el resumen de su jornada.</p>
                </div>
                <a href="{{ route('nutricionista.schedules.index') }}" 
                   class="flex items-center gap-2 rounded-lg bg-white px-6 py-3 text-green-600 font-semibold transition hover:bg-green-50">
                    <span class="material-symbols-outlined">schedule</span>
                    Gestionar Horarios
                </a>
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
                <h2 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">Próxima Cita</h2>
                
                @if($nextAppointment)
                    <div class="space-y-3">
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">
                            {{ \Carbon\Carbon::parse($nextAppointment->start_time)->locale('es')->isoFormat('D [de] MMM, YYYY [a las] hh:mm A') }}
                        </p>
                        @if($nextAppointment->reason)
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                <span class="font-medium">Motivo:</span> {{ $nextAppointment->reason }}
                            </p>
                        @endif
                        <a href="{{ route('nutricionista.appointments.show', $nextAppointment) }}" 
                           class="mt-4 block w-full rounded-lg bg-blue-600 px-4 py-3 text-center font-semibold text-white transition hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600">
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
