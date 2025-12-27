@extends('layouts.app')

@section('title', 'Mis Citas - NutriFit')

@section('content')
<body class="min-h-screen bg-gradient-to-br from-green-50 via-emerald-50 to-rose-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 flex flex-col">
    @include('layouts.header')

    <main class="container mx-auto px-4 py-8 flex-grow">
        <div class="max-w-7xl mx-auto">
        <!-- Breadcrumb -->
        <nav class="mb-6 flex items-center gap-2 text-sm">
            <a href="{{ route('paciente.dashboard') }}" class="text-green-600 dark:text-green-400 hover:underline">Dashboard</a>
            <span class="material-symbols-outlined text-gray-400 text-sm">chevron_right</span>
            <span class="text-gray-600 dark:text-gray-400">Mis Citas</span>
        </nav>

        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Historial de Citas</h1>
            <p class="text-gray-600 dark:text-gray-400">Visualiza y gestiona todas tus citas médicas</p>
        </div>

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

        <!-- Filtros -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 mb-6 border border-gray-200 dark:border-gray-700">
            <form method="GET" action="{{ route('paciente.appointments.index') }}" class="flex flex-wrap gap-4 items-end">
                <div class="flex-1 min-w-[200px]">
                    <label for="estado" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Filtrar por estado
                    </label>
                    <select name="estado" id="estado" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <option value="">Todas las citas</option>
                        <option value="pendiente" {{ request('estado') === 'pendiente' ? 'selected' : '' }}>Pendientes</option>
                        <option value="completada" {{ request('estado') === 'completada' ? 'selected' : '' }}>Completadas</option>
                        <option value="cancelada" {{ request('estado') === 'cancelada' ? 'selected' : '' }}>Canceladas</option>
                        <option value="vencida" {{ request('estado') === 'vencida' ? 'selected' : '' }}>Vencidas</option>
                    </select>
                </div>

                <div class="flex-1 min-w-[200px]">
                    <label for="nutricionista" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Filtrar por nutricionista
                    </label>
                    <select name="nutricionista" id="nutricionista" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <option value="">Todos los nutricionistas</option>
                        @foreach($nutricionistas as $nutricionista)
                            <option value="{{ $nutricionista->id }}" {{ request('nutricionista') == $nutricionista->id ? 'selected' : '' }}>
                                {{ $nutricionista->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex-1 min-w-[200px]">
                    <label for="fecha_desde" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Fecha desde
                    </label>
                    <input type="date" name="fecha_desde" id="fecha_desde" value="{{ request('fecha_desde') }}" 
                           class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-green-500 focus:border-transparent">
                </div>

                <div class="flex-1 min-w-[200px]">
                    <label for="fecha_hasta" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Fecha hasta
                    </label>
                    <input type="date" name="fecha_hasta" id="fecha_hasta" value="{{ request('fecha_hasta') }}" 
                           class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-green-500 focus:border-transparent">
                </div>

                <div class="flex gap-2">
                    <button type="submit" class="flex items-center gap-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white font-semibold py-2 px-6 rounded-lg hover:from-green-700 hover:to-emerald-700 transition shadow-lg">
                        <span class="material-symbols-outlined">filter_alt</span>
                        Filtrar
                    </button>
                    <a href="{{ route('paciente.appointments.index') }}" class="flex items-center gap-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 font-semibold py-2 px-6 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                        <span class="material-symbols-outlined">refresh</span>
                        Limpiar
                    </a>
                </div>
            </form>
        </div>

        <!-- Lista de Citas -->
        @if($appointments->count() > 0)
            <div class="grid gap-6 mb-8">
                @foreach($appointments as $appointment)
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden border border-gray-200 dark:border-gray-700 hover:shadow-xl transition">
                        <div class="p-6">
                            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                                <!-- Info del Nutricionista -->
                                <div class="flex items-start gap-4 flex-1">
                                    <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-500 rounded-full flex items-center justify-center text-white font-bold text-xl flex-shrink-0">
                                        {{ substr($appointment->nutricionista->name, 0, 1) }}
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">
                                            {{ $appointment->nutricionista->name }}
                                        </h3>
                                        <div class="flex flex-wrap gap-4 text-sm text-gray-600 dark:text-gray-400">
                                            <div class="flex items-center gap-1">
                                                <span class="material-symbols-outlined text-sm">calendar_month</span>
                                                <span>{{ $appointment->start_time->format('d/m/Y') }}</span>
                                            </div>
                                            <div class="flex items-center gap-1">
                                                <span class="material-symbols-outlined text-sm">schedule</span>
                                                <span>{{ $appointment->start_time->format('h:i A') }} - {{ $appointment->end_time->format('h:i A') }}</span>
                                            </div>
                                            <div class="flex items-center gap-1">
                                                <span class="material-symbols-outlined text-sm">category</span>
                                                <span>{{ $appointment->appointment_type === 'primera_vez' ? 'Primera vez' : 'Seguimiento' }}</span>
                                            </div>
                                        </div>
                                        @if($appointment->reason)
                                            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                                                <span class="font-medium">Motivo:</span> {{ Str::limit($appointment->reason, 100) }}
                                            </p>
                                        @endif
                                    </div>
                                </div>

                                <!-- Estado y Acciones -->
                                <div class="flex flex-col items-end gap-3">
                                    @php
                                        $statusClasses = [
                                            'pendiente' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400',
                                            'completada' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
                                            'cancelada' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400',
                                            'vencida' => 'bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-400',
                                        ];
                                        $statusIcons = [
                                            'pendiente' => 'pending',
                                            'completada' => 'check_circle',
                                            'cancelada' => 'cancel',
                                            'vencida' => 'event_busy',
                                        ];
                                    @endphp
                                    <span class="flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold {{ $statusClasses[$appointment->appointmentState->name] ?? 'bg-gray-100 text-gray-800' }}">
                                        <span class="material-symbols-outlined text-sm">{{ $statusIcons[$appointment->appointmentState->name] ?? 'help' }}</span>
                                        {{ ucfirst($appointment->appointmentState->name) }}
                                    </span>

                                    <div class="flex gap-2">
                                        @if($appointment->appointmentState->name === 'completada')
                                            <a href="{{ route('paciente.appointments.show', $appointment) }}" 
                                               class="flex items-center gap-1 bg-gradient-to-r from-green-600 to-emerald-600 text-white font-semibold py-2 px-4 rounded-lg hover:from-green-700 hover:to-emerald-700 transition text-sm">
                                                <span class="material-symbols-outlined text-sm">visibility</span>
                                                Ver Detalle
                                            </a>
                                        @elseif($appointment->appointmentState->name === 'pendiente')
                                            <a href="{{ route('paciente.appointments.show', $appointment) }}" 
                                               class="flex items-center gap-1 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 font-semibold py-2 px-4 rounded-lg hover:bg-green-200 dark:hover:bg-green-900/50 transition text-sm">
                                                <span class="material-symbols-outlined text-sm">info</span>
                                                Ver Info
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Paginación -->
            <div class="mt-8">
                {{ $appointments->links() }}
            </div>
        @else
            <!-- Estado Vacío -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-12 text-center border border-gray-200 dark:border-gray-700">
                <span class="material-symbols-outlined text-8xl text-gray-300 dark:text-gray-600 mb-4">calendar_month</span>
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">No hay citas</h3>
                <p class="text-gray-600 dark:text-gray-400 mb-6">
                    @if(request('estado'))
                        No se encontraron citas con el filtro seleccionado.
                    @else
                        Aún no has agendado ninguna cita.
                    @endif
                </p>
                <a href="{{ route('paciente.booking.index') }}" 
                   class="inline-flex items-center gap-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white font-semibold py-3 px-6 rounded-lg hover:from-green-700 hover:to-emerald-700 transition shadow-lg">
                    <span class="material-symbols-outlined">add</span>
                    Agendar Nueva Cita
                </a>
            </div>
        @endif
        </div>
    </main>

    @include('layouts.footer')
</body>
@endsection
