@extends('layouts.app')

@section('title', 'Datos Personales del Paciente - NutriFit')

@section('content')
<body class="min-h-screen bg-gradient-to-br from-green-50 via-emerald-50 to-rose-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 flex flex-col">
    @include('layouts.header')

    <main class="flex-grow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
            <!-- Breadcrumb -->
            <nav class="mb-6 flex items-center gap-2 text-sm">
                <a href="{{ route('nutricionista.dashboard') }}" class="text-green-600 dark:text-green-400 hover:underline transition-colors">Inicio</a>
                <span class="material-symbols-outlined text-gray-400 text-sm">chevron_right</span>
                <a href="{{ route('nutricionista.patients.index') }}" class="text-green-600 dark:text-green-400 hover:underline transition-colors">Mis Pacientes</a>
                <span class="material-symbols-outlined text-gray-400 text-sm">chevron_right</span>
                <span class="text-gray-700 dark:text-gray-300 font-medium">Datos Personales</span>
            </nav>

            <!-- Header -->
            <div class="mb-8 bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-4 sm:p-6 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between gap-3">
                    <div class="flex items-center gap-3 flex-1 min-w-0">
                        <a href="{{ route('nutricionista.patients.index') }}" class="text-gray-700 dark:text-gray-300 hover:text-green-600 dark:hover:text-green-400 hover:scale-110 transition-all">
                            <span class="material-symbols-outlined text-2xl">arrow_back</span>
                        </a>
                        <div class="min-w-0 flex-1">
                            <h1 class="text-2xl sm:text-3xl font-bold bg-gradient-to-r from-green-600 to-emerald-600 dark:from-green-400 dark:to-emerald-400 bg-clip-text text-transparent">
                                Datos Personales
                            </h1>
                            <p class="text-sm sm:text-base text-gray-700 dark:text-gray-300 mt-1 truncate">
                                Información personal del paciente {{ $patient->name }}
                            </p>
                        </div>
                    </div>
                    <div class="flex-shrink-0 hidden sm:flex items-center">
                        <span class="material-symbols-outlined text-3xl sm:text-4xl text-green-600 dark:text-green-400">account_circle</span>
                    </div>
                </div>
            </div>
        
            <!-- Información del Paciente -->
            <div class="mb-6 bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-4 sm:p-6 border border-gray-200 dark:border-gray-700" x-data="{ showPhotoModal: false }">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-green-600 dark:text-green-400">person</span>
                    Información del Paciente
                </h2>
                
                <div class="flex flex-col sm:flex-row items-center sm:items-start gap-4 sm:gap-6">
                    <!-- Foto de Perfil -->
                    <div class="flex-shrink-0">
                        <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-full overflow-hidden flex items-center justify-center shadow-lg border-4 border-green-100 dark:border-green-900 {{ $patient->personalData?->profile_photo ? 'cursor-pointer hover:opacity-90 transition' : '' }}"
                             @if($patient->personalData?->profile_photo) @click="showPhotoModal = true" @endif>
                            @if($patient->personalData?->profile_photo)
                                <img src="{{ asset('storage/' . $patient->personalData->profile_photo) }}" 
                                     alt="{{ $patient->name }}" 
                                     class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-green-500 to-emerald-500 flex items-center justify-center text-white text-2xl sm:text-3xl font-bold">
                                    {{ $patient->initials() }}
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Información Básica -->
                    <div class="flex-1 w-full grid grid-cols-1 md:grid-cols-2 gap-3 sm:gap-4">
                        <div class="min-w-0">
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Nombre Completo</p>
                            <p class="font-semibold text-gray-900 dark:text-white text-sm sm:text-base break-words">{{ $patient->name }}</p>
                        </div>
                        
                        <div class="min-w-0">
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Correo Electrónico</p>
                            <p class="font-semibold text-gray-900 dark:text-white flex items-start gap-1 text-sm sm:text-base">
                                <span class="material-symbols-outlined text-sm text-green-600 flex-shrink-0 mt-0.5">email</span>
                                <span class="break-all">{{ $patient->email }}</span>
                            </p>
                        </div>

                        @if($patient->personalData)
                            @if($patient->personalData->phone)
                                <div class="min-w-0">
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Teléfono</p>
                                    <p class="font-semibold text-gray-900 dark:text-white flex items-center gap-1 text-sm sm:text-base">
                                        <span class="material-symbols-outlined text-sm text-green-600 flex-shrink-0">phone</span>
                                        <span class="break-all">{{ $patient->personalData->phone }}</span>
                                    </p>
                                </div>
                            @endif

                            @if($patient->personalData->date_of_birth)
                                <div class="min-w-0">
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Edad</p>
                                    <p class="font-semibold text-gray-900 dark:text-white flex flex-wrap items-center gap-1 text-sm sm:text-base">
                                        <span class="material-symbols-outlined text-sm text-green-600 flex-shrink-0">cake</span>
                                        <span>{{ \Carbon\Carbon::parse($patient->personalData->date_of_birth)->age }} años</span>
                                        <span class="text-xs text-gray-500">({{ \Carbon\Carbon::parse($patient->personalData->date_of_birth)->format('d/m/Y') }})</span>
                                    </p>
                                </div>
                            @endif

                            @if($patient->personalData->gender)
                                <div class="min-w-0">
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Género</p>
                                    <p class="font-semibold text-gray-900 dark:text-white flex items-center gap-1 text-sm sm:text-base">
                                        <span class="material-symbols-outlined text-sm text-green-600 flex-shrink-0">
                                            {{ $patient->personalData->gender === 'male' ? 'male' : ($patient->personalData->gender === 'female' ? 'female' : 'transgender') }}
                                        </span>
                                        {{ $patient->personalData->gender === 'male' ? 'Masculino' : ($patient->personalData->gender === 'female' ? 'Femenino' : 'Otro') }}
                                    </p>
                                </div>
                            @endif

                            @if($patient->personalData->address)
                                <div class="md:col-span-2 min-w-0">
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Dirección</p>
                                    <p class="font-semibold text-gray-900 dark:text-white flex items-start gap-1 text-sm sm:text-base">
                                        <span class="material-symbols-outlined text-sm text-green-600 flex-shrink-0 mt-0.5">location_on</span>
                                        <span class="break-words">{{ $patient->personalData->address }}</span>
                                    </p>
                                </div>
                            @endif
                        @else
                            <div class="md:col-span-2">
                                <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-lg p-4">
                                    <p class="text-sm text-amber-800 dark:text-amber-200 flex items-center gap-2">
                                        <span class="material-symbols-outlined text-lg">info</span>
                                        El paciente aún no ha completado sus datos personales
                                    </p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Modal de Foto de Perfil -->
                <div x-show="showPhotoModal && {{ $patient->personalData?->profile_photo ? 'true' : 'false' }}"
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
                        <div class="bg-gradient-to-r from-green-600 to-emerald-600 px-6 py-4 flex items-center justify-between">
                            <h3 class="text-lg font-bold text-white flex items-center gap-2">
                                <span class="material-symbols-outlined">photo_camera</span>
                                Foto de Perfil - {{ $patient->name }}
                            </h3>
                            <button type="button" @click="showPhotoModal = false" class="text-white hover:text-gray-200 transition">
                                <span class="material-symbols-outlined">close</span>
                            </button>
                        </div>
                        
                        <!-- Image -->
                        <div class="p-6 overflow-auto max-h-[calc(90vh-140px)]">
                            @if($patient->personalData?->profile_photo)
                                <img src="{{ asset('storage/' . $patient->personalData->profile_photo) }}" 
                                     alt="{{ $patient->name }}"
                                     class="w-full h-auto rounded-lg">
                            @endif
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

            <livewire:nutricionista.patient-data-form :patient="$patient" :appointmentId="$appointment" />
        </div>
    </main>

    @include('layouts.footer')
</body>
@endsection
