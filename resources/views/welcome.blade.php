@extends('layouts.app')

@section('title', 'NutriFit-Home')

@section('content')
<body class="min-h-screen flex flex-col bg-gradient-to-br from-[#e9fcd9] to-[#d4f4dd] text-gray-900 dark:from-gray-900 dark:to-gray-800 dark:text-white">

    @include('layouts.header')

    <!-- HERO SECTION -->
    <main class="flex-grow flex flex-col items-center justify-center text-center px-4 py-12">
        <h1 class="text-3xl lg:text-4xl font-bold text-green-900 dark:text-green-400 mb-2">
            Agenda tu cita con un nutricionista profesional
        </h1>
        <p class="text-gray-700 dark:text-gray-300 mb-10">Empieza tu camino hacia una vida más saludable</p>

        <!-- CARDS -->
        <div class="grid gap-6 md:grid-cols-3 max-w-5xl w-full">
            <!-- Card 1 -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 border border-green-100 dark:border-gray-700 hover:shadow-lg transition">
                <div class="text-green-700 dark:text-green-500 text-4xl mb-3">
                    <span class="material-symbols-outlined text-5xl">stethoscope</span>
                </div>
                <h3 class="font-semibold text-lg mb-2 dark:text-white">Consultas Nutricionales</h3>
                <p class="text-gray-600 dark:text-gray-400 text-sm">
                    Consulta personalizada para mejorar tu salud y nutrición.
                </p>
            </div>

            <!-- Card 2 -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 border border-green-100 dark:border-gray-700 hover:shadow-lg transition">
                <div class="text-green-700 dark:text-green-500 text-4xl mb-3">
                    <span class="material-symbols-outlined text-5xl">medical_information</span>
                </div>
                <h3 class="font-semibold text-lg mb-2 dark:text-white">Historial Clínico</h3>
                <p class="text-gray-600 dark:text-gray-400 text-sm">
                    Registro ordenado de tus datos de salud y evolución nutricional.
                </p>
            </div>

            <!-- Card 3 -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 border border-green-100 dark:border-gray-700 hover:shadow-lg transition">
                <div class="text-green-700 dark:text-green-500 text-4xl mb-3">
                    <span class="material-symbols-outlined text-5xl">monitor_heart</span>
                </div>
                <h3 class="font-semibold text-lg mb-2 dark:text-white">Seguimiento</h3>
                <p class="text-gray-600 dark:text-gray-400 text-sm">
                    Revisión y seguimiento regular para asegurar tus procesos.
                </p>
            </div>
        </div>
    </main>

    @include('layouts.footer')
</body>
@endsection