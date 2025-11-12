@extends('layouts.app')

@section('title', 'Mi Panel - NutriFit')

@section('content')
<body class="min-h-screen bg-gradient-to-br from-purple-50 via-white to-pink-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
    @include('layouts.header')

    <main class="container mx-auto px-4 py-8">
        {{-- Mensaje de Bienvenida --}}
        <div class="mb-8 rounded-2xl bg-gradient-to-r from-purple-600 to-pink-600 p-8 text-white shadow-lg">
            <div class="flex items-center justify-between flex-wrap gap-4">
                <div>
                    <h1 class="text-3xl font-bold">¡Hola, {{ auth()->user()->name }}!</h1>
                    <p class="mt-2 text-purple-100">Bienvenido a tu panel de gestión de citas</p>
                </div>
                <a href="{{ route('paciente.booking.show') }}" 
                   class="flex items-center gap-2 rounded-lg bg-white px-6 py-3 text-purple-600 font-semibold transition hover:bg-purple-50">
                    <span class="material-symbols-outlined">add_circle</span>
                    Agendar Nueva Cita
                </a>
            </div>
        </div>

        {{-- Estadísticas --}}
        <div class="mb-8 grid gap-6 md:grid-cols-3">
            {{-- Total de Citas --}}
            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-lg dark:border-gray-700 dark:bg-gray-800">
                <div class="text-center">
                    <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-purple-100 dark:bg-purple-900/30 mb-3">
                        <span class="material-symbols-outlined text-purple-600 dark:text-purple-400">event</span>
                    </div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total de Citas</p>
                    <p class="mt-2 text-4xl font-bold text-purple-600 dark:text-purple-400">{{ $stats['total_appointments'] }}</p>
                </div>
            </div>

            {{-- Citas Completadas --}}
            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-lg dark:border-gray-700 dark:bg-gray-800">
                <div class="text-center">
                    <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-green-100 dark:bg-green-900/30 mb-3">
                        <span class="material-symbols-outlined text-green-600 dark:text-green-400">check_circle</span>
                    </div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Completadas</p>
                    <p class="mt-2 text-4xl font-bold text-green-600 dark:text-green-400">{{ $stats['completed_appointments'] }}</p>
                </div>
            </div>

            {{-- Citas Pendientes --}}
            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-lg dark:border-gray-700 dark:bg-gray-800">
                <div class="text-center">
                    <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-orange-100 dark:bg-orange-900/30 mb-3">
                        <span class="material-symbols-outlined text-orange-600 dark:text-orange-400">schedule</span>
                    </div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Próximas</p>
                    <p class="mt-2 text-4xl font-bold text-orange-600 dark:text-orange-400">{{ $stats['pending_appointments'] }}</p>
                </div>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-3">
            {{-- Próxima Cita --}}
            <div class="lg:col-span-2 rounded-2xl border-2 border-purple-500 bg-white p-6 shadow-lg dark:border-purple-400 dark:bg-gray-800">
                <div class="flex items-center gap-3 mb-4">
                    <span class="material-symbols-outlined text-purple-600 dark:text-purple-400 text-3xl">upcoming</span>
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">Próxima Cita</h2>
                </div>
                
                @if($nextAppointment)
                    <div class="space-y-4">
                        <div class="bg-gradient-to-r from-purple-50 to-pink-50 dark:from-purple-900/20 dark:to-pink-900/20 rounded-lg p-4">
                            <div class="flex items-start gap-4">
                                <div class="flex-shrink-0">
                                    <div class="w-16 h-16 rounded-full bg-gradient-to-br from-purple-600 to-pink-600 flex items-center justify-center text-white font-bold text-xl">
                                        {{ $nextAppointment->nutricionista->initials() }}
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <h3 class="font-semibold text-lg text-gray-900 dark:text-white">
                                        {{ $nextAppointment->nutricionista->name }}
                                    </h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Nutricionista</p>
                                    
                                    <div class="mt-3 space-y-2">
                                        <div class="flex items-center gap-2 text-gray-700 dark:text-gray-300">
                                            <span class="material-symbols-outlined text-sm">calendar_today</span>
                                            <span class="text-sm font-medium">
                                                {{ \Carbon\Carbon::parse($nextAppointment->start_time)->locale('es')->isoFormat('dddd, D [de] MMMM, YYYY') }}
                                            </span>
                                        </div>
                                        <div class="flex items-center gap-2 text-gray-700 dark:text-gray-300">
                                            <span class="material-symbols-outlined text-sm">schedule</span>
                                            <span class="text-sm font-medium">
                                                {{ \Carbon\Carbon::parse($nextAppointment->start_time)->format('h:i A') }}
                                            </span>
                                        </div>
                                        @if($nextAppointment->reason)
                                            <div class="flex items-start gap-2 text-gray-700 dark:text-gray-300">
                                                <span class="material-symbols-outlined text-sm">description</span>
                                                <span class="text-sm">{{ $nextAppointment->reason }}</span>
                                            </div>
                                        @endif
                                        <div class="flex items-center gap-2">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                @if($nextAppointment->appointmentState->name === 'confirmada') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400
                                                @elseif($nextAppointment->appointmentState->name === 'pendiente') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400
                                                @else bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-400
                                                @endif">
                                                {{ ucfirst($nextAppointment->appointmentState->name) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex gap-3">
                            <button class="flex-1 rounded-lg bg-purple-600 px-4 py-3 text-center font-semibold text-white transition hover:bg-purple-700 dark:bg-purple-500 dark:hover:bg-purple-600 flex items-center justify-center gap-2">
                                <span class="material-symbols-outlined">video_call</span>
                                Unirse a Videollamada
                            </button>
                            <button class="rounded-lg border-2 border-red-600 px-4 py-3 text-red-600 font-semibold transition hover:bg-red-50 dark:border-red-500 dark:text-red-500 dark:hover:bg-red-900/10 flex items-center gap-2">
                                <span class="material-symbols-outlined">cancel</span>
                                Cancelar
                            </button>
                        </div>
                    </div>
                @else
                    <div class="py-12 text-center">
                        <span class="material-symbols-outlined text-6xl text-gray-300 dark:text-gray-600">event_busy</span>
                        <p class="mt-4 text-gray-500 dark:text-gray-400">No tienes citas próximas</p>
                        <a href="{{ route('paciente.booking.show') }}" class="mt-4 inline-flex items-center gap-2 text-purple-600 dark:text-purple-400 hover:underline font-medium">
                            <span class="material-symbols-outlined">add</span>
                            Agendar una cita
                        </a>
                    </div>
                @endif
            </div>

            {{-- Nutricionistas Disponibles --}}
            <div id="agendar" class="rounded-2xl border border-gray-200 bg-white p-6 shadow-lg dark:border-gray-700 dark:bg-gray-800">
                <div class="flex items-center gap-3 mb-4">
                    <span class="material-symbols-outlined text-green-600 dark:text-green-400 text-3xl">group</span>
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">Nutricionistas</h2>
                </div>
                
                <div class="space-y-3">
                    @forelse($nutricionistas as $nutricionista)
                        <div class="flex items-center justify-between rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-700">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-green-600 to-emerald-600 flex items-center justify-center text-white font-bold">
                                    {{ $nutricionista->initials() }}
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900 dark:text-white">{{ $nutricionista->name }}</p>
                                    <p class="text-xs text-gray-600 dark:text-gray-400">{{ $nutricionista->email }}</p>
                                </div>
                            </div>
                            <button class="rounded-lg bg-green-600 px-3 py-2 text-sm font-medium text-white transition hover:bg-green-700 dark:bg-green-500 dark:hover:bg-green-600"
                                    onclick="window.location.href='{{ route('paciente.booking.schedule', $nutricionista) }}'">
                                Agendar
                            </button>
                        </div>
                    @empty
                        <p class="text-center text-sm text-gray-500 dark:text-gray-400 py-4">No hay nutricionistas disponibles</p>
                    @endforelse
                </div>

                <a href="{{ route('paciente.booking.show') }}" class="mt-4 block text-center text-sm text-purple-600 dark:text-purple-400 hover:underline font-medium">
                    Ver todos los nutricionistas →
                </a>
            </div>
        </div>

        {{-- Historial de Citas --}}
        <div class="mt-8 rounded-2xl border border-gray-200 bg-white p-6 shadow-lg dark:border-gray-700 dark:bg-gray-800">
            <div class="flex items-center gap-3 mb-4">
                <span class="material-symbols-outlined text-blue-600 dark:text-blue-400 text-3xl">history</span>
                <h2 class="text-xl font-bold text-gray-900 dark:text-white">Historial de Citas</h2>
            </div>
            
            @if($recentAppointments->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Fecha</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nutricionista</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tipo</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Estado</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($recentAppointments as $appointment)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-750">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                        {{ \Carbon\Carbon::parse($appointment->start_time)->locale('es')->isoFormat('D MMM YYYY, h:mm A') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                        {{ $appointment->nutricionista->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                                        {{ $appointment->appointment_type ? ucfirst(str_replace('_', ' ', $appointment->appointment_type)) : 'General' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if($appointment->appointmentState->name === 'completada') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400
                                            @elseif($appointment->appointmentState->name === 'confirmada') bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400
                                            @elseif($appointment->appointmentState->name === 'pendiente') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400
                                            @elseif($appointment->appointmentState->name === 'cancelada') bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400
                                            @else bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-400
                                            @endif">
                                            {{ ucfirst($appointment->appointmentState->name) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <button class="text-purple-600 hover:text-purple-900 dark:text-purple-400 dark:hover:text-purple-300 font-medium">
                                            Ver detalles
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="py-12 text-center">
                    <span class="material-symbols-outlined text-6xl text-gray-300 dark:text-gray-600">description</span>
                    <p class="mt-4 text-gray-500 dark:text-gray-400">No tienes historial de citas</p>
                </div>
            @endif
        </div>
    </main>

    @include('layouts.footer')
</body>
@endsection
