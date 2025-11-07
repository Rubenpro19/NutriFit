<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NutriFit - Inicio</title>
    @vite('resources/css/app.css')

    <!-- Google Material Symbols -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />

    <style>
        .material-symbols-outlined {
            font-variation-settings:
            'FILL' 0,
            'wght' 400,
            'GRAD' 0,
            'opsz' 24;
        }
    </style>
</head>

<body class="bg-[#f6ffe8] text-[#1b1b18] flex flex-col min-h-screen">

    <!-- NAVBAR -->
    <header style="background-color:#9be38d" class="text-white py-3 shadow-md">
        <div class="max-w-7xl mx-auto px-6 flex justify-between items-center">
            <!-- LOGO -->
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-3xl">spa</span>
                <span class="font-semibold text-lg text-white">NutriFit</span>
            </div>

            <!-- NAV LINKS -->
            <nav class="flex items-center space-x-6 text-sm font-medium">
                <a href="#" class="hover:underline flex items-center gap-1">
                    <span class="material-symbols-outlined">groups</span>
                    Sobre Nosotros
                </a>
                <a href="#" class="hover:underline flex items-center gap-1">
                    <span class="material-symbols-outlined">mail</span>
                    Contacto
                </a>

                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="hover:underline flex items-center gap-1">
                            <span class="material-symbols-outlined">dashboard</span>
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="hover:underline flex items-center gap-1">
                            <span class="material-symbols-outlined">login</span>
                            Iniciar sesión
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="hover:underline flex items-center gap-1">
                                <span class="material-symbols-outlined">person_add</span>
                                Registrarse
                            </a>
                        @endif
                    @endauth
                @endif
            </nav>
        </div>
    </header>

    <!-- HERO SECTION -->
    <main class="flex-grow flex flex-col items-center justify-center text-center px-4 py-12">
        <h1 class="text-3xl lg:text-4xl font-bold text-green-900 mb-2">
            Agenda tu cita con un nutricionista profesional
        </h1>
        <p class="text-gray-700 mb-10">Empieza tu camino hacia una vida más saludable</p>

        <!-- CARDS -->
        <div class="grid gap-6 md:grid-cols-3 max-w-5xl w-full">
            <!-- Card 1 -->
            <div class="bg-white rounded-xl shadow-md p-6 border border-green-100 hover:shadow-lg transition">
                <div class="text-green-700 text-4xl mb-3">
                    <span class="material-symbols-outlined text-5xl">stethoscope</span>
                </div>
                <h3 class="font-semibold text-lg mb-2">Consultas Nutricionales</h3>
                <p class="text-gray-600 text-sm">
                    Consulta personalizada para mejorar tu salud y nutrición.
                </p>
            </div>

            <!-- Card 2 -->
            <div class="bg-white rounded-xl shadow-md p-6 border border-green-100 hover:shadow-lg transition">
                <div class="text-green-700 text-4xl mb-3">
                    <span class="material-symbols-outlined text-5xl">medical_information</span>
                </div>
                <h3 class="font-semibold text-lg mb-2">Historial Clínico</h3>
                <p class="text-gray-600 text-sm">
                    Registro ordenado de tus datos de salud y evolución nutricional.
                </p>
            </div>

            <!-- Card 3 -->
            <div class="bg-white rounded-xl shadow-md p-6 border border-green-100 hover:shadow-lg transition">
                <div class="text-green-700 text-4xl mb-3">
                    <span class="material-symbols-outlined text-5xl">monitor_heart</span>
                </div>
                <h3 class="font-semibold text-lg mb-2">Seguimiento</h3>
                <p class="text-gray-600 text-sm">
                    Revisión y seguimiento regular para asegurar tus procesos.
                </p>
            </div>
        </div>
    </main>

    <!-- FOOTER -->
    <footer style="background-color:#9be38d" class="text-center py-4 text-sm text-white shadow-md">
        <div class="flex flex-col items-center justify-center space-y-2">
            <div class="flex items-center gap-2">
                <span>© 2025 NutriFit. Todos los derechos reservados.</span>
            </div>

            <div class="flex space-x-4">
                <a href="#" class="flex items-center gap-1 hover:text-gray-200">
                    <span class="material-symbols-outlined">public</span>
                    Facebook
                </a>
                <a href="#" class="flex items-center gap-1 hover:text-gray-200">
                    <span class="material-symbols-outlined">photo_camera</span>
                    Instagram
                </a>
                <a href="#" class="flex items-center gap-1 hover:text-gray-200">
                    <span class="material-symbols-outlined">chat</span>
                    Twitter
                </a>
            </div>
        </div>
    </footer>

</body>
</html>
