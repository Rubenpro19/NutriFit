@extends('layouts.app')

@section('title', 'Mis Pacientes - NutriFit')

@section('content')
<body class="min-h-screen bg-gradient-to-br from-green-50 via-emerald-50 to-rose-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 flex flex-col">
    @include('layouts.header')

    <main class="flex-grow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
            <!-- Breadcrumb -->
            <nav class="mb-6 flex items-center gap-2 text-sm">
                <a href="{{ route('nutricionista.dashboard') }}" class="text-green-600 dark:text-green-400 hover:underline transition-colors">Inicio</a>
                <span class="material-symbols-outlined text-gray-400 text-sm">chevron_right</span>
                <span class="text-gray-700 dark:text-gray-300 font-medium">Mis Pacientes</span>
            </nav>

            <!-- Header -->
            <div class="mb-8 bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-4 sm:p-6 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between gap-3">
                    <div class="flex items-center gap-3 flex-1 min-w-0">
                        <a href="{{ route('nutricionista.dashboard') }}" 
                        class="flex-shrink-0 text-gray-700 dark:text-gray-300 hover:text-green-600 dark:hover:text-green-400 hover:scale-110 transition-all">
                            <span class="material-symbols-outlined text-2xl">arrow_back</span>
                        </a>
                        <div class="min-w-0 flex-1">
                            <h1 class="text-2xl sm:text-3xl font-bold bg-gradient-to-r from-green-600 to-emerald-600 dark:from-green-400 dark:to-emerald-400 bg-clip-text text-transparent">
                                Mis Pacientes
                            </h1>
                            <p class="text-sm sm:text-base text-gray-700 dark:text-gray-300 mt-1 truncate">Gestiona y visualiza la información de tus pacientes</p>
                        </div>
                    </div>
                    <div class="flex-shrink-0 hidden sm:flex items-center">
                        <span class="material-symbols-outlined text-3xl sm:text-4xl text-green-600 dark:text-green-400">groups</span>
                    </div>
                </div>
            </div>

            <livewire:nutricionista.patients-table />
        </div>
    </main>

    @include('layouts.footer')
</body>
@endsection