@extends('layouts.app')

@section('title', 'Verificar Correo Electrónico - NutriFit')

@section('content')
<body class="min-h-screen bg-gradient-to-br from-green-50 via-emerald-50 to-teal-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 flex flex-col">
    @include('layouts.header')

    <main class="flex-grow flex items-center justify-center px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
        <div class="w-full max-w-md">
            <!-- Logo y Header -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl shadow-lg mb-4">
                    <span class="material-symbols-outlined text-white text-4xl">mark_email_unread</span>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">
                    Verifica tu Correo
                </h1>
                <p class="text-gray-600 dark:text-gray-400">Un paso más para completar tu registro</p>
            </div>

            @if (session('status') == 'verification-link-sent')
                <div class="bg-green-50 border border-green-200 rounded-xl p-4 mb-6 dark:bg-green-900/20 dark:border-green-800">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-green-600 dark:text-green-400">check_circle</span>
                        <p class="text-green-900 dark:text-green-300 font-medium">
                            ¡Correo reenviado! Revisa tu bandeja de entrada.
                        </p>
                    </div>
                </div>
            @endif

            <!-- Card Principal -->
            <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl p-8">
                <!-- Información -->
                <div class="mb-6">
                    <div class="flex items-start gap-3 text-sm text-blue-800 dark:text-blue-300 bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg mb-4">
                        <span class="material-symbols-outlined text-blue-600 dark:text-blue-400">info</span>
                        <div>
                            <p class="mb-2">
                                Hemos enviado un enlace de verificación a tu correo electrónico <strong>{{ auth()->user()->email }}</strong>
                            </p>
                            <p>Por favor, revisa tu bandeja de entrada y haz clic en el enlace para verificar tu cuenta.</p>
                        </div>
                    </div>

                    <!-- Instrucciones -->
                    <div class="space-y-3 text-sm text-gray-700 dark:text-gray-300">
                        <div class="flex items-start gap-2">
                            <span class="material-symbols-outlined text-green-600 dark:text-green-400 text-lg">check_circle</span>
                            <p>Revisa tu carpeta de <strong>spam o correo no deseado</strong></p>
                        </div>
                        <div class="flex items-start gap-2">
                            <span class="material-symbols-outlined text-green-600 dark:text-green-400 text-lg">check_circle</span>
                            <p>El correo proviene de <strong>{{ config('mail.from.address') }}</strong></p>
                        </div>
                        <div class="flex items-start gap-2">
                            <span class="material-symbols-outlined text-green-600 dark:text-green-400 text-lg">check_circle</span>
                            <p>El enlace de verificación expira en <strong>60 minutos</strong></p>
                        </div>
                    </div>
                </div>

                <!-- Formulario para reenviar correo -->
                <form method="POST" action="{{ route('verification.send') }}" class="space-y-4">
                    @csrf
                    <button 
                        type="submit" 
                        class="w-full flex items-center justify-center gap-2 rounded-lg bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-4 text-base font-semibold text-white hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:focus:ring-blue-800 shadow-lg transition-all duration-200 transform hover:scale-[1.02]"
                    >
                        <span class="material-symbols-outlined text-xl">forward_to_inbox</span>
                        Reenviar Correo de Verificación
                    </button>
                </form>

                <!-- Nota de ayuda -->
                <div class="mt-6 pt-6 border-t dark:border-gray-700">
                    <div class="flex items-start gap-3 text-xs text-gray-600 dark:text-gray-400">
                        <span class="material-symbols-outlined text-amber-600 dark:text-amber-400 text-lg">help</span>
                        <p>
                            <span class="font-semibold text-gray-900 dark:text-white">¿No recibiste el correo?</span> 
                            Espera unos minutos y verifica tu bandeja de spam. Si el problema persiste, haz clic en el botón de arriba para reenviar el correo.
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
                    </button>
                </form>
            </div>
        </div>
    </main>

    @include('layouts.footer')
</body>
@endsection
