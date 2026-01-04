@extends('layouts.app')

@section('title', 'Mis Citas')

@section('content')
<body class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-green-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 flex flex-col">
    @include('layouts.header')

    <main class="flex-grow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
            {{-- Breadcrumb --}}
            <nav class="mb-6 flex items-center gap-2 text-sm">
                <a href="{{ route('nutricionista.dashboard') }}" class="text-green-600 dark:text-green-400 hover:underline transition-colors">Inicio</a>
                <span class="material-symbols-outlined text-gray-400 text-sm">chevron_right</span>
                <span class="text-gray-700 dark:text-gray-300 font-medium">Mis Citas</span>
            </nav>

            {{-- Header --}}
            <div class="mb-6 rounded-xl bg-white p-4 sm:p-6 shadow-sm dark:bg-gray-800">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-gradient-to-br from-green-600 to-emerald-600 text-white shadow-lg">
                            <span class="material-symbols-outlined text-2xl">calendar_month</span>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Mis Citas</h1>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Gestiona todas tus citas médicas</p>
                        </div>
                    </div>
                    <a href="{{ route('nutricionista.appointments.create') }}" 
                       class="flex items-center justify-center gap-2 rounded-lg bg-gradient-to-r from-green-600 to-emerald-600 px-6 py-3 text-white font-semibold transition-all duration-200 hover:shadow-lg hover:scale-105 active:scale-95 whitespace-nowrap">
                        <span class="material-symbols-outlined">add_circle</span>
                        Asignar Nueva Cita
                    </a>
                </div>
            </div>

            {{-- Stats Cards --}}
            <div class="grid grid-cols-2 gap-4 mb-6 lg:grid-cols-5">
                {{-- Total --}}
                <div class="rounded-xl bg-gradient-to-br from-green-600 to-emerald-600 p-4 text-white shadow-lg transform transition-all duration-200 hover:scale-105">
                    <div class="flex items-center justify-between mb-2">
                        <span class="material-symbols-outlined text-3xl opacity-80">calendar_month</span>
                        <span class="text-3xl font-bold">{{ $stats['total'] }}</span>
                    </div>
                    <p class="text-sm font-medium opacity-90">Total</p>
                </div>

                {{-- Pendientes --}}
                <div class="rounded-xl bg-gradient-to-br from-green-500 to-emerald-500 p-4 text-white shadow-lg transform transition-all duration-200 hover:scale-105">
                    <div class="flex items-center justify-between mb-2">
                        <span class="material-symbols-outlined text-3xl opacity-80">schedule</span>
                        <span class="text-3xl font-bold">{{ $stats['pendientes'] }}</span>
                    </div>
                    <p class="text-sm font-medium opacity-90">Pendientes</p>
                </div>

                {{-- Completadas --}}
                <div class="rounded-xl bg-gradient-to-br from-emerald-600 to-green-600 p-4 text-white shadow-lg transform transition-all duration-200 hover:scale-105">
                    <div class="flex items-center justify-between mb-2">
                        <span class="material-symbols-outlined text-3xl opacity-80">check_circle</span>
                        <span class="text-3xl font-bold">{{ $stats['completadas'] }}</span>
                    </div>
                    <p class="text-sm font-medium opacity-90">Completadas</p>
                </div>

                {{-- Canceladas --}}
                <div class="rounded-xl bg-gradient-to-br from-green-700 to-emerald-700 p-4 text-white shadow-lg transform transition-all duration-200 hover:scale-105">
                    <div class="flex items-center justify-between mb-2">
                        <span class="material-symbols-outlined text-3xl opacity-80">cancel</span>
                        <span class="text-3xl font-bold">{{ $stats['canceladas'] }}</span>
                    </div>
                    <p class="text-sm font-medium opacity-90">Canceladas</p>
                </div>

                {{-- Vencidas --}}
                <div class="rounded-xl bg-gradient-to-br from-emerald-700 to-green-700 p-4 text-white shadow-lg transform transition-all duration-200 hover:scale-105">
                    <div class="flex items-center justify-between mb-2">
                        <span class="material-symbols-outlined text-3xl opacity-80">error</span>
                        <span class="text-3xl font-bold">{{ $stats['vencidas'] }}</span>
                    </div>
                    <p class="text-sm font-medium opacity-90">Vencidas</p>
                </div>
            </div>

            {{-- Filtros --}}
            <div class="mb-6 rounded-xl bg-white p-4 sm:p-6 shadow-sm dark:bg-gray-800">
                <form method="GET" action="{{ route('nutricionista.appointments.index') }}" class="space-y-4">
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-4">
                        {{-- Búsqueda por paciente --}}
                        <div>
                            <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Buscar Paciente
                            </label>
                            <div class="relative">
                                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                                    search
                                </span>
                                <input type="text" 
                                       name="search" 
                                       id="search"
                                       value="{{ request('search') }}"
                                       placeholder="Nombre del paciente..."
                                       class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            </div>
                        </div>

                        {{-- Filtro por estado --}}
                        <div>
                            <label for="estado" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Estado
                            </label>
                            <div class="relative">
                                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                                    filter_list
                                </span>
                                <select name="estado" 
                                        id="estado"
                                        class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-green-500 focus:border-green-500 appearance-none">
                                    <option value="todos" {{ request('estado') == 'todos' ? 'selected' : '' }}>Todos</option>
                                    <option value="pendiente" {{ request('estado') == 'pendiente' ? 'selected' : '' }}>Pendientes</option>
                                    <option value="completada" {{ request('estado') == 'completada' ? 'selected' : '' }}>Completadas</option>
                                    <option value="cancelada" {{ request('estado') == 'cancelada' ? 'selected' : '' }}>Canceladas</option>
                                    <option value="vencida" {{ request('estado') == 'vencida' ? 'selected' : '' }}>Vencidas</option>
                                </select>
                            </div>
                        </div>

                        {{-- Fecha desde --}}
                        <div>
                            <label for="fecha_desde" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Desde
                            </label>
                            <div class="relative">
                                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                                    calendar_today
                                </span>
                                <input type="date" 
                                       name="fecha_desde" 
                                       id="fecha_desde"
                                       value="{{ request('fecha_desde') }}"
                                       class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            </div>
                        </div>

                        {{-- Fecha hasta --}}
                        <div>
                            <label for="fecha_hasta" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Hasta
                            </label>
                            <div class="relative">
                                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                                    calendar_today
                                </span>
                                <input type="date" 
                                       name="fecha_hasta" 
                                       id="fecha_hasta"
                                       value="{{ request('fecha_hasta') }}"
                                       class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            </div>
                        </div>
                    </div>

                    {{-- Botones --}}
                    <div class="flex flex-wrap gap-3">
                        <button type="submit"
                                class="flex items-center gap-2 rounded-lg bg-gradient-to-r from-green-600 to-emerald-600 px-6 py-2.5 text-white font-semibold transition-all duration-200 hover:shadow-lg hover:scale-105 active:scale-95">
                            <span class="material-symbols-outlined text-xl">search</span>
                            Filtrar
                        </button>
                        <a href="{{ route('nutricionista.appointments.index') }}"
                           class="flex items-center gap-2 rounded-lg bg-gray-200 dark:bg-gray-700 px-6 py-2.5 text-gray-700 dark:text-gray-300 font-semibold transition-all duration-200 hover:bg-gray-300 dark:hover:bg-gray-600">
                            <span class="material-symbols-outlined text-xl">refresh</span>
                            Limpiar
                        </a>
                    </div>
                </form>
            </div>

            {{-- Tabla de Citas --}}
            <div class="rounded-xl bg-white shadow-sm dark:bg-gray-800 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-900">
                            <tr>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">
                                    Paciente
                                </th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">
                                    Fecha y Hora
                                </th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">
                                    Estado
                                </th>
                                <th scope="col" class="px-6 py-4 text-center text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">
                                    Acciones
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                            @forelse($appointments as $appointment)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                    {{-- Paciente --}}
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-3">
                                            <div class="h-10 w-10 flex-shrink-0">
                                                @if($appointment->paciente->personalData?->profile_photo)
                                                    <img class="h-10 w-10 rounded-full object-cover ring-2 ring-green-500" 
                                                         src="{{ asset('storage/' . $appointment->paciente->personalData->profile_photo) }}" 
                                                         alt="{{ $appointment->paciente->name }}">
                                                @else
                                                    <div class="h-10 w-10 rounded-full bg-gradient-to-br from-green-600 to-emerald-600 flex items-center justify-center text-white font-bold ring-2 ring-green-500">
                                                        {{ strtoupper(substr($appointment->paciente->name, 0, 1)) }}
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                                    {{ $appointment->paciente->name }}
                                                </p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400 truncate">
                                                    {{ $appointment->paciente->email }}
                                                </p>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Fecha y Hora --}}
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm">
                                            <p class="font-medium text-gray-900 dark:text-white">
                                                {{ \Carbon\Carbon::parse($appointment->start_time)->locale('es')->isoFormat('D [de] MMMM, YYYY') }}
                                            </p>
                                            <p class="text-gray-500 dark:text-gray-400">
                                                {{ \Carbon\Carbon::parse($appointment->start_time)->format('H:i') }} - 
                                                {{ \Carbon\Carbon::parse($appointment->end_time)->format('H:i') }}
                                            </p>
                                        </div>
                                    </td>

                                    {{-- Estado --}}
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $estadoClasses = [
                                                'pendiente' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
                                                'completada' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                                                'cancelada' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
                                                'vencida' => 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200',
                                            ];
                                            $estadoIcons = [
                                                'pendiente' => 'schedule',
                                                'completada' => 'check_circle',
                                                'cancelada' => 'cancel',
                                                'vencida' => 'error',
                                            ];
                                        @endphp
                                        <span class="inline-flex items-center gap-1 rounded-full px-3 py-1 text-xs font-semibold {{ $estadoClasses[$appointment->appointmentState->name] ?? 'bg-gray-100 text-gray-800' }}">
                                            <span class="material-symbols-outlined text-sm">{{ $estadoIcons[$appointment->appointmentState->name] ?? 'help' }}</span>
                                            {{ ucfirst($appointment->appointmentState->name) }}
                                        </span>
                                    </td>

                                    {{-- Acciones --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            {{-- Ver detalles --}}
                                            <a href="{{ route('nutricionista.appointments.show', $appointment->id) }}"
                                               class="flex items-center justify-center h-9 w-9 rounded-lg bg-blue-100 text-blue-600 dark:bg-blue-900 dark:text-blue-300 hover:bg-blue-200 dark:hover:bg-blue-800 transition-colors"
                                               title="Ver detalles">
                                                <span class="material-symbols-outlined text-xl">visibility</span>
                                            </a>

                                            {{-- Atender (solo si está pendiente y es hoy o ya pasó) --}}
                                            @if($appointment->appointmentState->name === 'pendiente' && 
                                                \Carbon\Carbon::parse($appointment->start_time)->lte(now()))
                                                <a href="{{ route('nutricionista.attentions.create', $appointment->id) }}"
                                                   class="flex items-center justify-center h-9 w-9 rounded-lg bg-green-100 text-green-600 dark:bg-green-900 dark:text-green-300 hover:bg-green-200 dark:hover:bg-green-800 transition-colors"
                                                   title="Atender paciente">
                                                    <span class="material-symbols-outlined text-xl">medical_services</span>
                                                </a>
                                            @endif

                                            {{-- Reagendar (solo si está pendiente) --}}
                                            @if($appointment->appointmentState->name === 'pendiente')
                                                <a href="{{ route('nutricionista.appointments.reschedule', $appointment->id) }}"
                                                   class="flex items-center justify-center h-9 w-9 rounded-lg bg-yellow-100 text-yellow-600 dark:bg-yellow-900 dark:text-yellow-300 hover:bg-yellow-200 dark:hover:bg-yellow-800 transition-colors"
                                                   title="Reagendar">
                                                    <span class="material-symbols-outlined text-xl">event_repeat</span>
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center justify-center gap-3">
                                            <span class="material-symbols-outlined text-6xl text-gray-300 dark:text-gray-600">
                                                event_busy
                                            </span>
                                            <p class="text-lg font-medium text-gray-500 dark:text-gray-400">
                                                No se encontraron citas
                                            </p>
                                            <p class="text-sm text-gray-400 dark:text-gray-500">
                                                Intenta ajustar los filtros o crear una nueva cita
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Paginación --}}
                @if($appointments->hasPages())
                    <div class="border-t border-gray-200 dark:border-gray-700 px-6 py-4 bg-gray-50 dark:bg-gray-900">
                        {{ $appointments->links() }}
                    </div>
                @endif
            </div>
        </div>
    </main>
</body>
@endsection
