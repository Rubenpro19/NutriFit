@extends('layouts.app')

@section('title', 'Contacto - NutriFit')

@section('content')
<body class="min-h-screen flex flex-col bg-gradient-to-br from-[#e9fcd9] to-[#d4f4dd] text-gray-900 dark:from-gray-900 dark:to-gray-800 dark:text-white">

    @include('layouts.header')

    <!-- HERO SECTION -->
    <section class="flex-grow">
        <div class="relative overflow-hidden bg-gradient-to-r from-green-600 to-green-800 py-16 text-white">
            <div class="container mx-auto px-4 text-center">
                <h1 class="mb-4 text-4xl font-bold lg:text-5xl">Contáctanos</h1>
                <p class="mx-auto max-w-2xl text-lg text-green-100">
                    ¿Tienes preguntas? Estamos aquí para ayudarte. Envíanos un mensaje y te responderemos lo antes posible
                </p>
            </div>
        </div>

        <!-- CONTENIDO PRINCIPAL -->
        <div class="container mx-auto px-4 py-16">
            <div class="grid gap-12 lg:grid-cols-2 lg:items-start">
                
                <!-- FORMULARIO DE CONTACTO -->
                <div class="rounded-2xl bg-white dark:bg-gray-800 p-8 shadow-xl border border-gray-200 dark:border-gray-700 h-fit">
                    <div class="mb-6">
                        <h2 class="mb-2 text-2xl font-bold text-green-900 dark:text-green-400 dark:text-green-400">Envíanos un mensaje</h2>
                        <p class="text-gray-600 dark:text-gray-400 dark:text-gray-400">Completa el formulario y nos pondremos en contacto contigo</p>
                    </div>

                    @if(session('success'))
                        <div class="mb-6 rounded-lg bg-green-100 border border-green-200 p-4 flex items-center gap-3">
                            <span class="material-symbols-outlined text-green-700">check_circle</span>
                            <p class="text-sm text-green-800">{{ session('success') }}</p>
                        </div>
                    @endif

                    <form action="{{ route('contact.send') }}" method="POST" class="space-y-5">
                        @csrf

                        <!-- Nombre -->
                        <div>
                            <label for="name" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300 dark:text-gray-300">
                                Nombre completo <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="name" 
                                   id="name" 
                                   value="{{ old('name') }}"
                                   required
                                   class="w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-4 py-3 focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-200"
                                   placeholder="Tu nombre completo">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300 dark:text-gray-300">
                                Correo electrónico <span class="text-red-500">*</span>
                            </label>
                            <input type="email" 
                                   name="email" 
                                   id="email" 
                                   value="{{ old('email') }}"
                                   required
                                   class="w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-4 py-3 focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-200"
                                   placeholder="tu@email.com">
                            @error('email')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Teléfono -->
                        <div>
                            <label for="phone" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300 dark:text-gray-300">
                                Teléfono <span class="text-gray-400 dark:text-gray-500">(opcional)</span>
                            </label>
                            <input type="tel" 
                                   name="phone" 
                                   id="phone" 
                                   value="{{ old('phone') }}"
                                   class="w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-4 py-3 focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-200"
                                   placeholder="0987654321">
                            @error('phone')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Asunto -->
                        <div>
                            <label for="subject" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Asunto <span class="text-red-500">*</span>
                            </label>
                            <select name="subject" 
                                    id="subject" 
                                    required
                                    class="w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-4 py-3 focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-200">
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

                        <!-- Mensaje -->
                        <div>
                            <label for="message" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Mensaje <span class="text-red-500">*</span>
                            </label>
                            <textarea name="message" 
                                      id="message" 
                                      rows="5" 
                                      required
                                      class="w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-4 py-3 focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-200"
                                      placeholder="Escribe tu mensaje aquí...">{{ old('message') }}</textarea>
                            @error('message')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Botón de envío -->
                        <button type="submit" 
                                class="flex w-full items-center justify-center gap-2 rounded-lg bg-green-600 px-6 py-3 font-semibold text-white shadow-md transition hover:bg-green-700 focus:outline-none focus:ring-4 focus:ring-green-300">
                            <span class="material-symbols-outlined">send</span>
                            Enviar Mensaje
                        </button>
                    </form>
                </div>

                <!-- INFORMACIÓN DE CONTACTO -->
                <div class="space-y-8">
                    <!-- Información de contacto -->
                    <div class="rounded-2xl bg-white dark:bg-gray-800 p-8 shadow-xl transition-all duration-300 transform hover:scale-105 hover:-translate-y-2">
                        <h2 class="mb-6 text-2xl font-bold text-green-900 dark:text-green-400">Información de Contacto</h2>
                        
                        <div class="space-y-6">
                            <!-- Email -->
                            <div class="flex items-start gap-4">
                                <div class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-green-100 dark:bg-green-900/30">
                                    <span class="material-symbols-outlined text-2xl text-green-700 dark:text-green-400">mail</span>
                                </div>
                                <div>
                                    <h3 class="mb-1 font-semibold text-gray-900 dark:text-gray-100">Email</h3>
                                    <a href="mailto:contacto@nutrifit.ec" class="text-green-600 dark:text-green-400 hover:text-green-700 dark:hover:text-green-300">
                                        nutifit2026@gmail.com
                                    </a>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Respuesta en 24-48 horas</p>
                                </div>
                            </div>

                            <!-- WhatsApp -->
                            <div class="flex items-start gap-4">
                                <div class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-emerald-100 dark:bg-emerald-900/30">
                                    <span class="material-symbols-outlined text-2xl text-emerald-700 dark:text-emerald-400">chat</span>
                                </div>
                                <div>
                                    <h3 class="mb-1 font-semibold text-gray-900 dark:text-gray-100">WhatsApp</h3>
                                    <a href="https://wa.me/593984668389" target="_blank" class="text-emerald-600 dark:text-emerald-400 hover:text-emerald-700 dark:hover:text-emerald-300">
                                        +593 98 466 8389
                                    </a>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Atención inmediata</p>
                                </div>
                            </div>

                            <!-- Ubicación -->
                            <div class="flex items-start gap-4">
                                <div class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-purple-100 dark:bg-purple-900/30">
                                    <span class="material-symbols-outlined text-2xl text-purple-700 dark:text-purple-400">location_on</span>
                                </div>
                                <div class="flex-1">
                                    <h3 class="mb-1 font-semibold text-gray-900 dark:text-gray-100">Ubicación</h3>
                                    <p class="text-gray-700 dark:text-gray-300 mb-3">Santa Ana, Manabí, Ecuador</p>
                                    
                                    <!-- Mapa Embebido -->
                                    <div class="rounded-lg overflow-hidden border-2 border-purple-200 dark:border-purple-700 shadow-md">
                                        <iframe 
                                            src="https://www.google.com/maps/embed?pb=!1m17!1m12!1m3!1d3987.268689!2d-80.372294!3d-1.205192!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m2!1m1!2zMcKwMTInMTguNyJTIDgwwrAyMicyMC4zIlc!5e0!3m2!1ses!2sec!4v1705000000000!5m2!1ses!2sec" 
                                            width="100%" 
                                            height="250" 
                                            style="border:0;" 
                                            allowfullscreen="" 
                                            loading="lazy" 
                                            referrerpolicy="no-referrer-when-downgrade"
                                            class="w-full">
                                        </iframe>
                                    </div>
                                    
                                    <a href="https://www.google.com/maps?q=-1.205192,-80.372294" 
                                       target="_blank"
                                       class="mt-2 inline-flex items-center gap-1 text-sm text-purple-600 dark:text-purple-400 hover:text-purple-700 dark:hover:text-purple-300 transition">
                                        <span class="material-symbols-outlined text-base">open_in_new</span>
                                        Ver en Google Maps
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Horarios de atención -->
                    <div class="rounded-2xl bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/30 dark:to-green-800/30 dark:border dark:border-green-700 p-8 shadow-xl transition-all duration-300 transform hover:scale-105 hover:-translate-y-2">
                        <div class="mb-4 flex items-center gap-3">
                            <span class="material-symbols-outlined text-3xl text-green-700 dark:text-green-400">schedule</span>
                            <h2 class="text-2xl font-bold text-green-900 dark:text-green-400">Horarios de Atención</h2>
                        </div>
                        
                        <div class="space-y-3">
                            <div class="flex justify-between border-b border-green-200 dark:border-green-700 pb-2">
                                <span class="font-medium text-gray-700 dark:text-gray-300">Lunes - Viernes</span>
                                <span class="text-gray-900 dark:text-gray-100">9:00 AM - 6:00 PM</span>
                            </div>
                            <div class="flex justify-between border-b border-green-200 dark:border-green-700 pb-2">
                                <span class="font-medium text-gray-700 dark:text-gray-300">Sábado</span>
                                <span class="text-gray-900 dark:text-gray-100">10:00 AM - 2:00 PM</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-medium text-gray-700 dark:text-gray-300">Domingo</span>
                                <span class="text-red-600 dark:text-red-400">Cerrado</span>
                            </div>
                        </div>

                        <div class="mt-6 rounded-lg bg-white dark:bg-gray-800 dark:border dark:border-gray-700 p-4">
                            <p class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                                <span class="material-symbols-outlined text-green-600 dark:text-green-400">info</span>
                                <span>Una vez registrado podrás observar los horarios disponibles de cada nutricionista</span>
                            </p>
                        </div>
                    </div>

                    {{-- <!-- Redes Sociales -->
                    <div class="rounded-2xl bg-white dark:bg-gray-800 p-8 shadow-xl">
                        <h2 class="mb-6 text-2xl font-bold text-gray-900">Síguenos</h2>
                        <div class="flex gap-4">
                            <a href="#" class="flex h-12 w-12 items-center justify-center rounded-full bg-blue-600 text-white transition hover:bg-blue-700">
                                <span class="material-symbols-outlined">thumb_up</span>
                            </a>
                            <a href="#" class="flex h-12 w-12 items-center justify-center rounded-full bg-sky-500 text-white transition hover:bg-sky-600">
                                <span class="material-symbols-outlined">chat_bubble</span>
                            </a>
                            <a href="#" class="flex h-12 w-12 items-center justify-center rounded-full bg-pink-600 text-white transition hover:bg-pink-700">
                                <span class="material-symbols-outlined">photo_camera</span>
                            </a>
                            <a href="#" class="flex h-12 w-12 items-center justify-center rounded-full bg-red-600 text-white transition hover:bg-red-700">
                                <span class="material-symbols-outlined">play_arrow</span>
                            </a>
                        </div>
                    </div> --}}
                </div>
            </div>
        </div>

        <!-- FAQ SECTION -->
        <div class="bg-white dark:bg-gray-800 py-16">
            <div class="container mx-auto px-4">
                <div class="mb-12 text-center">
                    <span class="material-symbols-outlined mb-4 text-6xl text-green-700 dark:text-green-400">help</span>
                    <h2 class="mb-4 text-3xl font-bold text-green-900 dark:text-green-400">Preguntas Frecuentes</h2>
                    <p class="text-gray-600 dark:text-gray-400">Respuestas rápidas a las preguntas más comunes</p>
                </div>

                <div class="mx-auto max-w-3xl space-y-4">
                    <details class="group rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-6 shadow-sm transition-all duration-300">
                        <summary class="flex cursor-pointer items-center justify-between font-semibold text-gray-900 dark:text-gray-100">
                            <span>¿Cómo puedo agendar una cita?</span>
                            <span class="material-symbols-outlined transition group-open:rotate-180 text-gray-700 dark:text-gray-300">expand_more</span>
                        </summary>
                        <p class="mt-4 text-gray-600 dark:text-gray-300">
                            Una vez registrado en la plataforma, podrás acceder al panel de citas donde podrás ver la disponibilidad 
                            de nuestros nutricionistas y agendar tu consulta en el horario que mejor te convenga.
                        </p>
                    </details>

                    <details class="group rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-6 shadow-sm transition-all duration-300">
                        <summary class="flex cursor-pointer items-center justify-between font-semibold text-gray-900 dark:text-gray-100">
                            <span>¿Las consultas son presenciales o virtuales?</span>
                            <span class="material-symbols-outlined transition group-open:rotate-180 text-gray-700 dark:text-gray-300">expand_more</span>
                        </summary>
                        <p class="mt-4 text-gray-600 dark:text-gray-300">
                            Ofrecemos ambas modalidades. Puedes elegir consultas virtuales a través de videollamada o presenciales 
                            en nuestras instalaciones, según tu preferencia y comodidad.
                        </p>
                    </details>

                    <details class="group rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-6 shadow-sm transition-all duration-300">
                        <summary class="flex cursor-pointer items-center justify-between font-semibold text-gray-900 dark:text-gray-100">
                            <span>¿Cuál es el costo de las consultas?</span>
                            <span class="material-symbols-outlined transition group-open:rotate-180 text-gray-700 dark:text-gray-300">expand_more</span>
                        </summary>
                        <p class="mt-4 text-gray-600 dark:text-gray-300">
                            Los costos varían según el tipo de consulta y el nutricionista. Puedes ver los precios detallados 
                            en el perfil de cada profesional antes de agendar tu cita.
                        </p>
                    </details>

                    <details class="group rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-6 shadow-sm transition-all duration-300">
                        <summary class="flex cursor-pointer items-center justify-between font-semibold text-gray-900 dark:text-gray-100">
                            <span>¿Puedo cancelar o reprogramar mi cita?</span>
                            <span class="material-symbols-outlined transition group-open:rotate-180 text-gray-700 dark:text-gray-300">expand_more</span>
                        </summary>
                        <p class="mt-4 text-gray-600 dark:text-gray-300">
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


