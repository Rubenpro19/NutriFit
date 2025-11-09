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
            <div class="grid gap-12 lg:grid-cols-2">
                
                <!-- FORMULARIO DE CONTACTO -->
                <div class="rounded-2xl bg-white dark:bg-gray-800 p-8 shadow-xl border border-gray-200 dark:border-gray-700 dark:border-gray-700">
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
                    <div class="rounded-2xl bg-white dark:bg-gray-800 p-8 shadow-xl">
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
                                        contacto@nutrifit.ec
                                    </a>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Respuesta en 24-48 horas</p>
                                </div>
                            </div>

                            <!-- Teléfono -->
                            <div class="flex items-start gap-4">
                                <div class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-blue-100 dark:bg-blue-900/30">
                                    <span class="material-symbols-outlined text-2xl text-blue-700 dark:text-blue-400">phone</span>
                                </div>
                                <div>
                                    <h3 class="mb-1 font-semibold text-gray-900 dark:text-gray-100">Teléfono</h3>
                                    <a href="tel:+593987654321" class="text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300">
                                        +593 98 765 4321
                                    </a>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Lun - Vie: 9:00 AM - 6:00 PM</p>
                                </div>
                            </div>

                            <!-- WhatsApp -->
                            <div class="flex items-start gap-4">
                                <div class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-emerald-100 dark:bg-emerald-900/30">
                                    <span class="material-symbols-outlined text-2xl text-emerald-700 dark:text-emerald-400">chat</span>
                                </div>
                                <div>
                                    <h3 class="mb-1 font-semibold text-gray-900 dark:text-gray-100">WhatsApp</h3>
                                    <a href="https://wa.me/593987654321" target="_blank" class="text-emerald-600 dark:text-emerald-400 hover:text-emerald-700 dark:hover:text-emerald-300">
                                        +593 98 765 4321
                                    </a>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Atención inmediata</p>
                                </div>
                            </div>

                            <!-- Ubicación -->
                            <div class="flex items-start gap-4">
                                <div class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-purple-100 dark:bg-purple-900/30">
                                    <span class="material-symbols-outlined text-2xl text-purple-700 dark:text-purple-400">location_on</span>
                                </div>
                                <div>
                                    <h3 class="mb-1 font-semibold text-gray-900 dark:text-gray-100">Ubicación</h3>
                                    <p class="text-gray-700 dark:text-gray-300">Santa Ana, Manabí, Ecuador</p>
                                    {{-- <p class="text-sm text-gray-600 dark:text-gray-400">Consultas virtuales disponibles</p> --}}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Horarios de atención -->
                    <div class="rounded-2xl bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/30 dark:to-green-800/30 dark:border dark:border-green-700 p-8 shadow-xl">
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
                    <details class="group rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-6 shadow-sm transition hover:shadow-md">
                        <summary class="flex cursor-pointer items-center justify-between font-semibold text-gray-900 dark:text-gray-100">
                            <span>¿Cómo puedo agendar una cita?</span>
                            <span class="material-symbols-outlined transition group-open:rotate-180 text-gray-700 dark:text-gray-300">expand_more</span>
                        </summary>
                        <p class="mt-4 text-gray-600 dark:text-gray-300">
                            Una vez registrado en la plataforma, podrás acceder al panel de citas donde podrás ver la disponibilidad 
                            de nuestros nutricionistas y agendar tu consulta en el horario que mejor te convenga.
                        </p>
                    </details>

                    <details class="group rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-6 shadow-sm transition hover:shadow-md">
                        <summary class="flex cursor-pointer items-center justify-between font-semibold text-gray-900 dark:text-gray-100">
                            <span>¿Las consultas son presenciales o virtuales?</span>
                            <span class="material-symbols-outlined transition group-open:rotate-180 text-gray-700 dark:text-gray-300">expand_more</span>
                        </summary>
                        <p class="mt-4 text-gray-600 dark:text-gray-300">
                            Ofrecemos ambas modalidades. Puedes elegir consultas virtuales a través de videollamada o presenciales 
                            en nuestras instalaciones, según tu preferencia y comodidad.
                        </p>
                    </details>

                    <details class="group rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-6 shadow-sm transition hover:shadow-md">
                        <summary class="flex cursor-pointer items-center justify-between font-semibold text-gray-900 dark:text-gray-100">
                            <span>¿Cuál es el costo de las consultas?</span>
                            <span class="material-symbols-outlined transition group-open:rotate-180 text-gray-700 dark:text-gray-300">expand_more</span>
                        </summary>
                        <p class="mt-4 text-gray-600 dark:text-gray-300">
                            Los costos varían según el tipo de consulta y el nutricionista. Puedes ver los precios detallados 
                            en el perfil de cada profesional antes de agendar tu cita.
                        </p>
                    </details>

                    <details class="group rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-6 shadow-sm transition hover:shadow-md">
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

</body>
@endsection


