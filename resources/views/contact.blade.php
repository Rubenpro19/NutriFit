@extends('layouts.app')

@section('title', 'Contacto - NutriFit')

@php
    $settings = system_settings();
@endphp

@section('content')
<body class="min-h-screen flex flex-col bg-gradient-to-br from-[#e9fcd9] to-[#d4f4dd] text-gray-900 dark:from-gray-900 dark:to-gray-800 dark:text-white">

    @include('layouts.header')

    <!-- HERO SECTION -->
    <section class="flex-grow">
        <div class="relative overflow-hidden bg-gradient-to-r from-green-600 to-green-800 py-12 lg:py-16 text-white">
            <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\"30\" height=\"30\" viewBox=\"0 0 30 30\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cpath d=\"M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z\" fill=\"rgba(255,255,255,0.07)\"%3E%3C/path%3E%3C/svg%3E')] opacity-50"></div>
            <div class="container mx-auto px-4 text-center relative">
                <h1 class="mb-4 text-3xl font-bold lg:text-5xl">Contáctanos</h1>
                <p class="mx-auto max-w-2xl text-base lg:text-lg text-green-100">
                    ¿Tienes preguntas? Estamos aquí para ayudarte
                </p>
            </div>
        </div>

        <!-- CONTENIDO PRINCIPAL: FORMULARIO + MAPA/HORARIOS -->
        <div class="container mx-auto px-4 py-12 lg:py-16">
            <div class="grid gap-8 lg:gap-12 lg:grid-cols-5 max-w-6xl mx-auto">
                
                <!-- FORMULARIO DE CONTACTO (3 columnas) -->
                <div class="lg:col-span-3 rounded-2xl bg-white dark:bg-gray-800 p-6 lg:p-8 shadow-xl border border-gray-200 dark:border-gray-700">
                    <div class="mb-6">
                        <h2 class="mb-2 text-xl lg:text-2xl font-bold text-green-900 dark:text-green-400">Envíanos un mensaje</h2>
                        <p class="text-sm lg:text-base text-gray-600 dark:text-gray-400">Completa el formulario y te responderemos pronto</p>
                    </div>

                    @if(session('success'))
                        <div class="mb-6 rounded-lg bg-green-100 dark:bg-green-900/30 border border-green-200 dark:border-green-700 p-4 flex items-center gap-3">
                            <span class="material-symbols-outlined text-green-700 dark:text-green-400">check_circle</span>
                            <p class="text-sm text-green-800 dark:text-green-300">{{ session('success') }}</p>
                        </div>
                    @endif

                    <form action="{{ route('contact.send') }}" method="POST" class="space-y-4">
                        @csrf

                        <!-- Nombre y Email en fila -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="name" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Nombre completo <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       name="name" 
                                       id="name" 
                                       value="{{ old('name') }}"
                                       required
                                       class="w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-4 py-2.5 focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-200 dark:focus:ring-green-800 transition"
                                       placeholder="Tu nombre">
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="email" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Correo electrónico <span class="text-red-500">*</span>
                                </label>
                                <input type="email" 
                                       name="email" 
                                       id="email" 
                                       value="{{ old('email') }}"
                                       required
                                       class="w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-4 py-2.5 focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-200 dark:focus:ring-green-800 transition"
                                       placeholder="tu@email.com">
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Teléfono y Asunto en fila -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="phone" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Teléfono <span class="text-gray-400 dark:text-gray-500">(opcional)</span>
                                </label>
                                <input type="tel" 
                                       name="phone" 
                                       id="phone" 
                                       value="{{ old('phone') }}"
                                       class="w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-4 py-2.5 focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-200 dark:focus:ring-green-800 transition"
                                       placeholder="0987654321">
                                @error('phone')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="subject" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Asunto <span class="text-red-500">*</span>
                                </label>
                                <select name="subject" 
                                        id="subject" 
                                        required
                                        class="w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-4 py-2.5 focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-200 dark:focus:ring-green-800 transition">
                                    <option value="">Selecciona un asunto</option>
                                    <option value="Información general" {{ old('subject') == 'Información general' ? 'selected' : '' }}>Información general</option>
                                    <option value="Agendar cita" {{ old('subject') == 'Agendar cita' ? 'selected' : '' }}>Agendar cita</option>
                                    <option value="Soporte técnico" {{ old('subject') == 'Soporte técnico' ? 'selected' : '' }}>Soporte técnico</option>
                                    <option value="Convertirme en nutricionista" {{ old('subject') == 'Convertirme en nutricionista' ? 'selected' : '' }}>Convertirme en nutricionista</option>
                                    <option value="Otro" {{ old('subject') == 'Otro' ? 'selected' : '' }}>Otro</option>
                                </select>
                                @error('subject')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Mensaje -->
                        <div>
                            <label for="message" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Mensaje <span class="text-red-500">*</span>
                            </label>
                            <textarea name="message" 
                                      id="message" 
                                      rows="4" 
                                      required
                                      class="w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-4 py-2.5 focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-200 dark:focus:ring-green-800 transition resize-none"
                                      placeholder="Escribe tu mensaje aquí...">{{ old('message') }}</textarea>
                            @error('message')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Botón de envío -->
                        <button type="submit" 
                                class="flex w-full items-center justify-center gap-2 rounded-lg bg-green-600 px-6 py-3 font-semibold text-white shadow-md transition-all hover:bg-green-700 hover:shadow-lg focus:outline-none focus:ring-4 focus:ring-green-300 dark:focus:ring-green-800">
                            <span class="material-symbols-outlined">send</span>
                            Enviar Mensaje
                        </button>
                    </form>
                </div>

                <!-- SIDEBAR: MAPA + HORARIOS (2 columnas) -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Mapa -->
                    @if($settings?->latitud && $settings?->longitud)
                    <div class="rounded-2xl bg-white dark:bg-gray-800 p-4 shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <div class="rounded-xl overflow-hidden">
                            <iframe 
                                src="https://www.google.com/maps/embed?pb=!1m17!1m12!1m3!1d3987.268689!2d{{ $settings->longitud }}!3d{{ $settings->latitud }}!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m2!1m1!2zMcKwMTInMTguNyJTIDgwwrAyMicyMC4zIlc!5e0!3m2!1ses!2sec" 
                                width="100%" 
                                height="200" 
                                style="border:0;" 
                                allowfullscreen="" 
                                loading="lazy" 
                                referrerpolicy="no-referrer-when-downgrade"
                                class="w-full">
                            </iframe>
                        </div>
                        <a href="{{ $settings->google_maps_url }}" 
                           target="_blank"
                           class="mt-3 flex items-center justify-center gap-2 text-sm text-green-600 dark:text-green-400 hover:text-green-700 dark:hover:text-green-300 transition font-medium">
                            <span class="material-symbols-outlined text-base">open_in_new</span>
                            Abrir en Google Maps
                        </a>
                    </div>
                    @endif

                    <!-- Horarios de atención -->
                    <div class="rounded-2xl bg-gradient-to-br from-green-50 to-emerald-50 dark:from-gray-800 dark:to-gray-800 p-6 shadow-xl border border-green-200 dark:border-gray-700">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-green-100 dark:bg-green-900/40">
                                <span class="material-symbols-outlined text-xl text-green-600 dark:text-green-400">schedule</span>
                            </div>
                            <h3 class="text-lg font-bold text-green-900 dark:text-green-400">Horarios</h3>
                        </div>
                        
                        <div class="space-y-2.5 text-sm">
                            <div class="flex justify-between py-2 border-b border-green-200 dark:border-gray-600">
                                <span class="text-gray-700 dark:text-gray-300">Lunes - Viernes</span>
                                <span class="font-medium text-gray-900 dark:text-white">9:00 - 18:00</span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-green-200 dark:border-gray-600">
                                <span class="text-gray-700 dark:text-gray-300">Sábado</span>
                                <span class="font-medium text-gray-900 dark:text-white">10:00 - 14:00</span>
                            </div>
                            <div class="flex justify-between py-2">
                                <span class="text-gray-700 dark:text-gray-300">Domingo</span>
                                <span class="font-medium text-red-600 dark:text-red-400">Cerrado</span>
                            </div>
                        </div>

                        <div class="mt-4 p-3 rounded-lg bg-white/70 dark:bg-gray-700/50 border border-green-100 dark:border-gray-600">
                            <p class="flex items-start gap-2 text-xs text-gray-600 dark:text-gray-400">
                                <span class="material-symbols-outlined text-green-500 text-base flex-shrink-0">info</span>
                                <span>Una vez registrado verás los horarios disponibles de cada nutricionista</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- TARJETAS DE CONTACTO RÁPIDO -->
        <div class="bg-gradient-to-b from-white to-gray-50 dark:from-gray-800 dark:to-gray-900 py-12">
            <div class="container mx-auto px-4">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 lg:gap-8 max-w-5xl mx-auto">
                    <!-- Email -->
                    @if($settings?->email_contacto)
                    <a href="mailto:{{ $settings->email_contacto }}" class="group flex flex-col items-center p-6 bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                        <div class="flex h-16 w-16 items-center justify-center rounded-full bg-green-100 dark:bg-green-900/40 mb-4 group-hover:scale-110 transition-transform">
                            <span class="material-symbols-outlined text-3xl text-green-600 dark:text-green-400">mail</span>
                        </div>
                        <h3 class="font-semibold text-gray-900 dark:text-white mb-2 text-lg">Email</h3>
                        <p class="text-sm text-green-600 dark:text-green-400 text-center font-medium">{{ $settings->email_contacto }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Respuesta en 24-48 horas</p>
                    </a>
                    @endif

                    <!-- WhatsApp -->
                    @if($settings?->telefono)
                    <a href="{{ $settings->whatsapp_url }}" target="_blank" class="group flex flex-col items-center p-6 bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                        <div class="flex h-16 w-16 items-center justify-center rounded-full bg-emerald-100 dark:bg-emerald-900/40 mb-4 group-hover:scale-110 transition-transform">
                            <span class="material-symbols-outlined text-3xl text-emerald-600 dark:text-emerald-400">chat</span>
                        </div>
                        <h3 class="font-semibold text-gray-900 dark:text-white mb-2 text-lg">WhatsApp</h3>
                        <p class="text-sm text-emerald-600 dark:text-emerald-400 font-medium">{{ $settings->telefono_formateado }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Atención inmediata</p>
                    </a>
                    @endif

                    <!-- Ubicación -->
                    @if($settings?->direccion && $settings?->google_maps_url)
                    <a href="{{ $settings->google_maps_url }}" target="_blank" class="group flex flex-col items-center p-6 bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                        <div class="flex h-16 w-16 items-center justify-center rounded-full bg-purple-100 dark:bg-purple-900/40 mb-4 group-hover:scale-110 transition-transform">
                            <span class="material-symbols-outlined text-3xl text-purple-600 dark:text-purple-400">location_on</span>
                        </div>
                        <h3 class="font-semibold text-gray-900 dark:text-white mb-2 text-lg">Ubicación</h3>
                        <p class="text-sm text-purple-600 dark:text-purple-400 text-center font-medium">{{ $settings->direccion }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Ver en el mapa</p>
                    </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- FAQ SECTION -->
        <div class="bg-white dark:bg-gray-800 py-12 lg:py-16">
            <div class="container mx-auto px-4">
                <div class="mb-8 lg:mb-12 text-center">
                    <span class="material-symbols-outlined mb-3 text-5xl lg:text-6xl text-green-600 dark:text-green-400">help</span>
                    <h2 class="mb-2 text-2xl lg:text-3xl font-bold text-green-900 dark:text-green-400">Preguntas Frecuentes</h2>
                    <p class="text-sm lg:text-base text-gray-600 dark:text-gray-400">Respuestas rápidas a las preguntas más comunes</p>
                </div>

                <div class="mx-auto max-w-3xl grid gap-3">
                    <details class="group rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50 px-5 py-4 shadow-sm transition-all duration-300 hover:shadow-md">
                        <summary class="flex cursor-pointer items-center justify-between font-semibold text-gray-900 dark:text-gray-100">
                            <span class="text-sm lg:text-base">¿Cómo puedo agendar una cita?</span>
                            <span class="material-symbols-outlined transition group-open:rotate-180 text-green-600 dark:text-green-400">expand_more</span>
                        </summary>
                        <p class="mt-3 text-sm text-gray-600 dark:text-gray-300 leading-relaxed">
                            Una vez registrado en la plataforma, podrás acceder al panel de citas donde podrás ver la disponibilidad 
                            de nuestros nutricionistas y agendar tu consulta en el horario que mejor te convenga.
                        </p>
                    </details>

                    <details class="group rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50 px-5 py-4 shadow-sm transition-all duration-300 hover:shadow-md">
                        <summary class="flex cursor-pointer items-center justify-between font-semibold text-gray-900 dark:text-gray-100">
                            <span class="text-sm lg:text-base">¿Las consultas son presenciales o virtuales?</span>
                            <span class="material-symbols-outlined transition group-open:rotate-180 text-green-600 dark:text-green-400">expand_more</span>
                        </summary>
                        <p class="mt-3 text-sm text-gray-600 dark:text-gray-300 leading-relaxed">
                            Ofrecemos ambas modalidades. Puedes elegir consultas virtuales a través de videollamada o presenciales 
                            en nuestras instalaciones, según tu preferencia y comodidad.
                        </p>
                    </details>

                    <details class="group rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50 px-5 py-4 shadow-sm transition-all duration-300 hover:shadow-md">
                        <summary class="flex cursor-pointer items-center justify-between font-semibold text-gray-900 dark:text-gray-100">
                            <span class="text-sm lg:text-base">¿Cuál es el costo de las consultas?</span>
                            <span class="material-symbols-outlined transition group-open:rotate-180 text-green-600 dark:text-green-400">expand_more</span>
                        </summary>
                        <p class="mt-3 text-sm text-gray-600 dark:text-gray-300 leading-relaxed">
                            Los costos varían según el tipo de consulta y el nutricionista. Puedes ver los precios detallados 
                            en el perfil de cada profesional antes de agendar tu cita.
                        </p>
                    </details>

                    <details class="group rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50 px-5 py-4 shadow-sm transition-all duration-300 hover:shadow-md">
                        <summary class="flex cursor-pointer items-center justify-between font-semibold text-gray-900 dark:text-gray-100">
                            <span class="text-sm lg:text-base">¿Puedo cancelar o reprogramar mi cita?</span>
                            <span class="material-symbols-outlined transition group-open:rotate-180 text-green-600 dark:text-green-400">expand_more</span>
                        </summary>
                        <p class="mt-3 text-sm text-gray-600 dark:text-gray-300 leading-relaxed">
                            Sí, puedes cancelar o reprogramar tu cita con al menos 24 horas de anticipación sin cargo adicional. 
                            Puedes hacerlo directamente desde tu panel de usuario.
                        </p>
                    </details>
                </div>
            </div>
        </div>
    </section>

    @include('layouts.footer')

    <style>
        /* Animación para las preguntas frecuentes */
        details summary {
            list-style: none;
        }
        
        details summary::-webkit-details-marker {
            display: none;
        }
        
        details > summary {
            cursor: pointer;
        }
        
        details p {
            animation: fadeIn 0.3s ease-in-out;
        }
        
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Animación suave para la rotación del icono */
        details summary .material-symbols-outlined {
            transition: transform 0.3s ease-in-out;
        }
        
        details[open] summary .material-symbols-outlined {
            transform: rotate(180deg);
        }
    </style>

</body>
@endsection