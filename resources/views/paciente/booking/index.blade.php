@extends('layouts.app')

@section('title', 'Agendar Cita - NutriFit')

@section('content')
<body class="min-h-screen bg-gradient-to-br from-purple-50 via-white to-pink-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
    @include('layouts.header')

    <main class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center gap-3 mb-2">
                <a href="{{ route('paciente.dashboard') }}" class="text-gray-600 dark:text-gray-400 hover:text-purple-600 dark:hover:text-purple-400 transition-colors">
                    <span class="material-symbols-outlined text-2xl">arrow_back</span>
                </a>
                <h1 class="text-3xl font-bold bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent">
                    Agendar Nueva Cita
                </h1>
            </div>
            <p class="text-gray-600 dark:text-gray-400 ml-11">
                Selecciona un nutricionista para ver sus horarios disponibles
            </p>
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

        <!-- Lista de Nutricionistas -->
        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            @forelse($nutricionistas as $nutricionista)
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow">
                    <!-- Header con gradiente -->
                    <div class="bg-gradient-to-r from-green-600 to-emerald-600 p-6">
                        <div class="flex items-center gap-4">
                            <div class="w-16 h-16 rounded-full bg-white flex items-center justify-center text-green-600 font-bold text-2xl">
                                {{ $nutricionista->initials() }}
                            </div>
                            <div class="text-white">
                                <h3 class="font-bold text-lg">{{ $nutricionista->name }}</h3>
                                <p class="text-green-100 text-sm">Nutricionista</p>
                            </div>
                        </div>
                    </div>

                    <!-- Contenido -->
                    <div class="p-6">
                        <div class="space-y-3 mb-6">
                            <div class="flex items-center gap-2 text-gray-600 dark:text-gray-400">
                                <span class="material-symbols-outlined text-sm">mail</span>
                                <span class="text-sm">{{ $nutricionista->email }}</span>
                            </div>
                            
                            <div class="flex items-center gap-2 text-gray-600 dark:text-gray-400">
                                <span class="material-symbols-outlined text-sm">schedule</span>
                                <span class="text-sm">
                                    @if($nutricionista->schedules_count > 0)
                                        {{ $nutricionista->schedules_count }} horarios disponibles
                                    @else
                                        Sin horarios configurados
                                    @endif
                                </span>
                            </div>
                        </div>

                        @if($nutricionista->schedules_count > 0)
                            <a href="{{ route('paciente.booking.schedule', $nutricionista) }}" 
                               class="block w-full text-center bg-gradient-to-r from-purple-600 to-pink-600 text-white font-semibold py-3 rounded-lg hover:from-purple-700 hover:to-pink-700 transition-all">
                                Ver Horarios Disponibles
                            </a>
                        @else
                            <button disabled 
                                    class="block w-full text-center bg-gray-300 dark:bg-gray-700 text-gray-500 dark:text-gray-400 font-semibold py-3 rounded-lg cursor-not-allowed">
                                Sin Horarios
                            </button>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-full">
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-12 text-center">
                        <span class="material-symbols-outlined text-6xl text-gray-300 dark:text-gray-600">person_off</span>
                        <p class="mt-4 text-gray-500 dark:text-gray-400">No hay nutricionistas disponibles en este momento</p>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Info adicional -->
        <div class="mt-8 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg px-4 py-3">
            <div class="flex gap-3">
                <span class="material-symbols-outlined text-blue-600 dark:text-blue-400">info</span>
                <div class="text-sm text-blue-800 dark:text-blue-200">
                    <p class="font-medium mb-1">¿Cómo agendar?</p>
                    <ul class="list-disc list-inside space-y-1 text-blue-700 dark:text-blue-300">
                        <li>Selecciona un nutricionista</li>
                        <li>Elige una fecha y horario disponible</li>
                        <li>Completa la información de la cita</li>
                        <li>El nutricionista confirmará tu cita</li>
                    </ul>
                </div>
            </div>
        </div>
    </main>

    @include('layouts.footer')
</body>
@endsection
