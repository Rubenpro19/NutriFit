@extends('layouts.app')

@section('title', 'Política de Privacidad - NutriFit')

@section('content')
<body class="min-h-screen flex flex-col bg-gradient-to-br from-[#e9fcd9] to-[#d4f4dd] text-gray-900 dark:from-gray-900 dark:to-gray-800 dark:text-white">

    @include('layouts.header')

    <!-- HERO SECTION -->
    <section class="flex-grow">
        <div class="relative overflow-hidden bg-gradient-to-r from-green-600 to-green-800 py-16 text-white">
            <div class="container mx-auto px-4 text-center">
                <span class="material-symbols-outlined mb-4 text-6xl">shield</span>
                <h1 class="mb-4 text-4xl font-bold lg:text-5xl">Política de Privacidad</h1>
                <p class="mx-auto max-w-2xl text-lg text-green-100">
                    Tu privacidad es importante para nosotros. Conoce cómo protegemos y utilizamos tus datos.
                </p>
                <p class="mt-4 text-sm text-green-200">
                    Última actualización: {{ now()->format('d/m/Y') }}
                </p>
            </div>
        </div>

        <!-- CONTENIDO PRINCIPAL -->
        <div class="container mx-auto px-4 py-16">
            <div class="mx-auto max-w-4xl space-y-12">

                <!-- RESPONSABLE DEL TRATAMIENTO -->
                <div class="rounded-2xl bg-white dark:bg-gray-800 p-8 shadow-lg border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-green-100 dark:bg-green-900/30">
                            <span class="material-symbols-outlined text-2xl text-green-700 dark:text-green-400">business</span>
                        </div>
                        <h2 class="text-2xl font-bold text-green-900 dark:text-green-400">1. Responsable del Tratamiento</h2>
                    </div>
                    <div class="space-y-4 text-gray-700 dark:text-gray-300">
                        <p>
                            <strong>NutriFit</strong> es el responsable del tratamiento de los datos personales que nos proporciones 
                            a través de nuestra plataforma.
                        </p>
                        @php
                            $settings = system_settings();
                        @endphp
                        <div class="mt-4 rounded-lg bg-gray-50 dark:bg-gray-700/50 p-4">
                            <ul class="space-y-2">
                                @if($settings?->email_contacto)
                                <li class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-green-600 dark:text-green-400">mail</span>
                                    <span><strong>Correo electrónico:</strong> {{ $settings->email_contacto }}</span>
                                </li>
                                @endif
                                @if($settings?->telefono)
                                <li class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-green-600 dark:text-green-400">phone</span>
                                    <span><strong>Teléfono:</strong> {{ $settings->telefono_formateado ?? $settings->telefono }}</span>
                                </li>
                                @endif
                                @if($settings?->direccion)
                                <li class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-green-600 dark:text-green-400">location_on</span>
                                    <span><strong>Dirección:</strong> {{ $settings->direccion }}</span>
                                </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- DATOS QUE RECOPILAMOS -->
                <div class="rounded-2xl bg-white dark:bg-gray-800 p-8 shadow-lg border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-blue-100 dark:bg-blue-900/30">
                            <span class="material-symbols-outlined text-2xl text-blue-700 dark:text-blue-400">database</span>
                        </div>
                        <h2 class="text-2xl font-bold text-blue-900 dark:text-blue-400">2. Datos que Recopilamos</h2>
                    </div>
                    <div class="space-y-6 text-gray-700 dark:text-gray-300">
                        <p>
                            En NutriFit recopilamos diferentes tipos de información personal para brindarte nuestros servicios 
                            de gestión de consultas nutricionales:
                        </p>

                        <!-- Datos de Registro -->
                        <div class="rounded-lg border border-gray-200 dark:border-gray-600 p-4">
                            <h3 class="flex items-center gap-2 text-lg font-semibold text-gray-900 dark:text-white mb-3">
                                <span class="material-symbols-outlined text-green-600">person_add</span>
                                Datos de Registro y Cuenta
                            </h3>
                            <ul class="list-disc list-inside space-y-1 ml-4 text-sm">
                                <li>Nombre completo</li>
                                <li>Correo electrónico</li>
                                <li>Contraseña (almacenada de forma encriptada)</li>
                                <li>Número de cédula o documento de identidad</li>
                                <li>Número de teléfono</li>
                                <li>Dirección</li>
                                <li>Fecha de nacimiento</li>
                                <li>Género</li>
                                <li>Fotografía de perfil (opcional)</li>
                            </ul>
                        </div>

                        <!-- Datos de Salud -->
                        <div class="rounded-lg border border-orange-200 dark:border-orange-600/50 bg-orange-50 dark:bg-orange-900/20 p-4">
                            <h3 class="flex items-center gap-2 text-lg font-semibold text-orange-900 dark:text-orange-400 mb-3">
                                <span class="material-symbols-outlined text-orange-600">health_and_safety</span>
                                Datos de Salud y Nutricionales
                            </h3>
                            <p class="text-sm text-orange-800 dark:text-orange-200 mb-3">
                                <strong>Datos sensibles:</strong> Esta información es tratada con especial protección debido a su naturaleza médica.
                            </p>
                            <ul class="list-disc list-inside space-y-1 ml-4 text-sm">
                                <li>Peso y altura</li>
                                <li>Medidas corporales (cintura, cadera, cuello, muñeca, brazo, muslo, pantorrilla)</li>
                                <li>Índice de masa corporal (IMC)</li>
                                <li>Porcentaje de grasa corporal</li>
                                <li>Tasa metabólica basal y gasto energético</li>
                                <li>Nivel de actividad física</li>
                                <li>Objetivos nutricionales</li>
                                <li>Diagnósticos y recomendaciones del nutricionista</li>
                                <li>Plan de alimentación y distribución de macronutrientes</li>
                            </ul>
                        </div>

                        <!-- Datos de Citas -->
                        <div class="rounded-lg border border-gray-200 dark:border-gray-600 p-4">
                            <h3 class="flex items-center gap-2 text-lg font-semibold text-gray-900 dark:text-white mb-3">
                                <span class="material-symbols-outlined text-purple-600">calendar_month</span>
                                Datos de Citas y Consultas
                            </h3>
                            <ul class="list-disc list-inside space-y-1 ml-4 text-sm">
                                <li>Fecha y hora de citas programadas</li>
                                <li>Tipo de consulta (primera vez, seguimiento, control)</li>
                                <li>Motivo de la consulta</li>
                                <li>Notas adicionales</li>
                                <li>Historial de citas anteriores</li>
                            </ul>
                        </div>

                        <!-- Datos Técnicos -->
                        <div class="rounded-lg border border-gray-200 dark:border-gray-600 p-4">
                            <h3 class="flex items-center gap-2 text-lg font-semibold text-gray-900 dark:text-white mb-3">
                                <span class="material-symbols-outlined text-gray-600">devices</span>
                                Datos Técnicos y de Sesión
                            </h3>
                            <ul class="list-disc list-inside space-y-1 ml-4 text-sm">
                                <li>Dirección IP</li>
                                <li>Información del navegador (user agent)</li>
                                <li>Datos de sesión y actividad en la plataforma</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- FINALIDAD DEL TRATAMIENTO -->
                <div class="rounded-2xl bg-white dark:bg-gray-800 p-8 shadow-lg border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-purple-100 dark:bg-purple-900/30">
                            <span class="material-symbols-outlined text-2xl text-purple-700 dark:text-purple-400">target</span>
                        </div>
                        <h2 class="text-2xl font-bold text-purple-900 dark:text-purple-400">3. Finalidad del Tratamiento</h2>
                    </div>
                    <div class="space-y-4 text-gray-700 dark:text-gray-300">
                        <p>Utilizamos tus datos personales para las siguientes finalidades:</p>
                        
                        <div class="grid gap-4 md:grid-cols-2">
                            <div class="flex items-start gap-3 rounded-lg bg-green-50 dark:bg-green-900/20 p-4">
                                <span class="material-symbols-outlined text-green-600 mt-1">check_circle</span>
                                <div>
                                    <h4 class="font-semibold text-green-900 dark:text-green-400">Gestión de cuenta</h4>
                                    <p class="text-sm">Crear y administrar tu cuenta de usuario en la plataforma.</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3 rounded-lg bg-green-50 dark:bg-green-900/20 p-4">
                                <span class="material-symbols-outlined text-green-600 mt-1">check_circle</span>
                                <div>
                                    <h4 class="font-semibold text-green-900 dark:text-green-400">Agendamiento de citas</h4>
                                    <p class="text-sm">Gestionar y coordinar tus citas con profesionales de nutrición.</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3 rounded-lg bg-green-50 dark:bg-green-900/20 p-4">
                                <span class="material-symbols-outlined text-green-600 mt-1">check_circle</span>
                                <div>
                                    <h4 class="font-semibold text-green-900 dark:text-green-400">Historial clínico</h4>
                                    <p class="text-sm">Mantener un registro de tu evolución nutricional y datos de salud.</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3 rounded-lg bg-green-50 dark:bg-green-900/20 p-4">
                                <span class="material-symbols-outlined text-green-600 mt-1">check_circle</span>
                                <div>
                                    <h4 class="font-semibold text-green-900 dark:text-green-400">Comunicaciones</h4>
                                    <p class="text-sm">Enviarte recordatorios de citas y notificaciones importantes.</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3 rounded-lg bg-green-50 dark:bg-green-900/20 p-4">
                                <span class="material-symbols-outlined text-green-600 mt-1">check_circle</span>
                                <div>
                                    <h4 class="font-semibold text-green-900 dark:text-green-400">Atención nutricional</h4>
                                    <p class="text-sm">Permitir a los nutricionistas brindarte un servicio personalizado.</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3 rounded-lg bg-green-50 dark:bg-green-900/20 p-4">
                                <span class="material-symbols-outlined text-green-600 mt-1">check_circle</span>
                                <div>
                                    <h4 class="font-semibold text-green-900 dark:text-green-400">Seguridad</h4>
                                    <p class="text-sm">Proteger tu cuenta y prevenir accesos no autorizados.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- BASE LEGAL -->
                <div class="rounded-2xl bg-white dark:bg-gray-800 p-8 shadow-lg border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-indigo-100 dark:bg-indigo-900/30">
                            <span class="material-symbols-outlined text-2xl text-indigo-700 dark:text-indigo-400">gavel</span>
                        </div>
                        <h2 class="text-2xl font-bold text-indigo-900 dark:text-indigo-400">4. Base Legal del Tratamiento</h2>
                    </div>
                    <div class="space-y-4 text-gray-700 dark:text-gray-300">
                        <p>El tratamiento de tus datos personales se fundamenta en:</p>
                        <ul class="space-y-3">
                            <li class="flex items-start gap-3">
                                <span class="material-symbols-outlined text-indigo-600 mt-1">arrow_right</span>
                                <span><strong>Tu consentimiento:</strong> Al registrarte y utilizar nuestra plataforma, aceptas el tratamiento de tus datos conforme a esta política.</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="material-symbols-outlined text-indigo-600 mt-1">arrow_right</span>
                                <span><strong>Ejecución del servicio:</strong> El tratamiento es necesario para prestarte los servicios de gestión de consultas nutricionales.</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="material-symbols-outlined text-indigo-600 mt-1">arrow_right</span>
                                <span><strong>Consentimiento explícito para datos de salud:</strong> Los datos sensibles relacionados con tu salud son tratados únicamente con tu consentimiento expreso.</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- DERECHOS DEL USUARIO -->
                <div class="rounded-2xl bg-white dark:bg-gray-800 p-8 shadow-lg border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-teal-100 dark:bg-teal-900/30">
                            <span class="material-symbols-outlined text-2xl text-teal-700 dark:text-teal-400">verified_user</span>
                        </div>
                        <h2 class="text-2xl font-bold text-teal-900 dark:text-teal-400">5. Tus Derechos</h2>
                    </div>
                    <div class="space-y-4 text-gray-700 dark:text-gray-300">
                        <p>Como titular de tus datos personales, tienes los siguientes derechos:</p>
                        
                        <div class="grid gap-4 md:grid-cols-2">
                            <div class="rounded-lg border border-teal-200 dark:border-teal-600/50 p-4 hover:shadow-md transition-shadow">
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="material-symbols-outlined text-teal-600">visibility</span>
                                    <h4 class="font-semibold text-teal-900 dark:text-teal-400">Derecho de Acceso</h4>
                                </div>
                                <p class="text-sm">Conocer qué datos personales tenemos sobre ti y cómo los utilizamos.</p>
                            </div>
                            <div class="rounded-lg border border-teal-200 dark:border-teal-600/50 p-4 hover:shadow-md transition-shadow">
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="material-symbols-outlined text-teal-600">edit</span>
                                    <h4 class="font-semibold text-teal-900 dark:text-teal-400">Derecho de Rectificación</h4>
                                </div>
                                <p class="text-sm">Solicitar la corrección de datos inexactos o incompletos.</p>
                            </div>
                            <div class="rounded-lg border border-teal-200 dark:border-teal-600/50 p-4 hover:shadow-md transition-shadow">
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="material-symbols-outlined text-teal-600">delete</span>
                                    <h4 class="font-semibold text-teal-900 dark:text-teal-400">Derecho de Supresión</h4>
                                </div>
                                <p class="text-sm">Solicitar la eliminación de tus datos cuando ya no sean necesarios.</p>
                            </div>
                            <div class="rounded-lg border border-teal-200 dark:border-teal-600/50 p-4 hover:shadow-md transition-shadow">
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="material-symbols-outlined text-teal-600">block</span>
                                    <h4 class="font-semibold text-teal-900 dark:text-teal-400">Derecho de Oposición</h4>
                                </div>
                                <p class="text-sm">Oponerte al tratamiento de tus datos en determinadas circunstancias.</p>
                            </div>
                            <div class="rounded-lg border border-teal-200 dark:border-teal-600/50 p-4 hover:shadow-md transition-shadow">
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="material-symbols-outlined text-teal-600">pause_circle</span>
                                    <h4 class="font-semibold text-teal-900 dark:text-teal-400">Derecho de Limitación</h4>
                                </div>
                                <p class="text-sm">Solicitar la limitación del tratamiento de tus datos.</p>
                            </div>
                            <div class="rounded-lg border border-teal-200 dark:border-teal-600/50 p-4 hover:shadow-md transition-shadow">
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="material-symbols-outlined text-teal-600">download</span>
                                    <h4 class="font-semibold text-teal-900 dark:text-teal-400">Derecho de Portabilidad</h4>
                                </div>
                                <p class="text-sm">Recibir tus datos en un formato estructurado y de uso común.</p>
                            </div>
                        </div>

                        <div class="mt-6 rounded-lg bg-teal-50 dark:bg-teal-900/20 p-4 border border-teal-200 dark:border-teal-600/50">
                            <p class="text-sm">
                                <strong>¿Cómo ejercer tus derechos?</strong> Puedes ejercer cualquiera de estos derechos 
                                contactándonos a través de los medios indicados en la sección de contacto. Responderemos 
                                a tu solicitud en un plazo máximo de 30 días.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- SEGURIDAD DE LOS DATOS -->
                <div class="rounded-2xl bg-white dark:bg-gray-800 p-8 shadow-lg border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-red-100 dark:bg-red-900/30">
                            <span class="material-symbols-outlined text-2xl text-red-700 dark:text-red-400">lock</span>
                        </div>
                        <h2 class="text-2xl font-bold text-red-900 dark:text-red-400">6. Seguridad de los Datos</h2>
                    </div>
                    <div class="space-y-4 text-gray-700 dark:text-gray-300">
                        <p>Implementamos medidas de seguridad técnicas y organizativas para proteger tus datos:</p>
                        <ul class="space-y-2">
                            <li class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-red-600">lock</span>
                                <span>Cifrado de contraseñas mediante algoritmos de hash seguros.</span>
                            </li>
                            <li class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-red-600">security</span>
                                <span>Autenticación de usuarios y gestión de sesiones seguras.</span>
                            </li>
                            <li class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-red-600">https</span>
                                <span>Control de acceso basado en roles y permisos.</span>
                            </li>
                            <li class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-red-600">verified</span>
                                <span>Verificación de correo electrónico obligatoria</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- CONSERVACIÓN DE DATOS -->
                <div class="rounded-2xl bg-white dark:bg-gray-800 p-8 shadow-lg border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-amber-100 dark:bg-amber-900/30">
                            <span class="material-symbols-outlined text-2xl text-amber-700 dark:text-amber-400">schedule</span>
                        </div>
                        <h2 class="text-2xl font-bold text-amber-900 dark:text-amber-400">7. Conservación de Datos</h2>
                    </div>
                    <div class="space-y-4 text-gray-700 dark:text-gray-300">
                        <p>
                            Conservamos tus datos personales mientras mantengas tu cuenta activa en nuestra plataforma 
                            y durante el tiempo necesario para cumplir con las finalidades descritas en esta política.
                        </p>
                        <p>
                            Los datos personales se conservan mientras la cuenta del usuario se encuentre activa en la plataforma.
                        </p>
                        <p>
                            En caso de solicitar la eliminación de la cuenta, esta será desactivada para preservar la trazabilidad clínica, garantizando el cumplimiento del principio de conservación responsable de la información conforme a la normativa vigente.
                        </p>
                    </div>
                </div>

                <!-- CONTACTO -->
                <div class="rounded-2xl bg-gradient-to-br from-green-600 to-green-800 p-8 shadow-lg text-white">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-white/20">
                            <span class="material-symbols-outlined text-2xl">contact_support</span>
                        </div>
                        <h2 class="text-2xl font-bold">8. Contacto</h2>
                    </div>
                    <div class="space-y-4">
                        <p>
                            Si tienes preguntas, inquietudes o deseas ejercer cualquiera de tus derechos relacionados 
                            con tus datos personales, no dudes en contactarnos:
                        </p>
                        
                        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                            @if($settings?->email_contacto)
                            <a href="mailto:{{ $settings->email_contacto }}" class="flex items-center gap-3 rounded-lg bg-white/10 p-4 hover:bg-white/20 transition-colors">
                                <span class="material-symbols-outlined">mail</span>
                                <div>
                                    <p class="text-sm text-green-200">Correo electrónico</p>
                                    <p class="font-medium">{{ $settings->email_contacto }}</p>
                                </div>
                            </a>
                            @endif
                            @if($settings?->telefono)
                            <a href="{{ $settings->whatsapp_url }}" target="_blank" rel="noopener noreferrer" class="flex items-center gap-3 rounded-lg bg-white/10 p-4 hover:bg-white/20 transition-colors">
                                <span class="material-symbols-outlined">phone</span>
                                <div>
                                    <p class="text-sm text-green-200">Teléfono</p>
                                    <p class="font-medium">{{ $settings->telefono_formateado ?? $settings->telefono }}</p>
                                </div>
                            </a>
                            @endif
                            @if($settings?->direccion)
                            <a href="{{ $settings->google_maps_url ?? '#' }}" target="_blank" rel="noopener noreferrer" class="flex items-center gap-3 rounded-lg bg-white/10 p-4 hover:bg-white/20 transition-colors md:col-span-2 lg:col-span-1">
                                <span class="material-symbols-outlined">location_on</span>
                                <div>
                                    <p class="text-sm text-green-200">Dirección</p>
                                    <p class="font-medium">{{ $settings->direccion }}</p>
                                </div>
                            </a>
                            @endif
                        </div>

                        <div class="mt-6 pt-6 border-t border-white/20">
                            <a href="{{ route('contact') }}" class="inline-flex items-center gap-2 rounded-lg bg-white text-green-700 px-6 py-3 font-semibold hover:bg-green-50 transition-colors">
                                <span class="material-symbols-outlined">send</span>
                                Ir a la página de contacto
                            </a>
                        </div>
                    </div>
                </div>

                <!-- MODIFICACIONES -->
                <div class="rounded-2xl bg-gray-100 dark:bg-gray-700/50 p-8 border border-gray-200 dark:border-gray-600">
                    <div class="flex items-center gap-3 mb-4">
                        <span class="material-symbols-outlined text-2xl text-gray-600 dark:text-gray-400">update</span>
                        <h2 class="text-xl font-bold text-gray-900 dark:text-gray-200">Modificaciones a esta Política</h2>
                    </div>
                    <p class="text-gray-700 dark:text-gray-300">
                        Nos reservamos el derecho de modificar esta política de privacidad en cualquier momento. 
                        Las modificaciones entrarán en vigor una vez publicadas en esta página. Te recomendamos 
                        revisar periódicamente esta política para estar informado sobre cómo protegemos tus datos.
                    </p>
                </div>

            </div>
        </div>
    </section>

    @include('layouts.footer')
</body>
@endsection
