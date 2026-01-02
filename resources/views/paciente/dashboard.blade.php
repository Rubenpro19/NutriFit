@extends('layouts.app')

@section('title', 'Dashboard Paciente - NutriFit')

@section('content')

    <body
        class="min-h-screen bg-gradient-to-br from-green-50 via-emerald-50 to-rose-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
        @include('layouts.header')

        <main class="container mx-auto px-4 py-8">
            <div class="max-w-7xl mx-auto">
            <!-- Header -->
            <div class="mb-8 rounded-2xl bg-gradient-to-r from-green-600 to-emerald-600 p-8 text-white shadow-lg">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h1 class="text-3xl font-bold mb-2">¡Hola, {{ auth()->user()->name }}!</h1>
                        <p class="text-green-100">Gestiona tus citas y encuentra al nutricionista perfecto para ti</p>
                    </div>
                    <div>
                        <a href="{{ route('paciente.profile') }}" 
                           class="inline-flex items-center gap-2 bg-white/20 backdrop-blur-sm hover:bg-white/30 text-white font-semibold py-3 px-6 rounded-xl transition border border-white/30">
                            <span class="material-symbols-outlined">person</span>
                            Mi Perfil
                        </a>
                    </div>
                </div>
            </div>

            {{-- Acceso Rápido al Historial de Citas --}}
            <div
                class="mt-8 rounded-2xl border border-gray-200 bg-white p-8 shadow-lg dark:border-gray-700 dark:bg-gray-800">
                <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0">
                            <div
                                class="w-16 h-16 rounded-full bg-gradient-to-br from-green-600 to-emerald-600 flex items-center justify-center">
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
                            class="inline-flex items-center gap-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white font-semibold py-3 px-6 rounded-xl hover:from-green-700 hover:to-emerald-700 transition shadow-lg">
                            <span class="material-symbols-outlined">calendar_view_month</span>
                            Ver Todas las Citas
                        </a>
                    </div>
                </div>

                @if ($recentAppointments->count() > 0)
                    <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="bg-green-50 dark:bg-green-900/20 rounded-xl p-4">
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Última cita</p>
                                <p class="text-lg font-semibold text-gray-900 dark:text-white">
                                    {{ \Carbon\Carbon::parse($recentAppointments->first()->start_time)->locale('es')->isoFormat('D [de] MMMM') }}
                                </p>
                            </div>
                            <div class="bg-emerald-50 dark:bg-emerald-900/20 rounded-xl p-4">
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Total registradas</p>
                                <p class="text-lg font-semibold text-gray-900 dark:text-white">
                                    {{ $recentAppointments->count() }}
                                    {{ $recentAppointments->count() === 1 ? 'cita' : 'citas' }}
                                </p>
                            </div>
                            <div class="bg-green-50 dark:bg-green-900/20 rounded-xl p-4">
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
                    class="lg:col-span-2 rounded-2xl border-2 border-green-500 bg-white p-6 shadow-lg dark:border-green-400 dark:bg-gray-800">
                    <h2 class="mb-4 text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                        <span class="material-symbols-outlined text-green-600 dark:text-green-400">upcoming</span>
                        Próxima Cita
                    </h2>

                    @if ($nextAppointment)
                        <div
                            class="bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 rounded-lg p-6">
                            <div class="flex items-start gap-4 mb-4">
                                <div class="flex-shrink-0">
                                    <div
                                        class="w-16 h-16 rounded-full bg-gradient-to-br from-green-600 to-emerald-600 flex items-center justify-center text-white font-bold text-xl">
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
                                        class="material-symbols-outlined text-green-600 dark:text-green-400">calendar_today</span>
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Fecha</p>
                                        <p class="font-semibold text-gray-900 dark:text-white">
                                            {{ \Carbon\Carbon::parse($nextAppointment->start_time)->locale('es')->isoFormat('D [de] MMMM, YYYY') }}
                                        </p>
                                    </div>
                                </div>

                                <div class="flex items-center gap-3 bg-white dark:bg-gray-700 rounded-lg p-3">
                                    <span
                                        class="material-symbols-outlined text-green-600 dark:text-green-400">schedule</span>
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
                                    class="flex-1 rounded-lg bg-gradient-to-r from-green-600 to-emerald-600 px-4 py-3 text-center font-semibold text-white transition hover:from-green-700 hover:to-emerald-700 flex items-center justify-center gap-2">
                                    <span class="material-symbols-outlined">visibility</span>
                                    Ver Detalles
                                </a>
                                <div x-data="{ showModal: false }" class="flex-1">
                                    <button type="button" @click="showModal = true"
                                        class="w-full rounded-lg bg-red-600 px-4 py-3 text-center font-semibold text-white transition hover:bg-red-700 flex items-center justify-center gap-2">
                                        <span class="material-symbols-outlined">cancel</span>
                                        Cancelar Cita
                                    </button>

                                    <!-- Modal de Confirmación -->
                                    <div x-show="showModal" x-cloak
                                        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/30 backdrop-blur-sm"
                                        @click.self="showModal = false">
                                        <div @click.away="showModal = false"
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
                                                    class="flex-1 px-4 py-3 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 font-semibold rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                                                    No, mantener
                                                </button>
                                                <form method="POST" action="{{ route('paciente.appointments.cancel', $nextAppointment) }}" class="flex-1">
                                                    @csrf
                                                    <button type="submit"
                                                        class="w-full px-4 py-3 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition">
                                                        Sí, cancelar
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="py-12 text-center">
                            <span
                                class="material-symbols-outlined text-6xl text-gray-300 dark:text-gray-600">event_busy</span>
                            <p class="mt-4 text-gray-500 dark:text-gray-400 mb-6">No tienes citas próximas</p>
                            <a href="{{ route('paciente.booking.index') }}"
                                class="inline-flex items-center gap-2 rounded-lg bg-gradient-to-r from-green-600 to-emerald-600 px-6 py-3 font-semibold text-white transition hover:from-green-700 hover:to-emerald-700">
                                <span class="material-symbols-outlined">add_circle</span>
                                Agendar Nueva Cita
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
