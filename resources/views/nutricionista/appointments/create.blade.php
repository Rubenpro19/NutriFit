@extends('layouts.app')

@section('title', 'Asignar Cita - NutriFit')

@section('content')
<body class="min-h-screen bg-gradient-to-br from-green-50 via-emerald-50 to-rose-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 flex flex-col">
    @include('layouts.header')

    <main class="flex-grow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
            <!-- Breadcrumb -->
            <nav class="mb-6 flex items-center gap-2 text-sm">
            <a href="{{ route('nutricionista.dashboard') }}" class="text-green-600 dark:text-green-400 hover:underline transition-colors">Inicio</a>
            <span class="material-symbols-outlined text-gray-400 text-sm">chevron_right</span>
            <span class="text-gray-700 dark:text-gray-300 font-medium">Asignar Cita</span>
        </nav>

        <!-- Header -->
        <div class="mb-8 bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-4 sm:p-6 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between gap-3">
                <div class="flex items-center gap-3 flex-1 min-w-0">
                    <a href="{{ route('nutricionista.dashboard') }}" class="text-gray-700 dark:text-gray-300 hover:text-green-600 dark:hover:text-green-400 hover:scale-110 transition-all">
                        <span class="material-symbols-outlined text-2xl">arrow_back</span>
                    </a>
                    <div class="min-w-0 flex-1">
                        <h1 class="text-2xl sm:text-3xl font-bold bg-gradient-to-r from-green-600 to-emerald-600 dark:from-green-400 dark:to-emerald-400 bg-clip-text text-transparent">
                            Asignar Cita a Paciente
                        </h1>
                        <p class="text-sm sm:text-base text-gray-700 dark:text-gray-300 mt-1 truncate">
                            Selecciona un paciente y asigna un horario disponible
                        </p>
                    </div>
                </div>
                <div class="flex-shrink-0 hidden sm:flex items-center">
                    <span class="material-symbols-outlined text-3xl sm:text-4xl text-green-600 dark:text-green-400">event_available</span>
                </div>
            </div>
        </div>

        @if(session('error'))
            <div class="mb-6 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-800 dark:text-red-200 px-4 py-3 rounded-lg flex items-center gap-3">
                <span class="material-symbols-outlined">error</span>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- Toast de Éxito -->
        @if(session('success'))
        <div x-data="{ show: true }" 
             x-init="setTimeout(() => show = false, 5000)"
             x-show="show"
             x-cloak
             class="fixed top-20 right-4 z-50 max-w-md w-full sm:w-96"
             style="display: none;">
            <div x-transition:enter="transition ease-out duration-300 transform"
                 x-transition:enter-start="translate-x-full opacity-0"
                 x-transition:enter-end="translate-x-0 opacity-100"
                 x-transition:leave="transition ease-in duration-200 transform"
                 x-transition:leave-start="translate-x-0 opacity-100"
                 x-transition:leave-end="translate-x-full opacity-0"
                 class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl border-l-4 border-green-500 overflow-hidden">
                
                
                <div class="p-4">
                    <div class="flex items-start gap-3">
                        <!-- Icono -->
                        <div class="flex-shrink-0">
                            <div class="bg-green-100 dark:bg-green-900/30 rounded-full p-2">
                                <span class="material-symbols-outlined text-green-600 dark:text-green-400 text-2xl">check_circle</span>
                            </div>
                        </div>
                        
                        <!-- Contenido -->
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-bold text-gray-900 dark:text-white mb-1">
                                ¡Cita Asignada con Éxito!
                            </h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                                {{ session('success') }}
                            </p>
                            
                            <!-- Resumen de la cita -->
                            @if(session('appointment_date') && session('appointment_time'))
                            <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-2 mb-3 border border-green-200 dark:border-green-700">
                                <div class="flex items-center gap-2 text-xs text-gray-700 dark:text-gray-300">
                                    <span class="material-symbols-outlined text-green-600 dark:text-green-400 text-sm">schedule</span>
                                    <span class="font-semibold">{{ \Carbon\Carbon::parse(session('appointment_date'))->locale('es')->isoFormat('dddd, D [de] MMMM') }}</span>
                                </div>
                                <div class="flex items-center gap-2 text-xs text-gray-700 dark:text-gray-300 mt-1 ml-5">
                                    <span>{{ \Carbon\Carbon::parse(session('appointment_time'))->format('h:i A') }}</span>
                                </div>
                            </div>
                            @endif
                            
                            <!-- Botón Ver Cita -->
                            @if(session('appointment_id'))
                                <a href="{{ route('nutricionista.appointments.show', session('appointment_id')) }}"
                                   class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white text-sm font-semibold rounded-lg hover:bg-green-700 transition-all duration-200 shadow-sm hover:shadow-md">
                                    <span class="material-symbols-outlined text-base">visibility</span>
                                    Ver Cita
                                </a>
                            @endif
                        </div>
                        
                        <!-- Botón Cerrar -->
                        <button @click="show = false"
                                class="flex-shrink-0 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors">
                            <span class="material-symbols-outlined">close</span>
                        </button>
                    </div>
                </div>
                
                <!-- Barra de progreso horizontal -->
                <div class="h-1 bg-gray-200 dark:bg-gray-700">
                    <div class="h-full bg-green-500 transition-all duration-100" style="width: 100%; animation: shrink 5s linear forwards;"></div>
                </div>
            </div>
        </div>

        <style>
            @keyframes shrink {
                from { width: 100%; }
                to { width: 0%; }
            }
        </style>
        @endif

        <div x-data="appointmentAssignment()" x-init="init()">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Columna Izquierda: Selección de Paciente -->
                    <div class="lg:col-span-1 w-full">
                        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-4 sm:p-6 border border-gray-200 dark:border-gray-700 lg:sticky lg:top-6">
                            <h2 class="text-base sm:text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                                <span class="material-symbols-outlined text-green-600 dark:text-green-400">person_search</span>
                                Seleccionar Paciente
                            </h2>

                            <!-- Barra de búsqueda -->
                            <div class="mb-4">
                                <div class="relative">
                                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500">search</span>
                                    <input 
                                        type="text" 
                                        x-model="searchQuery"
                                        @input="filterPatients"
                                        placeholder="Buscar paciente..."
                                        class="w-full pl-10 pr-10 py-2.5 text-sm sm:text-base rounded-lg border-2 border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all"
                                    >
                                    <button 
                                        x-show="searchQuery.length > 0"
                                        @click="searchQuery = ''; filterPatients()"
                                        type="button"
                                        class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors"
                                    >
                                        <span class="material-symbols-outlined text-lg">close</span>
                                    </button>
                                </div>
                            </div>

                            <!-- Lista de pacientes -->
                            <div class="space-y-2 sm:space-y-3 max-h-96 overflow-y-auto">
                                <template x-for="paciente in filteredPatients" :key="paciente.id">
                                    <button
                                        type="button"
                                        @click="selectPatient(paciente.id)"
                                        :class="selectedPatientId === paciente.id ? 'border-green-500 bg-green-50 dark:bg-green-900/20 shadow-md' : 'border-gray-200 dark:border-gray-700 hover:border-green-300 hover:shadow-sm'"
                                        class="w-full text-left p-3 sm:p-4 rounded-lg border-2 transition-all duration-200"
                                    >
                                        <div class="flex items-center gap-3 min-w-0">
                                            <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full flex items-center justify-center flex-shrink-0 overflow-hidden">
                                                <template x-if="paciente.profile_photo">
                                                    <img :src="paciente.profile_photo ? '/storage/' + paciente.profile_photo : 'data:,'" :alt="paciente.name" class="w-full h-full object-cover">
                                                </template>
                                                <template x-if="!paciente.profile_photo">
                                                    <div class="w-full h-full bg-gradient-to-br from-green-500 to-emerald-500 flex items-center justify-center text-white text-xs sm:text-sm font-bold">
                                                        <span x-text="paciente.initials"></span>
                                                    </div>
                                                </template>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="font-semibold text-sm sm:text-base text-gray-900 dark:text-white truncate" x-text="paciente.name"></p>
                                                <p class="text-xs text-gray-600 dark:text-gray-400 truncate" x-text="paciente.email"></p>
                                                <!-- Badge de estado -->
                                                <template x-if="!paciente.habilitado_clinicamente">
                                                    <span class="inline-flex items-center gap-1 mt-1 px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300">
                                                        <span class="material-symbols-outlined text-xs">block</span>
                                                        <span class="truncate" x-text="paciente.motivo_inhabilitacion"></span>
                                                    </span>
                                                </template>
                                            </div>
                                            <span x-show="selectedPatientId === paciente.id" class="material-symbols-outlined text-green-600 dark:text-green-400 flex-shrink-0">check_circle</span>
                                        </div>
                                    </button>
                                </template>

                                <!-- Sin resultados -->
                                <div x-show="filteredPatients.length === 0" class="text-center py-8">
                                    <span class="material-symbols-outlined text-4xl text-gray-400 dark:text-gray-600 mb-2 block">search_off</span>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">No se encontraron pacientes</p>
                                    <button 
                                        x-show="searchQuery.length > 0"
                                        @click="searchQuery = ''; filterPatients()"
                                        class="mt-3 text-sm text-green-600 hover:text-green-700 dark:text-green-400 dark:hover:text-green-300 font-semibold hover:underline"
                                    >
                                        Limpiar búsqueda
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Columna Derecha: Horarios y Formulario -->
                    <div class="lg:col-span-2 w-full min-w-0">
                        <!-- Mensaje inicial -->
                        <div x-show="!selectedPatientId" class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-12 text-center border border-gray-200 dark:border-gray-700">
                            <span class="material-symbols-outlined text-6xl text-gray-300 dark:text-gray-600 mb-4">arrow_back</span>
                            <p class="text-gray-600 dark:text-gray-400">Selecciona un paciente para ver los horarios disponibles</p>
                        </div>

                        <!-- Loading -->
                        <div x-show="loading" class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-12 text-center border border-gray-200 dark:border-gray-700">
                            <svg class="animate-spin h-12 w-12 mx-auto text-green-600 mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <p class="text-gray-600 dark:text-gray-400">Cargando horarios disponibles...</p>
                        </div>

                        <!-- Mensaje cuando el paciente ya tiene una cita pendiente -->
                        <div x-show="selectedPatientId && !loading && hasError" class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-8 sm:p-12 border border-orange-200 dark:border-orange-800 w-full overflow-hidden">
                            <!-- Información del Paciente -->
                            <div class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700/50 dark:to-gray-600/50 rounded-2xl p-6 mb-6 border border-gray-200 dark:border-gray-600 overflow-hidden" x-data="{ showPhotoModal: false }">
                                <div class="flex items-center gap-4 min-w-0">
                                    <div class="w-20 h-20 rounded-full flex items-center justify-center flex-shrink-0 overflow-hidden border-4 border-white dark:border-gray-800 shadow-lg">
                                        <template x-if="selectedPatient?.profile_photo">
                                            <img :src="selectedPatient?.profile_photo ? '/storage/' + selectedPatient.profile_photo : 'data:,'" 
                                                 :alt="selectedPatient?.name" 
                                                 @click="showPhotoModal = true"
                                                 class="w-full h-full object-cover cursor-pointer hover:opacity-90 transition">
                                        </template>
                                        <template x-if="!selectedPatient?.profile_photo">
                                            <div class="w-full h-full bg-gradient-to-br from-orange-500 to-red-500 flex items-center justify-center text-white text-2xl font-bold">
                                                <span x-text="selectedPatient?.name?.charAt(0).toUpperCase()"></span>
                                            </div>
                                        </template>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Información del paciente:</p>
                                        <p class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white break-words" x-text="selectedPatient?.name"></p>
                                        <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 flex items-start gap-1 mt-1 min-w-0">
                                            <span class="material-symbols-outlined text-base flex-shrink-0">email</span>
                                            <span class="break-all" x-text="selectedPatient?.email"></span>
                                        </p>
                                    </div>
                                </div>

                                <!-- Modal de Foto de Perfil -->
                                <div x-show="showPhotoModal && selectedPatient?.profile_photo"
                                     x-cloak
                                     @keydown.escape.window="showPhotoModal = false"
                                     class="fixed inset-0 z-50 flex items-center justify-center p-4"
                                     style="display: none;">
                                    
                                    <!-- Overlay -->
                                    <div class="fixed inset-0 bg-black/60 dark:bg-black/80" @click="showPhotoModal = false"></div>
                                    
                                    <!-- Modal Content -->
                                    <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-hidden"
                                         @click.stop>
                                        
                                        <!-- Header -->
                                        <div class="bg-gradient-to-r from-blue-600 to-cyan-600 px-6 py-4 flex items-center justify-between">
                                            <h3 class="text-lg font-bold text-white flex items-center gap-2">
                                                <span class="material-symbols-outlined">photo_camera</span>
                                                Foto de Perfil
                                            </h3>
                                            <button type="button" @click="showPhotoModal = false" class="text-white hover:text-gray-200 transition">
                                                <span class="material-symbols-outlined">close</span>
                                            </button>
                                        </div>
                                        
                                        <!-- Image -->
                                        <div class="p-6 overflow-auto max-h-[calc(90vh-140px)]">
                                            <img :src="selectedPatient?.profile_photo ? '/storage/' + selectedPatient.profile_photo : 'data:,'" 
                                                 :alt="selectedPatient?.name"
                                                 class="w-full h-auto rounded-lg">
                                        </div>
                                        
                                        <!-- Footer -->
                                        <div class="bg-gray-50 dark:bg-gray-700 px-6 py-4 flex justify-end">
                                            <button type="button" @click="showPhotoModal = false" 
                                                    class="px-6 py-2 bg-gray-200 dark:bg-gray-600 text-gray-800 dark:text-gray-200 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-500 transition">
                                                Cerrar
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="text-center mb-6">
                                <span class="material-symbols-outlined text-5xl sm:text-6xl text-orange-500 dark:text-orange-400 mb-4 inline-block">event_busy</span>
                                <h3 class="text-lg sm:text-xl font-bold text-gray-900 dark:text-white mb-2">Paciente no disponible</h3>
                            </div>
                            
                            <div class="bg-orange-50 dark:bg-orange-900/20 border border-orange-200 dark:border-orange-800 rounded-lg p-6 mb-6">
                                <p class="text-sm sm:text-base text-orange-800 dark:text-orange-200 text-center" x-text="errorMessage"></p>
                            </div>

                            <!-- Mostrar información de la cita si es del mismo nutricionista -->
                            <div x-show="isOwnAppointment && appointmentData" class="mb-6">
                                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-lg p-6 border border-blue-200 dark:border-blue-800">
                                    <h4 class="font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                                        <span class="material-symbols-outlined text-blue-600">info</span>
                                        Información de la cita pendiente
                                    </h4>
                                    
                                    <div class="space-y-3">
                                        <div class="flex items-start gap-3">
                                            <span class="material-symbols-outlined text-blue-600 text-lg">schedule</span>
                                            <div>
                                                <p class="text-xs text-gray-600 dark:text-gray-400">Fecha y hora</p>
                                                <p class="text-sm font-medium text-gray-900 dark:text-white capitalize" x-text="appointmentData?.start_time_formatted"></p>
                                            </div>
                                        </div>

                                        <div class="flex items-start gap-3" x-show="appointmentData?.appointment_type">
                                            <span class="material-symbols-outlined text-blue-600 text-lg">medical_services</span>
                                            <div>
                                                <p class="text-xs text-gray-600 dark:text-gray-400">Tipo de consulta</p>
                                                <p class="text-sm font-medium text-gray-900 dark:text-white capitalize" x-text="appointmentData?.appointment_type?.replace('_', ' ')"></p>
                                            </div>
                                        </div>

                                        <div class="flex items-start gap-3" x-show="appointmentData?.reason">
                                            <span class="material-symbols-outlined text-blue-600 text-lg">description</span>
                                            <div>
                                                <p class="text-xs text-gray-600 dark:text-gray-400">Motivo</p>
                                                <p class="text-sm font-medium text-gray-900 dark:text-white" x-text="appointmentData?.reason"></p>
                                            </div>
                                        </div>

                                        <div class="flex items-start gap-3" x-show="appointmentData?.price">
                                            <span class="material-symbols-outlined text-blue-600 text-lg">payments</span>
                                            <div>
                                                <p class="text-xs text-gray-600 dark:text-gray-400">Precio</p>
                                                <p class="text-sm font-medium text-gray-900 dark:text-white" x-text="'$' + appointmentData?.price"></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div x-show="!isOwnAppointment" class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4 mb-6">
                                <p class="text-xs sm:text-sm text-blue-800 dark:text-blue-200 flex items-start gap-2">
                                    <span class="material-symbols-outlined text-lg flex-shrink-0">info</span>
                                    <span>Un paciente solo puede tener una cita pendiente a la vez. Debe completar o cancelar su cita actual antes de poder agendar una nueva.</span>
                                </p>
                            </div>

                            <div class="flex flex-col sm:flex-row justify-center gap-3">
                                <!-- Botón para gestionar la cita (solo si es del mismo nutricionista) -->
                                <a 
                                    x-show="isOwnAppointment && appointmentData"
                                    :href="'/nutricionista/appointments/' + appointmentData?.id"
                                    class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold rounded-lg hover:from-blue-700 hover:to-indigo-700 transition-all duration-200 shadow-lg hover:shadow-xl"
                                >
                                    <span class="material-symbols-outlined">event</span>
                                    Gestionar esta cita
                                </a>

                                <button 
                                    type="button"
                                    @click="resetSelection"
                                    class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white font-semibold rounded-lg hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-lg hover:shadow-xl"
                                >
                                    <span class="material-symbols-outlined">arrow_back</span>
                                    Seleccionar otro paciente
                                </button>
                            </div>
                        </div>

                        <!-- Horarios Disponibles y Formulario -->
                        <div x-show="selectedPatientId && !loading && !hasError && weeks.length > 0" class="w-full overflow-hidden">
                            <form method="POST" action="{{ route('nutricionista.appointments.store') }}" @submit="submitting = true" class="w-full">
                                @csrf
                                <input type="hidden" name="paciente_id" x-model="selectedPatientId">
                                <input type="hidden" name="appointment_date" x-model="selectedDate">
                                <input type="hidden" name="appointment_time" x-model="selectedTime">

                                <!-- Información del Paciente Seleccionado -->
                                <div class="bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 rounded-2xl p-4 sm:p-6 mb-6 border border-green-200 dark:border-green-800" x-data="{ showPhotoModal: false }">
                                    <div class="flex items-center gap-4 mb-0">
                                        <div class="w-16 h-16 rounded-full flex items-center justify-center flex-shrink-0 overflow-hidden">
                                            <template x-if="selectedPatient?.profile_photo">
                                                <img :src="selectedPatient?.profile_photo ? '/storage/' + selectedPatient.profile_photo : 'data:,'" 
                                                     :alt="selectedPatient?.name" 
                                                     @click="showPhotoModal = true"
                                                     class="w-full h-full object-cover cursor-pointer hover:opacity-90 transition">
                                            </template>
                                            <template x-if="!selectedPatient?.profile_photo">
                                                <div class="w-full h-full bg-gradient-to-br from-green-500 to-emerald-500 flex items-center justify-center text-white text-xl font-bold">
                                                    <span x-text="selectedPatient?.name?.charAt(0).toUpperCase()"></span>
                                                </div>
                                            </template>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm text-gray-600 dark:text-gray-400">Asignando cita para:</p>
                                            <p class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white truncate" x-text="selectedPatient?.name"></p>
                                            <p class="text-sm text-gray-600 dark:text-gray-400 truncate" x-text="selectedPatient?.email"></p>
                                        </div>
                                    </div>
                                    
                                    <!-- Resumen de Selección -->
                                    <div x-show="selectedDate && selectedTime" 
                                         x-transition:enter="transition ease-out duration-300"
                                         x-transition:enter-start="opacity-0 transform scale-95"
                                         x-transition:enter-end="opacity-100 transform scale-100"
                                         class="mt-4 w-full">
                                        <div class="bg-gradient-to-br from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 rounded-xl p-3 sm:p-4 border-2 border-dashed border-green-300 dark:border-green-600">
                                            <p class="text-sm font-semibold text-green-900 dark:text-green-200 mb-3 flex items-center gap-2">
                                                <span class="material-symbols-outlined text-base">check_circle</span>
                                                Horario seleccionado
                                            </p>
                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                                <div class="flex items-center gap-2 sm:gap-3 bg-white dark:bg-gray-700 px-3 py-2 sm:py-3 rounded-lg shadow-sm min-w-0">
                                                    <span class="material-symbols-outlined text-green-600 dark:text-green-400 text-lg sm:text-xl flex-shrink-0">calendar_today</span>
                                                    <div class="flex-1 min-w-0">
                                                        <p class="text-xs text-gray-500 dark:text-gray-400">Fecha</p>
                                                        <p class="text-xs sm:text-sm font-bold text-gray-900 dark:text-white break-words" x-text="selectedDate ? new Date(selectedDate + 'T00:00:00').toLocaleDateString('es-ES', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }) : ''"></p>
                                                    </div>
                                                </div>
                                                <div class="flex items-center gap-2 sm:gap-3 bg-white dark:bg-gray-700 px-3 py-2 sm:py-3 rounded-lg shadow-sm min-w-0">
                                                    <span class="material-symbols-outlined text-green-600 dark:text-green-400 text-lg sm:text-xl flex-shrink-0">schedule</span>
                                                    <div class="flex-1 min-w-0">
                                                        <p class="text-xs text-gray-500 dark:text-gray-400">Hora</p>
                                                        <p class="text-sm font-bold text-gray-900 dark:text-white" x-text="selectedTime"></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Modal de Foto de Perfil -->
                                    <div x-show="showPhotoModal && selectedPatient?.profile_photo"
                                         x-cloak
                                         @keydown.escape.window="showPhotoModal = false"
                                         class="fixed inset-0 z-50 flex items-center justify-center p-4">
                                        
                                        <!-- Overlay -->
                                        <div class="fixed inset-0 bg-black/60 dark:bg-black/80" @click="showPhotoModal = false"></div>
                                        
                                        <!-- Modal Content -->
                                        <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-hidden"
                                             @click.stop>
                                            
                                            <!-- Header -->
                                            <div class="bg-gradient-to-r from-green-600 to-emerald-600 px-6 py-4 flex items-center justify-between">
                                                <h3 class="text-lg font-bold text-white flex items-center gap-2">
                                                    <span class="material-symbols-outlined">photo_camera</span>
                                                    Foto de Perfil
                                                </h3>
                                                <button type="button" @click="showPhotoModal = false" class="text-white hover:text-gray-200 transition">
                                                    <span class="material-symbols-outlined">close</span>
                                                </button>
                                            </div>
                                            
                                            <!-- Image -->
                                            <div class="p-6 overflow-auto max-h-[calc(90vh-140px)]">
                                                <img :src="selectedPatient?.profile_photo ? '/storage/' + selectedPatient.profile_photo : 'data:,'" 
                                                     :alt="selectedPatient?.name"
                                                     class="w-full h-auto rounded-lg">
                                            </div>
                                            
                                            <!-- Footer -->
                                            <div class="bg-gray-50 dark:bg-gray-700 px-6 py-4 flex justify-end">
                                                <button type="button" @click="showPhotoModal = false" 
                                                        class="px-6 py-2 bg-gray-200 dark:bg-gray-600 text-gray-800 dark:text-gray-200 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-500 transition">
                                                    Cerrar
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Detalles de la Cita -->
                                <div x-show="selectedDate && selectedTime" x-cloak style="display: none;" class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-4 sm:p-6 border border-gray-200 dark:border-gray-700 mb-6">
                                    <h3 class="text-base sm:text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                                        <span class="material-symbols-outlined text-green-600">edit_note</span>
                                        Detalles de la Cita
                                    </h3>

                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                                        <!-- Tipo de Cita -->
                                        <div>
                                            <label for="appointment_type" class="flex items-center gap-2 text-sm font-semibold text-gray-800 dark:text-gray-200 mb-2">
                                                <span class="material-symbols-outlined text-lg text-green-600 dark:text-green-400">medical_services</span>
                                                Tipo de Cita *
                                            </label>
                                            <select 
                                                id="appointment_type" 
                                                name="appointment_type" 
                                                required
                                                class="w-full px-3 sm:px-4 py-2.5 text-sm sm:text-base rounded-lg border-2 border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all"
                                            >
                                                <option value="">Seleccionar tipo...</option>
                                                <option value="primera_vez" x-show="!hasPreviousAppointments">Primera Vez</option>
                                                <option value="seguimiento">Seguimiento</option>
                                                <option value="control">Control</option>
                                            </select>
                                        </div>

                                        <!-- Precio -->
                                        <div>
                                            <label for="price" class="flex items-center gap-2 text-sm font-semibold text-gray-800 dark:text-gray-200 mb-2">
                                                <span class="material-symbols-outlined text-lg text-green-600 dark:text-green-400">payments</span>
                                                Precio *
                                            </label>
                                            <div class="relative">
                                                <span class="absolute left-3 sm:left-4 top-1/2 -translate-y-1/2 text-gray-600 dark:text-gray-400 font-semibold">$</span>
                                                <input 
                                                    type="number" 
                                                    id="price" 
                                                    name="price" 
                                                    step="0.01"
                                                    min="0"
                                                    required
                                                    value="{{ number_format($consultationPrice, 2, '.', '') }}"
                                                    placeholder="0.00"
                                                    class="w-full pl-7 sm:pl-8 pr-3 sm:pr-4 py-2.5 text-sm sm:text-base rounded-lg border-2 border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all"
                                                >
                                            </div>
                                            <p class="mt-2 text-xs text-gray-600 dark:text-gray-400 flex items-start gap-1.5">
                                                <span class="material-symbols-outlined text-sm flex-shrink-0 mt-0.5">info</span>
                                                <span>Puedes cambiar tu precio por defecto en <a href="{{ route('nutricionista.profile') }}" class="text-green-600 dark:text-green-400 hover:underline font-semibold">Mi Perfil</a></span>
                                            </p>
                                        </div>

                                        <!-- Motivo -->
                                        <div class="sm:col-span-2">
                                            <label for="reason" class="flex items-center gap-2 text-sm font-semibold text-gray-800 dark:text-gray-200 mb-2">
                                                <span class="material-symbols-outlined text-lg text-green-600 dark:text-green-400">description</span>
                                                Motivo de la Consulta
                                            </label>
                                            <textarea 
                                                id="reason" 
                                                name="reason" 
                                                rows="3"
                                                placeholder="Ej: Control de peso, Evaluación inicial, etc."
                                                class="w-full px-3 sm:px-4 py-2.5 text-sm sm:text-base rounded-lg border-2 border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all"
                                            ></textarea>
                                        </div>

                                        <!-- Notas -->
                                        <div class="sm:col-span-2">
                                            <label for="notes" class="flex items-center gap-2 text-sm font-semibold text-gray-800 dark:text-gray-200 mb-2">
                                                <span class="material-symbols-outlined text-lg text-green-600 dark:text-green-400">note_add</span>
                                                Notas Adicionales
                                            </label>
                                            <textarea 
                                                id="notes" 
                                                name="notes" 
                                                rows="3"
                                                placeholder="Notas internas sobre la cita..."
                                                class="w-full px-3 sm:px-4 py-2.5 text-sm sm:text-base rounded-lg border-2 border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all"
                                            ></textarea>
                                        </div>
                                    </div>

                                    <!-- Botones -->
                                    <div class="mt-6 flex flex-col sm:flex-row gap-3">
                                        <button 
                                            type="submit"
                                            :disabled="submitting"
                                            class="flex-1 flex items-center justify-center gap-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white font-semibold py-3 px-4 sm:px-6 text-sm sm:text-base rounded-lg hover:from-green-700 hover:to-emerald-700 hover:shadow-lg hover:scale-105 active:scale-95 transition-all duration-200 shadow-md disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:scale-100"
                                        >
                                            <span x-show="!submitting" class="material-symbols-outlined">check</span>
                                            <svg x-show="submitting" class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                            <span x-text="submitting ? 'Asignando Cita...' : 'Asignar Cita'"></span>
                                        </button>
                                        <button 
                                            type="button"
                                            @click="resetSelection"
                                            :disabled="submitting"
                                            class="px-4 sm:px-6 py-3 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 font-semibold text-sm sm:text-base rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 hover:shadow-md hover:scale-105 active:scale-95 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:scale-100"
                                        >
                                            Cancelar
                                        </button>
                                    </div>
                                </div>

                                <!-- Selección de Horario con Navegación por Semanas -->
                                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg mb-6 border border-gray-200 dark:border-gray-700 overflow-hidden">
                                    <!-- Header -->
                                    <div class="bg-gradient-to-r from-green-600 to-emerald-600 px-6 py-4">
                                        <h3 class="text-xl font-bold text-white flex items-center gap-2">
                                            <span class="material-symbols-outlined">calendar_month</span>
                                            Seleccionar Horario
                                        </h3>
                                        <p class="text-green-100 text-sm mt-1">Próximas 4 semanas disponibles</p>
                                    </div>

                                    <!-- Navegación de Semanas -->
                                    <div class="border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/30">
                                        <div class="flex items-center justify-between px-3 sm:px-6 py-3 sm:py-4 gap-2">
                                            <button type="button" @click="changeWeek(-1)" :disabled="currentWeek === 0"
                                                    class="flex items-center gap-1 sm:gap-2 px-2 sm:px-4 py-2 text-xs sm:text-sm font-semibold text-gray-800 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-700 rounded-lg transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed hover:scale-105 active:scale-95 flex-shrink-0">
                                                <span class="material-symbols-outlined text-lg sm:text-base">chevron_left</span>
                                                <span class="hidden sm:inline">Anterior</span>
                                            </button>
                                            
                                            <div class="flex gap-2 overflow-x-auto pb-2 flex-1 sm:justify-center scrollbar-hide">
                                                <template x-for="(week, index) in weeks" :key="index">
                                                    <button type="button" @click="showWeek(index)"
                                                            :class="currentWeek === index 
                                                                ? 'bg-gradient-to-r from-green-600 to-emerald-600 text-white shadow-lg scale-105' 
                                                                : 'bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 hover:shadow-md hover:scale-105'"
                                                            class="flex-shrink-0 px-3 sm:px-6 py-2 sm:py-3 rounded-lg font-semibold transition-all duration-200 active:scale-95 min-w-[80px] sm:min-w-0">
                                                        <div class="text-xs opacity-90 mb-1" x-text="'Sem ' + (index + 1)"></div>
                                                        <div class="text-xs sm:text-sm font-bold whitespace-nowrap" x-text="week.label"></div>
                                                    </button>
                                                </template>
                                            </div>

                                            <button type="button" @click="changeWeek(1)" :disabled="currentWeek === weeks.length - 1"
                                                    class="flex items-center gap-1 sm:gap-2 px-2 sm:px-4 py-2 text-xs sm:text-sm font-semibold text-gray-800 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-700 rounded-lg transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed hover:scale-105 active:scale-95 flex-shrink-0">
                                                <span class="hidden sm:inline">Siguiente</span>
                                                <span class="material-symbols-outlined text-lg sm:text-base">chevron_right</span>
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Contenido de la Semana Actual -->
                                    <div class="p-3 sm:p-6">
                                        <template x-for="(week, weekIndex) in weeks" :key="weekIndex">
                                            <div x-show="currentWeek === weekIndex" class="space-y-3 sm:space-y-4">
                                                <template x-for="daySlots in week.days" :key="daySlots.date">
                                                    <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-3 sm:p-4">
                                                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-3 gap-2">
                                                            <h4 class="font-semibold text-sm sm:text-base text-gray-900 dark:text-white capitalize flex items-center gap-2">
                                                                <span class="material-symbols-outlined text-green-600 text-lg sm:text-xl">calendar_today</span>
                                                                <span x-text="daySlots.date_formatted"></span>
                                                            </h4>
                                                            <span class="text-xs text-gray-500 dark:text-gray-400 pl-7 sm:pl-0" x-text="daySlots.slots.length > 0 ? daySlots.slots.length + ' horarios' : 'Sin horarios'"></span>
                                                        </div>
                                                        
                                                        <!-- Mostrar horarios o mensaje de "sin horarios" -->
                                                        <div x-show="daySlots.slots.length > 0" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-2">
                                                            <template x-for="slot in daySlots.slots" :key="slot.time">
                                                                <button
                                                                    type="button"
                                                                    @click="selectSlot(daySlots.date, slot.time)"
                                                                    :class="selectedDate === daySlots.date && selectedTime === slot.time 
                                                                        ? 'bg-green-600 text-white ring-4 ring-green-400 shadow-lg scale-105' 
                                                                        : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-green-100 dark:hover:bg-green-900/30 hover:shadow-md hover:scale-105'"
                                                                    class="px-2 sm:px-3 py-2 sm:py-2.5 rounded-lg text-xs sm:text-sm font-medium transition-all duration-200 flex items-center justify-center gap-1"
                                                                >
                                                                    <span class="material-symbols-outlined text-xs">schedule</span>
                                                                    <span x-text="slot.time_formatted"></span>
                                                                </button>
                                                            </template>
                                                        </div>
                                                        
                                                        <!-- Mensaje cuando no hay horarios para este día -->
                                                        <div x-show="daySlots.slots.length === 0" class="bg-gray-50 dark:bg-gray-900/50 rounded-lg p-4 sm:p-6 text-center">
                                                            <span class="material-symbols-outlined text-3xl sm:text-4xl text-gray-400 dark:text-gray-600 mb-2">event_busy</span>
                                                            <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400">
                                                                No hay horarios disponibles para este día
                                                            </p>
                                                            <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">
                                                                Todos los espacios están ocupados o no has configurado horarios para este día
                                                            </p>
                                                        </div>
                                                    </div>
                                                </template>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <!-- Sin Horarios Disponibles -->
                        <div x-show="selectedPatientId && !loading && !hasError && weeks.length === 0" class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-8 sm:p-12 text-center border border-gray-200 dark:border-gray-700">
                            <span class="material-symbols-outlined text-5xl sm:text-6xl text-orange-400 mb-4">event_busy</span>
                            <h3 class="text-lg sm:text-xl font-bold text-gray-900 dark:text-white mb-2">No hay horarios disponibles</h3>
                            <p class="text-sm sm:text-base text-gray-600 dark:text-gray-400 mb-6">
                                No tienes horarios configurados o todos los espacios están ocupados para las próximas 4 semanas.
                            </p>
                            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4 mb-6 text-left">
                                <p class="text-sm text-blue-800 dark:text-blue-200 flex items-start gap-2">
                                    <span class="material-symbols-outlined text-lg flex-shrink-0">info</span>
                                    <span>Para poder asignar citas, primero debes configurar tus horarios de disponibilidad en la sección de Horarios.</span>
                                </p>
                            </div>
                            <a 
                                href="{{ route('nutricionista.schedules.index') }}"
                                class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white font-semibold rounded-lg hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-lg hover:shadow-xl"
                            >
                                <span class="material-symbols-outlined">schedule</span>
                                Configurar Horarios
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <script>
        function appointmentAssignment() {
            return {
                // Datos de pacientes
                allPatients: {!! json_encode($pacientes->map(function($p) {
                    $data = [
                        'id' => $p->id,
                        'name' => $p->name,
                        'email' => $p->email,
                        'initials' => $p->initials(),
                        'habilitado_clinicamente' => $p->estaHabilitadoClinicamente(),
                        'motivo_inhabilitacion' => $p->motivoInhabilitacion(),
                        'estado' => $p->userState?->name,
                    ];
                    // Solo incluir profile_photo si tiene un valor válido
                    if ($p->personalData?->profile_photo) {
                        $data['profile_photo'] = $p->personalData->profile_photo;
                    }
                    return $data;
                })) !!},
                filteredPatients: [],
                searchQuery: '',
                
                // Estado de selección
                selectedPatientId: null,
                selectedPatient: null,
                weeks: [],
                currentWeek: 0,
                selectedDate: null,
                selectedTime: null,
                loading: false,
                submitting: false,
                
                // Estado de error
                hasError: false,
                errorMessage: '',
                isOwnAppointment: false,
                appointmentData: null,
                hasPreviousAppointments: false,

                init() {
                    this.filteredPatients = this.allPatients;
                },

                filterPatients() {
                    const query = this.searchQuery.toLowerCase().trim();
                    
                    if (query === '') {
                        this.filteredPatients = this.allPatients;
                    } else {
                        this.filteredPatients = this.allPatients.filter(patient => 
                            patient.name.toLowerCase().includes(query) || 
                            patient.email.toLowerCase().includes(query)
                        );
                    }
                },

                async selectPatient(patientId) {
                    this.selectedPatientId = patientId;
                    this.loading = true;
                    this.weeks = [];
                    this.currentWeek = 0;
                    this.selectedDate = null;
                    this.selectedTime = null;
                    this.hasError = false;
                    this.errorMessage = '';
                    this.isOwnAppointment = false;
                    this.appointmentData = null;
                    this.hasPreviousAppointments = false;

                    // Buscar el paciente en la lista local
                    this.selectedPatient = this.allPatients.find(p => p.id === patientId);

                    // Verificar si el paciente está habilitado clínicamente
                    if (this.selectedPatient && !this.selectedPatient.habilitado_clinicamente) {
                        this.hasError = true;
                        this.errorMessage = `No se puede asignar cita: ${this.selectedPatient.motivo_inhabilitacion}. El paciente debe estar activo y con email verificado.`;
                        this.loading = false;
                        return;
                    }

                    try {
                        const response = await fetch(`/nutricionista/citas/asignar/${patientId}/horarios`);
                        const data = await response.json();
                        
                        if (response.ok && !data.error) {
                            // Actualizar con datos del servidor si están disponibles
                            if (data.paciente) {
                                this.selectedPatient = data.paciente;
                            }
                            this.hasPreviousAppointments = data.hasPreviousAppointments || false;
                            this.weeks = data.weeks.map(week => ({
                                label: week.start_date_formatted,
                                week_number: week.week_number,
                                days: week.days // Mostrar todos los días, incluso sin horarios
                            }));
                        } else {
                            // Mostrar error en la interfaz en lugar de alert
                            this.hasError = true;
                            this.errorMessage = data.error || 'Error al cargar horarios';
                            this.isOwnAppointment = data.isOwnAppointment || false;
                            this.appointmentData = data.appointment || null;
                            this.hasPreviousAppointments = data.hasPreviousAppointments || false;
                            // Actualizar con datos del servidor si están disponibles
                            if (data.paciente) {
                                this.selectedPatient = data.paciente;
                            }
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        this.hasError = true;
                        this.errorMessage = 'Error al cargar los horarios disponibles';
                    } finally {
                        this.loading = false;
                    }
                },

                showWeek(index) {
                    this.currentWeek = index;
                },

                changeWeek(direction) {
                    const newWeek = this.currentWeek + direction;
                    if (newWeek >= 0 && newWeek < this.weeks.length) {
                        this.currentWeek = newWeek;
                    }
                },

                selectSlot(date, time) {
                    this.selectedDate = date;
                    this.selectedTime = time;
                },

                resetSelection() {
                    this.selectedPatientId = null;
                    this.selectedPatient = null;
                    this.weeks = [];
                    this.currentWeek = 0;
                    this.selectedDate = null;
                    this.selectedTime = null;
                    this.hasError = false;
                    this.errorMessage = '';
                    this.isOwnAppointment = false;
                    this.appointmentData = null;
                    this.hasPreviousAppointments = false;
                }
            }
        }
    </script>
        </div>
    </main>

    @include('layouts.footer')
</body>
@endsection
