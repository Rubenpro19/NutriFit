@extends('layouts.app')

@section('title', 'Sobre Nosotros - NutriFit')

@section('content')
<body class="min-h-screen flex flex-col bg-gradient-to-br from-[#e9fcd9] to-[#d4f4dd] text-gray-900 dark:from-gray-900 dark:to-gray-800 dark:text-white">

    @include('layouts.header')

    <!-- HERO SECTION -->
    <section class="flex-grow">
        <div class="relative overflow-hidden bg-gradient-to-r from-green-600 to-green-800 py-16 text-white">
            <div class="container mx-auto px-4 text-center">
                <h1 class="mb-4 text-4xl font-bold lg:text-5xl">Sobre Nosotros</h1>
                <p class="mx-auto max-w-2xl text-lg text-green-100">
                    Conoce nuestra plataforma y cómo facilitamos el acceso a servicios de nutrición profesional
                </p>
            </div>
        </div>

        <!-- NUESTRA HISTORIA -->
        <div class="container mx-auto px-4 py-16">
            <div class="mx-auto max-w-4xl">
                <div class="mb-12 text-center">
                    <span class="material-symbols-outlined mb-4 text-6xl text-green-700">history_edu</span>
                    <h2 class="mb-4 text-3xl font-bold text-green-900 dark:text-green-400">Nuestra Historia</h2>
                    <div class="space-y-4 text-lg leading-relaxed text-gray-700 dark:text-gray-300">
                        <p>
                            NutriFit nació en 2026 con el objetivo de simplificar la gestión de consultas nutricionales. 
                            Nuestra plataforma digital facilita la conexión entre pacientes y profesionales de la nutrición, 
                            optimizando el proceso de agendamiento y seguimiento.
                        </p>
                        <p>
                            Entendemos que el acceso a servicios de nutrición debe ser sencillo y eficiente. Por eso, 
                            desarrollamos una herramienta que centraliza la gestión de citas, historiales y comunicación, 
                            permitiendo a los profesionales ofrecer un mejor servicio y a los pacientes gestionar su salud 
                            de manera más cómoda.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- MISIÓN Y VISIÓN -->
        <div class="bg-white dark:bg-gray-800 py-16">
            <div class="container mx-auto px-4">
                <div class="grid gap-8 lg:grid-cols-2">
                    <!-- MISIÓN -->
                    <div class="rounded-2xl bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/30 dark:to-green-800/30 p-8 shadow-lg border border-green-200 dark:border-green-700 transition-all duration-300 transform hover:scale-105 hover:-translate-y-2">
                        <div class="mb-4 flex items-center gap-3">
                            <span class="material-symbols-outlined text-4xl text-green-700 dark:text-green-400">flag</span>
                            <h3 class="text-2xl font-bold text-green-900 dark:text-green-400">Nuestra Misión</h3>
                        </div>
                        <p class="text-gray-700 dark:text-gray-300 leading-relaxed">
                            Facilitar la gestión de consultas nutricionales mediante una plataforma digital sencilla e intuitiva, 
                            que optimice la comunicación entre profesionales y pacientes, permitiendo un seguimiento organizado 
                            y accesible del proceso nutricional.
                        </p>
                    </div>

                    <!-- VISIÓN -->
                    <div class="rounded-2xl bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/30 dark:to-blue-800/30 p-8 shadow-lg border border-blue-200 dark:border-blue-700 transition-all duration-300 transform hover:scale-105 hover:-translate-y-2">
                        <div class="mb-4 flex items-center gap-3">
                            <span class="material-symbols-outlined text-4xl text-blue-700 dark:text-blue-400">visibility</span>
                            <h3 class="text-2xl font-bold text-blue-900 dark:text-blue-400">Nuestra Visión</h3>
                        </div>
                        <p class="text-gray-700 dark:text-gray-300 leading-relaxed">
                            Convertirnos en una herramienta de referencia para la gestión de consultas nutricionales, 
                            destacando por nuestra facilidad de uso, confiabilidad y el valor que aportamos tanto a 
                            profesionales como a pacientes en el cuidado de su salud.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- VALORES -->
        <div class="container mx-auto px-4 py-16">
            <div class="mb-12 text-center">
                <span class="material-symbols-outlined mb-4 text-6xl text-green-700">favorite</span>
                <h2 class="mb-4 text-3xl font-bold text-green-900 dark:text-green-400">Nuestros Valores</h2>
                <p class="text-gray-600 dark:text-gray-300">Los principios que guían nuestro trabajo diario</p>
            </div>

            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-4">
                <!-- Valor 1 -->
                <div class="group rounded-xl bg-white dark:bg-gray-800 p-6 shadow-md transition-all duration-300 transform hover:scale-105 hover:-translate-y-2">
                    <div class="mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-green-100 dark:bg-green-900/30 group-hover:bg-green-200 dark:group-hover:bg-green-800/40">
                        <span class="material-symbols-outlined text-3xl text-green-700 dark:text-green-400">health_and_safety</span>
                    </div>
                    <h4 class="mb-2 text-lg font-semibold text-green-900 dark:text-green-400">Salud Primero</h4>
                    <p class="text-sm text-gray-600 dark:text-gray-300">
                        La salud y el bienestar de nuestros usuarios es nuestra máxima prioridad en cada decisión que tomamos.
                    </p>
                </div>

                <!-- Valor 2 -->
                <div class="group rounded-xl bg-white dark:bg-gray-800 p-6 shadow-md transition-all duration-300 transform hover:scale-105 hover:-translate-y-2">
                    <div class="mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-blue-100 dark:bg-blue-900/30 group-hover:bg-blue-200 dark:group-hover:bg-blue-800/40">
                        <span class="material-symbols-outlined text-3xl text-blue-700 dark:text-blue-400">verified</span>
                    </div>
                    <h4 class="mb-2 text-lg font-semibold text-blue-900 dark:text-blue-400">Profesionalismo</h4>
                    <p class="text-sm text-gray-600 dark:text-gray-300">
                        Facilitamos el trabajo de profesionales comprometidos con brindar un servicio de calidad.
                    </p>
                </div>

                <!-- Valor 3 -->
                <div class="group rounded-xl bg-white dark:bg-gray-800 p-6 shadow-md transition-all duration-300 transform hover:scale-105 hover:-translate-y-2">
                    <div class="mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-purple-100 dark:bg-purple-900/30 group-hover:bg-purple-200 dark:group-hover:bg-purple-800/40">
                        <span class="material-symbols-outlined text-3xl text-purple-700 dark:text-purple-400">lightbulb</span>
                    </div>
                    <h4 class="mb-2 text-lg font-semibold text-purple-900 dark:text-purple-400">Innovación</h4>
                    <p class="text-sm text-gray-600 dark:text-gray-300">
                        Utilizamos tecnología moderna para mejorar continuamente la experiencia de nuestros usuarios.
                    </p>
                </div>

                <!-- Valor 4 -->
                <div class="group rounded-xl bg-white dark:bg-gray-800 p-6 shadow-md transition-all duration-300 transform hover:scale-105 hover:-translate-y-2">
                    <div class="mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-orange-100 dark:bg-orange-900/30 group-hover:bg-orange-200 dark:group-hover:bg-orange-800/40">
                        <span class="material-symbols-outlined text-3xl text-orange-700 dark:text-orange-400">handshake</span>
                    </div>
                    <h4 class="mb-2 text-lg font-semibold text-orange-900 dark:text-orange-400">Confianza</h4>
                    <p class="text-sm text-gray-600 dark:text-gray-300">
                        Construimos relaciones basadas en la transparencia, privacidad y respeto mutuo.
                    </p>
                </div>
            </div>
        </div>
        <!-- LLAMADO A LA ACCIÓN -->
        <div class="container mx-auto px-4 py-16">
            <div class="mx-auto max-w-3xl rounded-2xl bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/30 dark:to-green-800/30 dark:border dark:border-green-700 p-8 text-center shadow-xl">
                <span class="material-symbols-outlined mb-4 text-6xl text-green-700 dark:text-green-400">rocket_launch</span>
                <h2 class="mb-4 text-3xl font-bold text-green-900 dark:text-green-400">¿Listo para comenzar?</h2>
                <p class="mb-6 text-lg text-gray-700 dark:text-gray-300">
                    Comienza tu camino hacia una mejor salud con NutriFit
                </p>
                <div class="flex flex-col gap-4 sm:flex-row sm:justify-center">
                    @guest
                        <a href="{{ route('register') }}" 
                           class="inline-flex items-center justify-center gap-2 rounded-lg bg-green-600 px-8 py-3 font-semibold text-white shadow-md transition hover:bg-green-700 dark:bg-green-600 dark:hover:bg-green-700">
                            <span class="material-symbols-outlined">person_add</span>
                            Regístrate Ahora
                        </a>
                        <a href="{{ route('contact') }}" 
                           class="inline-flex items-center justify-center gap-2 rounded-lg border-2 border-green-600 bg-white dark:bg-gray-800 dark:border-green-500 px-8 py-3 font-semibold text-green-600 dark:text-green-400 transition hover:bg-green-50 dark:hover:bg-gray-700">
                            <span class="material-symbols-outlined">mail</span>
                            Contáctanos
                        </a>
                    @else
                        @php
                            $dashboardRoute = match(auth()->user()->role->name) {
                                'administrador' => route('admin.dashboard'),
                                'nutricionista' => route('nutricionista.dashboard'),
                                'paciente' => route('paciente.dashboard'),
                                default => route('paciente.dashboard')
                            };
                        @endphp
                        <a href="{{ $dashboardRoute }}" 
                           class="inline-flex items-center justify-center gap-2 rounded-lg bg-green-600 px-8 py-3 font-semibold text-white shadow-md transition hover:bg-green-700 dark:bg-green-600 dark:hover:bg-green-700">
                            <span class="material-symbols-outlined">dashboard</span>
                            Ir al Dashboard
                        </a>
                    @endguest
                </div>
            </div>
        </div>
    </section>

    @include('layouts.footer')

</body>
@endsection
