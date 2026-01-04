@extends('layouts.app')

@section('title', 'Agendar Cita - NutriFit')

@section('content')
<body class="min-h-screen bg-gradient-to-br from-green-50 via-emerald-50 to-rose-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 flex flex-col">
    @include('layouts.header')

    <main class="flex-grow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
        <!-- Breadcrumb -->
        <nav class="mb-6 flex items-center gap-2 text-sm">
            <a href="{{ route('paciente.dashboard') }}" class="text-green-600 dark:text-green-400 hover:underline transition-colors">Inicio</a>
            <span class="material-symbols-outlined text-gray-400 text-sm">chevron_right</span>
            <span class="text-gray-700 dark:text-gray-300 font-medium">Agendar Cita</span>
        </nav>

        <!-- Header -->
        <div class="mb-8 bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-4 sm:p-6 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between gap-3">
                <div class="flex items-center gap-3 flex-1 min-w-0">
                    <a href="{{ route('paciente.dashboard') }}" class="text-gray-700 dark:text-gray-300 hover:text-green-600 dark:hover:text-green-400 hover:scale-110 transition-all">
                        <span class="material-symbols-outlined text-2xl">arrow_back</span>
                    </a>
                    <div class="min-w-0 flex-1">
                        <h1 class="text-2xl sm:text-3xl font-bold bg-gradient-to-r from-green-600 to-emerald-600 dark:from-green-400 dark:to-emerald-400 bg-clip-text text-transparent">
                            Selecciona tu Nutricionista
                        </h1>
                        <p class="text-sm sm:text-base text-gray-700 dark:text-gray-300 mt-1 truncate">
                            Elige al nutricionista con el que deseas agendar tu cita
                        </p>
                    </div>
                </div>
                <div class="flex-shrink-0 hidden sm:flex items-center">
                    <span class="material-symbols-outlined text-3xl sm:text-4xl text-green-600 dark:text-green-400">calendar_add_on</span>
                </div>
            </div>
        </div>

        @if(session('error'))
            <div class="mb-6 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-800 dark:text-red-200 px-4 py-3 rounded-xl flex items-center gap-3">
            </div>
            @endif

            @if($nutricionistas->count() > 0)
                <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                    @foreach($nutricionistas as $nutricionista)
                        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-lg dark:border-gray-700 dark:bg-gray-800 transition hover:shadow-xl hover:border-green-300 dark:hover:border-green-600">
                            <div class="text-center mb-6">
                                <div class="w-24 h-24 mx-auto rounded-full bg-gradient-to-br from-green-600 to-emerald-600 flex items-center justify-center text-white font-bold text-3xl mb-4 shadow-lg">
                                    {{ $nutricionista->initials() }}
                                </div>
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">
                                    {{ $nutricionista->name }}
                                </h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">
                                    {{ $nutricionista->email }}
                                </p>
                                
                                @if($nutricionista->schedules_count > 0)
                                    <div class="mt-4 inline-flex items-center gap-2 px-3 py-1.5 bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400 rounded-full text-sm font-medium">
                                        <span class="material-symbols-outlined text-lg">check_circle</span>
                                        {{ $nutricionista->schedules_count }} horarios disponibles
                                    </div>
                                @else
                                    <div class="mt-4 inline-flex items-center gap-2 px-3 py-1.5 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 rounded-full text-sm font-medium">
                                        <span class="material-symbols-outlined text-lg">schedule</span>
                                        Sin horarios configurados
                                    </div>
                                @endif
                            </div>

                            @if($nutricionista->schedules_count > 0)
                                <a href="{{ route('paciente.booking.schedule', $nutricionista) }}" 
                                   class="block w-full rounded-lg bg-gradient-to-r from-green-600 to-emerald-600 px-4 py-3 text-center font-semibold text-white transition hover:from-green-700 hover:to-emerald-700 flex items-center justify-center gap-2">
                                    <span class="material-symbols-outlined">calendar_month</span>
                                    Ver Horarios Disponibles
                                </a>
                            @else
                                <button disabled 
                                   class="block w-full rounded-lg bg-gray-300 dark:bg-gray-600 px-4 py-3 text-center font-semibold text-gray-500 dark:text-gray-400 cursor-not-allowed flex items-center justify-center gap-2">
                                    <span class="material-symbols-outlined">block</span>
                                    No Disponible
                                </button>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <div class="py-16 text-center">
                    <span class="material-symbols-outlined text-8xl text-gray-300 dark:text-gray-600">person_off</span>
                    <p class="mt-6 text-xl text-gray-500 dark:text-gray-400">No hay nutricionistas disponibles en este momento</p>
                    <a href="{{ route('paciente.dashboard') }}" 
                       class="mt-6 inline-flex items-center gap-2 rounded-lg bg-green-600 px-6 py-3 font-semibold text-white transition hover:bg-green-700">
                        <span class="material-symbols-outlined">arrow_back</span>
                        Volver al Dashboard
                    </a>
                </div>
            @endif
        </div>
    </main>

    @include('layouts.footer')
</body>
@endsection

