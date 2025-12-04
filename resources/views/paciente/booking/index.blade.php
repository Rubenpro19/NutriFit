@extends('layouts.app')

@section('title', 'Agendar Cita - NutriFit')

@section('content')
<body class="min-h-screen bg-gradient-to-br from-green-50 via-emerald-50 to-rose-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 flex flex-col">
    @include('layouts.header')

    <main class="container mx-auto px-4 py-8 flex-grow">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center gap-3 mb-2">
                <a href="{{ route('paciente.dashboard') }}" class="text-gray-600 dark:text-gray-400 hover:text-green-600 dark:hover:text-green-400 transition-colors">
                    <span class="material-symbols-outlined text-2xl">arrow_back</span>
                </a>
                <h1 class="text-3xl font-bold bg-gradient-to-r from-green-600 to-emerald-600 bg-clip-text text-transparent">
                    Selecciona tu Nutricionista
                </h1>
            </div>
            <p class="text-gray-600 dark:text-gray-400 ml-11">
                Elige al nutricionista con el que deseas agendar tu cita
            </p>
        </div>

        @if(session('error'))
            <div class="mb-6 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-800 dark:text-red-200 px-4 py-3 rounded-lg flex items-center gap-3">
                <span class="material-symbols-outlined">error</span>
                <span>{{ session('error') }}</span>
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
    </main>

    @include('layouts.footer')
</body>
@endsection

