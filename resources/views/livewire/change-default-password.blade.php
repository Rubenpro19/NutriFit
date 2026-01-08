@extends('layouts.app')

@section('title', 'Cambiar Contraseña - NutriFit')

@section('content')
<body class="min-h-screen bg-gradient-to-br from-green-50 via-emerald-50 to-teal-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
    @include('layouts.header')

<div class="min-h-screen flex items-center justify-center px-4 py-12">
    <div class="w-full max-w-md">
        <!-- Logo y Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl shadow-lg mb-4">
                <span class="material-symbols-outlined text-white text-4xl">lock_reset</span>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2 flex items-center justify-center gap-2">
                <span class="material-symbols-outlined text-4xl">lock</span>
                Cambiar Contraseña
            </h1>
            <p class="text-gray-600 dark:text-gray-400">Paso obligatorio de seguridad</p>
        </div>

        <!-- Alerta Informativa -->
        <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mb-6 dark:bg-amber-900/20 dark:border-amber-800">
            <div class="flex items-start gap-3">
                <span class="material-symbols-outlined text-amber-600 dark:text-amber-400 text-2xl">warning</span>
                <div class="flex-1">
                    <h3 class="font-semibold text-amber-900 dark:text-amber-300 mb-1">Contraseña Temporal Detectada</h3>
                    <p class="text-sm text-amber-800 dark:text-amber-400">
                        Iniciaste sesión con Google y se te asignó una contraseña temporal. Por tu seguridad, debes cambiarla ahora.
                    </p>
                </div>
            </div>
        </div>

        @if(session('warning'))
            <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mb-6 dark:bg-amber-900/20 dark:border-amber-800">
                <div class="flex items-center gap-3">
                    <span class="material-symbols-outlined text-amber-600 dark:text-amber-400">info</span>
                    <p class="text-amber-900 dark:text-amber-300 font-medium">{{ session('warning') }}</p>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6 dark:bg-red-900/20 dark:border-red-800">
                <div class="flex items-center gap-3">
                    <span class="material-symbols-outlined text-red-600 dark:text-red-400">error</span>
                    <p class="text-red-900 dark:text-red-300 font-medium">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        <!-- Card Principal -->
        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl p-8">
            <div class="mb-6">
                <div class="flex items-start gap-3 text-sm text-blue-800 dark:text-blue-300 bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg">
                    <span class="material-symbols-outlined text-blue-600 dark:text-blue-400">info</span>
                    <div>
                        <p class="mb-2">Con esta nueva contraseña podrás:</p>
                        <ul class="space-y-1 ml-4">
                            <li class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-xs">check_circle</span>
                                Iniciar sesión con correo y contraseña
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-xs">check_circle</span>
                                Seguir usando Google para acceder
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-xs">check_circle</span>
                                Mayor seguridad en tu cuenta
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <form wire:submit.prevent="changePassword">
                <div class="space-y-6">
                    <!-- Nueva Contraseña -->
                    <div>
                        <label for="password" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2 flex items-center gap-2">
                            <span class="material-symbols-outlined text-lg">key</span>
                            Nueva Contraseña
                        </label>
                        <input 
                            type="password" 
                            id="password"
                            wire:model="password"
                            required
                            placeholder="Ingresa tu contraseña (mínimo 8 caracteres)"
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-green-500 focus:border-transparent @error('password') border-red-500 @enderror"
                        >
                        @error('password')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center gap-1">
                                <span class="material-symbols-outlined text-sm">error</span>
                                {{ $message }}
                            </p>
                        @enderror
                        <p class="mt-2 text-xs text-gray-600 dark:text-gray-400">
                            Mínimo 8 caracteres. Usa una combinación de letras, números y símbolos.
                        </p>
                    </div>

                    <!-- Confirmar Contraseña -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2 flex items-center gap-2">
                            <span class="material-symbols-outlined text-lg">key</span>
                            Confirmar Nueva Contraseña
                        </label>
                        <input 
                            type="password" 
                            id="password_confirmation"
                            wire:model="password_confirmation"
                            required
                            placeholder="Confirma tu contraseña"
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-green-500 focus:border-transparent"
                        >
                    </div>

                    <!-- Botón Cambiar -->
                    <button 
                        type="submit" 
                        class="w-full flex items-center justify-center gap-2 rounded-lg bg-gradient-to-r from-green-600 to-emerald-600 px-6 py-4 text-base font-semibold text-white hover:from-green-700 hover:to-emerald-700 focus:outline-none focus:ring-4 focus:ring-green-300 dark:focus:ring-green-800 shadow-lg transition-all duration-200 transform hover:scale-[1.02]"
                    >
                        <span class="material-symbols-outlined text-xl">lock_open</span>
                        Cambiar Contraseña y Continuar
                    </button>
                </div>
            </form>

            <!-- Nota de Seguridad -->
            <div class="mt-6 pt-6 border-t dark:border-gray-700">
                <div class="flex items-start gap-3 text-xs text-gray-600 dark:text-gray-400">
                    <span class="material-symbols-outlined text-green-600 dark:text-green-400 text-lg">security</span>
                    <p>
                        <span class="font-semibold text-gray-900 dark:text-white">Importante:</span> 
                        No podrás acceder a las funcionalidades del sistema hasta que cambies tu contraseña temporal. Esto garantiza la seguridad de tu cuenta.
                    </p>
                </div>
            </div>
        </div>

        <!-- Botón de Cerrar Sesión -->
        <div class="text-center mt-6">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white font-medium">
                    <span class="flex items-center justify-center gap-1">
                        <span class="material-symbols-outlined text-sm">logout</span>
                        Cerrar Sesión
                    </span>
            </form>
        </div>
    </div>
</div>

@include('layouts.footer')
</body>
@endsection