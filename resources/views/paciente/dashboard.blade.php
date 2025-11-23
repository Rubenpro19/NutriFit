@extends('layouts.app')

@section('title', 'Dashboard Paciente - NutriFit')

@section('content')

    <body
        class="min-h-screen bg-gradient-to-br from-purple-50 via-pink-50 to-rose-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
        @include('layouts.header')

        <main class="container mx-auto px-4 py-8">
            <!-- Header -->
            <div class="mb-8 rounded-2xl bg-gradient-to-r from-purple-600 to-pink-600 p-8 text-white shadow-lg">
                <h1 class="text-3xl font-bold mb-2">¡Hola, {{ auth()->user()->name }}!</h1>
                <p class="text-purple-100">Gestiona tus citas y encuentra al nutricionista perfecto para ti</p>
            </div>

            {{-- Acceso Rápido al Historial de Citas --}}
            <div
                class="mt-8 rounded-2xl border border-gray-200 bg-white p-8 shadow-lg dark:border-gray-700 dark:bg-gray-800">
                <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0">
                            <div
                                class="w-16 h-16 rounded-full bg-gradient-to-br from-purple-600 to-pink-600 flex items-center justify-center">
                                <span class="material-symbols-outlined text-3xl text-white">history</span>
                            </div>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
                                Historial de Citas
                            </h2>
                            <p class="text-gray-600 dark:text-gray-400 mb-1">
                                Visualiza todas tus citas pasadas y próximas
                            </p>
                            <p class="text-sm text-gray-500 dark:text-gray-500">
                                Revisa detalles de atenciones, diagnósticos y recomendaciones
                            </p>
                        </div>
                    </div>
                    <div class="flex-shrink-0">
                        <a href="{{ route('paciente.appointments.index') }}"
                            class="inline-flex items-center gap-2 bg-gradient-to-r from-purple-600 to-pink-600 text-white font-semibold py-3 px-6 rounded-xl hover:from-purple-700 hover:to-pink-700 transition shadow-lg">
                            <span class="material-symbols-outlined">calendar_view_month</span>
                            Ver Todas las Citas
                        </a>
                    </div>
                </div>

                @if ($recentAppointments->count() > 0)
                    <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="bg-purple-50 dark:bg-purple-900/20 rounded-xl p-4">
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Última cita</p>
                                <p class="text-lg font-semibold text-gray-900 dark:text-white">
                                    {{ \Carbon\Carbon::parse($recentAppointments->first()->start_time)->locale('es')->isoFormat('D [de] MMMM') }}
                                </p>
                            </div>
                            <div class="bg-pink-50 dark:bg-pink-900/20 rounded-xl p-4">
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Total registradas</p>
                                <p class="text-lg font-semibold text-gray-900 dark:text-white">
                                    {{ $recentAppointments->count() }}
                                    {{ $recentAppointments->count() === 1 ? 'cita' : 'citas' }}
                                </p>
                            </div>
                            <div class="bg-purple-50 dark:bg-purple-900/20 rounded-xl p-4">
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Atenciones completadas</p>
                                <p class="text-lg font-semibold text-gray-900 dark:text-white">
                                    {{ $stats['completadas'] }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            @if (session('success'))
                <div
                    class="mb-6 mt-8 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-800 dark:text-green-200 px-4 py-3 rounded-lg flex items-center gap-3">
                    <span class="material-symbols-outlined">check_circle</span>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div
                    class="mb-6 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-800 dark:text-red-200 px-4 py-3 rounded-lg flex items-center gap-3">
                    <span class="material-symbols-outlined">error</span>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <div class="grid gap-6 lg:grid-cols-3 mt-8">
                {{-- Próxima Cita --}}
                <div
                    class="lg:col-span-2 rounded-2xl border-2 border-purple-500 bg-white p-6 shadow-lg dark:border-purple-400 dark:bg-gray-800">
                    <h2 class="mb-4 text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                        <span class="material-symbols-outlined text-purple-600 dark:text-purple-400">upcoming</span>
                        Próxima Cita
                    </h2>

                    @if ($nextAppointment)
                        <div
                            class="bg-gradient-to-r from-purple-50 to-pink-50 dark:from-purple-900/20 dark:to-pink-900/20 rounded-lg p-6">
                            <div class="flex items-start gap-4 mb-4">
                                <div class="flex-shrink-0">
                                    <div
                                        class="w-16 h-16 rounded-full bg-gradient-to-br from-purple-600 to-pink-600 flex items-center justify-center text-white font-bold text-xl">
                                        {{ $nextAppointment->nutricionista->initials() }}
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <h3 class="font-semibold text-xl text-gray-900 dark:text-white">
                                        {{ $nextAppointment->nutricionista->name }}
                                    </h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Nutricionista</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-500">
                                        {{ $nextAppointment->nutricionista->email }}</p>
                                </div>
                            </div>

                            <div class="grid md:grid-cols-2 gap-4 mb-4">
                                <div class="flex items-center gap-3 bg-white dark:bg-gray-700 rounded-lg p-3">
                                    <span
                                        class="material-symbols-outlined text-purple-600 dark:text-purple-400">calendar_today</span>
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Fecha</p>
                                        <p class="font-semibold text-gray-900 dark:text-white">
                                            {{ \Carbon\Carbon::parse($nextAppointment->start_time)->locale('es')->isoFormat('D [de] MMMM, YYYY') }}
                                        </p>
                                    </div>
                                </div>

                                <div class="flex items-center gap-3 bg-white dark:bg-gray-700 rounded-lg p-3">
                                    <span
                                        class="material-symbols-outlined text-purple-600 dark:text-purple-400">schedule</span>
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Hora</p>
                                        <p class="font-semibold text-gray-900 dark:text-white">
                                            {{ \Carbon\Carbon::parse($nextAppointment->start_time)->format('h:i A') }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            @if ($nextAppointment->reason)
                                <div class="bg-white dark:bg-gray-700 rounded-lg p-3 mb-4">
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Motivo</p>
                                    <p class="text-gray-900 dark:text-white">{{ $nextAppointment->reason }}</p>
                                </div>
                            @endif

                            <div class="flex gap-3">
                                <a href="{{ route('paciente.appointments.show', $nextAppointment) }}"
                                    class="flex-1 rounded-lg bg-gradient-to-r from-purple-600 to-pink-600 px-4 py-3 text-center font-semibold text-white transition hover:from-purple-700 hover:to-pink-700 flex items-center justify-center gap-2">
                                    <span class="material-symbols-outlined">visibility</span>
                                    Ver Detalles
                                </a>
                                <form method="POST" action="{{ route('paciente.appointments.cancel', $nextAppointment) }}"
                                    class="flex-1" onsubmit="return confirm('¿Estás seguro de cancelar esta cita?')">
                                    @csrf
                                    <button type="submit"
                                        class="w-full rounded-lg bg-red-600 px-4 py-3 text-center font-semibold text-white transition hover:bg-red-700 flex items-center justify-center gap-2">
                                        <span class="material-symbols-outlined">cancel</span>
                                        Cancelar Cita
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <div class="py-12 text-center">
                            <span
                                class="material-symbols-outlined text-6xl text-gray-300 dark:text-gray-600">event_busy</span>
                            <p class="mt-4 text-gray-500 dark:text-gray-400 mb-6">No tienes citas próximas</p>
                            <a href="{{ route('paciente.booking.index') }}"
                                class="inline-flex items-center gap-2 rounded-lg bg-gradient-to-r from-purple-600 to-pink-600 px-6 py-3 font-semibold text-white transition hover:from-purple-700 hover:to-pink-700">
                                <span class="material-symbols-outlined">add_circle</span>
                                Agendar Nueva Cita
                            </a>
                        </div>
                    @endif
                </div>

                {{-- Nutricionistas Disponibles --}}
                <div
                    class="rounded-2xl border border-gray-200 bg-white p-6 shadow-lg dark:border-gray-700 dark:bg-gray-800">
                    <h2 class="mb-4 text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                        <span class="material-symbols-outlined text-purple-600 dark:text-purple-400">group</span>
                        Nutricionistas
                    </h2>

                    @if ($nutricionistas->count() > 0)
                        <div class="space-y-3">
                            @foreach ($nutricionistas->take(5) as $nutricionista)
                                <div
                                    class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-750 transition">
                                    <div
                                        class="w-12 h-12 rounded-full bg-gradient-to-br from-purple-600 to-pink-600 flex items-center justify-center text-white font-bold">
                                        {{ $nutricionista->initials() }}
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="font-semibold text-gray-900 dark:text-white truncate">
                                            {{ $nutricionista->name }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $nutricionista->schedules_count }} horarios</p>
                                    </div>
                                    <a href="{{ route('paciente.booking.schedule', $nutricionista) }}"
                                        class="flex-shrink-0 rounded-lg bg-purple-600 px-3 py-2 text-sm font-medium text-white transition hover:bg-purple-700">
                                        Agendar
                                    </a>
                                </div>
                            @endforeach
                        </div>

                        <a href="{{ route('paciente.booking.index') }}"
                            class="mt-4 block text-center text-sm text-purple-600 dark:text-purple-400 hover:underline">
                            Ver todos los nutricionistas →
                        </a>
                    @else
                        <p class="text-center text-gray-500 dark:text-gray-400 py-8">No hay nutricionistas disponibles</p>
                    @endif
                </div>
            </div>

            <!-- Estadísticas -->
            <div class="mt-8 grid gap-6 md:grid-cols-3">
                <div
                    class="rounded-2xl border border-gray-200 bg-white p-6 shadow-lg dark:border-gray-700 dark:bg-gray-800 transition hover:shadow-xl">
                    <div class="text-center">
                        <div
                            class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-purple-100 dark:bg-purple-900/30 mb-4">
                            <span
                                class="material-symbols-outlined text-3xl text-purple-600 dark:text-purple-400">event</span>
                        </div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total de Citas</p>
                        <p class="mt-2 text-4xl font-bold text-purple-600 dark:text-purple-400">{{ $stats['total'] }}</p>
                    </div>
                </div>

                <div
                    class="rounded-2xl border border-gray-200 bg-white p-6 shadow-lg dark:border-gray-700 dark:bg-gray-800 transition hover:shadow-xl">
                    <div class="text-center">
                        <div
                            class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-green-100 dark:bg-green-900/30 mb-4">
                            <span
                                class="material-symbols-outlined text-3xl text-green-600 dark:text-green-400">check_circle</span>
                        </div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Completadas</p>
                        <p class="mt-2 text-4xl font-bold text-green-600 dark:text-green-400">{{ $stats['completadas'] }}
                        </p>
                    </div>
                </div>

                <div
                    class="rounded-2xl border border-gray-200 bg-white p-6 shadow-lg dark:border-gray-700 dark:bg-gray-800 transition hover:shadow-xl">
                    <div class="text-center">
                        <div
                            class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-yellow-100 dark:bg-yellow-900/30 mb-4">
                            <span
                                class="material-symbols-outlined text-3xl text-yellow-600 dark:text-yellow-400">schedule</span>
                        </div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Pendientes</p>
                        <p class="mt-2 text-4xl font-bold text-yellow-600 dark:text-yellow-400">{{ $stats['pendientes'] }}
                        </p>
                    </div>
                </div>
            </div>
        </main>

        @include('layouts.footer')
    </body>
@endsection
