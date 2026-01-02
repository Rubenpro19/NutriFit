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
                    Conoce nuestra historia, misión y el equipo que hace posible tu transformación hacia una vida más saludable
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
                            NutriFit nació con una visión clara: hacer que la nutrición profesional sea accesible para todos. 
                            Fundada en 2024, nuestra plataforma conecta a personas que buscan mejorar su salud con nutricionistas 
                            certificados y experimentados.
                        </p>
                        <p>
                            Creemos que una buena nutrición es la base de una vida plena y saludable. Por eso, hemos desarrollado 
                            una plataforma intuitiva que facilita la comunicación entre pacientes y profesionales, permitiendo 
                            un seguimiento personalizado y efectivo.
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
                            Proporcionar una plataforma digital confiable y fácil de usar que conecte a personas con 
                            nutricionistas profesionales, facilitando el acceso a asesoría nutricional de calidad y 
                            promoviendo hábitos alimenticios saludables que mejoren la calidad de vida de nuestros usuarios.
                        </p>
                    </div>

                    <!-- VISIÓN -->
                    <div class="rounded-2xl bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/30 dark:to-blue-800/30 p-8 shadow-lg border border-blue-200 dark:border-blue-700 transition-all duration-300 transform hover:scale-105 hover:-translate-y-2">
                        <div class="mb-4 flex items-center gap-3">
                            <span class="material-symbols-outlined text-4xl text-blue-700 dark:text-blue-400">visibility</span>
                            <h3 class="text-2xl font-bold text-blue-900 dark:text-blue-400">Nuestra Visión</h3>
                        </div>
                        <p class="text-gray-700 dark:text-gray-300 leading-relaxed">
                            Ser la plataforma líder en Ecuador para la gestión de consultas nutricionales, reconocida por 
                            nuestra innovación tecnológica, compromiso con la salud y la excelencia en el servicio que 
                            brindamos tanto a pacientes como a profesionales de la nutrición.
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
                        Trabajamos solo con nutricionistas certificados y comprometidos con la excelencia en su práctica.
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

        <!-- ESTADÍSTICAS -->
        <div class="bg-gradient-to-r from-green-700 to-green-900 py-16 text-white">
            <div class="container mx-auto px-4">
                <div class="mb-12 text-center">
                    <h2 class="mb-4 text-3xl font-bold">Nuestro Impacto</h2>
                    <p class="text-green-100">Números que reflejan nuestro compromiso</p>
                </div>

                <div class="grid gap-8 md:grid-cols-4">
                    <div class="text-center">
                        <div class="mb-2 text-5xl font-bold">500+</div>
                        <div class="text-green-200">Pacientes Atendidos</div>
                    </div>
                    <div class="text-center">
                        <div class="mb-2 text-5xl font-bold">25+</div>
                        <div class="text-green-200">Nutricionistas Certificados</div>
                    </div>
                    <div class="text-center">
                        <div class="mb-2 text-5xl font-bold">1,200+</div>
                        <div class="text-green-200">Consultas Realizadas</div>
                    </div>
                    <div class="text-center">
                        <div class="mb-2 text-5xl font-bold">95%</div>
                        <div class="text-green-200">Satisfacción del Cliente</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- LLAMADO A LA ACCIÓN -->
        <div class="container mx-auto px-4 py-16">
            <div class="mx-auto max-w-3xl rounded-2xl bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/30 dark:to-green-800/30 dark:border dark:border-green-700 p-8 text-center shadow-xl">
                <span class="material-symbols-outlined mb-4 text-6xl text-green-700 dark:text-green-400">rocket_launch</span>
                <h2 class="mb-4 text-3xl font-bold text-green-900 dark:text-green-400">¿Listo para comenzar?</h2>
                <p class="mb-6 text-lg text-gray-700 dark:text-gray-300">
                    Únete a cientos de personas que ya están transformando su salud con NutriFit
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
