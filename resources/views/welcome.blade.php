@extends('layouts.app')

@section('title', 'NutriFit-Home')

@section('content')
<body class="min-h-screen flex flex-col relative text-gray-900 dark:text-white">

    <!-- Background Image con Overlay -->
    <div class="absolute inset-0 z-0">
        <img src="https://images.unsplash.com/photo-1490645935967-10de6ba17061?q=80&w=2053&auto=format&fit=crop" 
             alt="Background" 
             class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-br from-gray-900/50 via-black/40 to-gray-800/50"></div>
    </div>

    <!-- Content -->
    <div class="relative z-10 flex flex-col min-h-screen">
        @include('layouts.header')

        <!-- HERO SECTION -->
        <main class="flex-grow flex flex-col items-center justify-center text-center px-4 py-12">
            <!-- Hero Content -->
            <div class="mb-12 animate-fade-in">
                <h1 class="text-4xl lg:text-6xl font-extrabold text-white mb-4 drop-shadow-lg leading-tight">
                    Tu salud es nuestra <span class="text-green-300">prioridad</span>
                </h1>
                <p class="text-xl lg:text-2xl text-green-100 mb-8 max-w-2xl mx-auto drop-shadow">
                    Agenda tu cita con nutricionistas profesionales y transforma tu vida
                </p>
                
            </div>

            <!-- CARDS con Glassmorphism -->
            <div class="grid gap-6 md:grid-cols-3 max-w-6xl w-full">
                <!-- Card 1 -->
                <div class="group bg-white/10 backdrop-blur-md rounded-2xl shadow-2xl p-8 border border-white/20 hover:bg-white/20 transition-all duration-300 transform hover:scale-105 hover:-translate-y-2">
                    <div class="text-green-300 mb-4 transform group-hover:scale-110 transition-transform duration-300">
                        <span class="material-symbols-outlined text-6xl drop-shadow-lg">stethoscope</span>
                    </div>
                    <h3 class="font-bold text-xl mb-3 text-white">Consultas Nutricionales</h3>
                    <p class="text-green-100 text-sm leading-relaxed">
                        Recibe atención personalizada de nutricionistas certificados para alcanzar tus objetivos de salud.
                    </p>
                </div>

                <!-- Card 2 -->
                <div class="group bg-white/10 backdrop-blur-md rounded-2xl shadow-2xl p-8 border border-white/20 hover:bg-white/20 transition-all duration-300 transform hover:scale-105 hover:-translate-y-2">
                    <div class="text-green-300 mb-4 transform group-hover:scale-110 transition-transform duration-300">
                        <span class="material-symbols-outlined text-6xl drop-shadow-lg">medical_information</span>
                    </div>
                    <h3 class="font-bold text-xl mb-3 text-white">Historial Clínico</h3>
                    <p class="text-green-100 text-sm leading-relaxed">
                        Mantén un registro completo y organizado de tu evolución nutricional y datos de salud.
                    </p>
                </div>

                <!-- Card 3 -->
                <div class="group bg-white/10 backdrop-blur-md rounded-2xl shadow-2xl p-8 border border-white/20 hover:bg-white/20 transition-all duration-300 transform hover:scale-105 hover:-translate-y-2">
                    <div class="text-green-300 mb-4 transform group-hover:scale-110 transition-transform duration-300">
                        <span class="material-symbols-outlined text-6xl drop-shadow-lg">monitor_heart</span>
                    </div>
                    <h3 class="font-bold text-xl mb-3 text-white">Seguimiento Continuo</h3>
                    <p class="text-green-100 text-sm leading-relaxed">
                        Monitoreo constante de tu progreso con citas de seguimiento y ajustes personalizados.
                    </p>
                </div>
            </div>
        </main>

        @include('layouts.footer')
    </div>

    <style>
        @keyframes fade-in {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .animate-fade-in {
            animation: fade-in 1s ease-out;
        }
    </style>
</body>
@endsection