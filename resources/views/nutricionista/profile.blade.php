@extends('layouts.app')
@section('title', 'Mi Perfil - NutriFit')
@section('content')
<body class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-green-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 flex flex-col">
    @include('layouts.header')

    <main class="flex-grow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
            <!-- Breadcrumb -->
            <nav class="mb-6 flex items-center gap-2 text-sm">
                <a href="{{ route('nutricionista.dashboard') }}" class="text-green-600 dark:text-green-400 hover:underline transition-colors">Inicio</a>
                <span class="material-symbols-outlined text-gray-400 text-sm">chevron_right</span>
                <span class="text-gray-700 dark:text-gray-300 font-medium">Mi Perfil</span>
            </nav>

            <livewire:nutricionista.nutricionista-profile />
        </div>
    </main>

    <!-- Toast de Éxito - Perfil -->
    @if(session('success'))
    <div x-data="{ show: true }" 
         x-init="setTimeout(() => show = false, 5000)"
         x-show="show"
         x-cloak
         class="fixed top-20 right-4 z-50 max-w-md w-full sm:w-96"
         style="display: none;">
        <div x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="translate-x-full opacity-0"
             x-transition:enter-end="translate-x-0 opacity-100"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="translate-x-0 opacity-100"
             x-transition:leave-end="translate-x-full opacity-0"
             class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl border-l-4 border-green-500 overflow-hidden">
            
            <div class="p-4">
                <div class="flex items-start gap-3">
                    <div class="flex-shrink-0">
                        <div class="bg-green-100 dark:bg-green-900/30 rounded-full p-2">
                            <span class="material-symbols-outlined text-green-600 dark:text-green-400 text-2xl">check_circle</span>
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="text-sm font-bold text-gray-900 dark:text-white mb-1">
                            ¡Perfil Actualizado!
                        </h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            {{ session('success') }}
                        </p>
                    </div>
                    <button @click="show = false"
                            class="flex-shrink-0 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>
            </div>
            <div class="h-1 bg-gray-200 dark:bg-gray-700">
                <div class="h-full bg-green-500 transition-all duration-100" style="width: 100%; animation: shrink 5s linear forwards;"></div>
            </div>
        </div>
    </div>
    @endif

    <!-- Toast de Éxito - Contraseña -->
    @if(session('password_success'))
    <div x-data="{ show: true }" 
         x-init="setTimeout(() => show = false, 5000)"
         x-show="show"
         x-cloak
         class="fixed top-20 right-4 z-50 max-w-md w-full sm:w-96"
         style="display: none;">
        <div x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="translate-x-full opacity-0"
             x-transition:enter-end="translate-x-0 opacity-100"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="translate-x-0 opacity-100"
             x-transition:leave-end="translate-x-full opacity-0"
             class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl border-l-4 border-green-500 overflow-hidden">
            
            <div class="p-4">
                <div class="flex items-start gap-3">
                    <div class="flex-shrink-0">
                        <div class="bg-green-100 dark:bg-green-900/30 rounded-full p-2">
                            <span class="material-symbols-outlined text-green-600 dark:text-green-400 text-2xl">lock</span>
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="text-sm font-bold text-gray-900 dark:text-white mb-1">
                            ¡Contraseña Actualizada!
                        </h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            {{ session('password_success') }}
                        </p>
                    </div>
                    <button @click="show = false"
                            class="flex-shrink-0 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>
            </div>
            <div class="h-1 bg-gray-200 dark:bg-gray-700">
                <div class="h-full bg-green-500 transition-all duration-100" style="width: 100%; animation: shrink 5s linear forwards;"></div>
            </div>
        </div>
    </div>
    @endif

    @include('layouts.footer')

<style>
    @keyframes shrink {
        from { width: 100%; }
        to { width: 0%; }
    }
</style>
</body>
@endsection
