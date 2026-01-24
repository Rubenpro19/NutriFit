<div>
    <!-- Filtros mejorados -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 mb-8 border border-gray-200 dark:border-gray-700">
        <div class="space-y-4">
            <!-- Título de filtros -->
            <div class="flex items-center gap-2 mb-4">
                <span class="material-symbols-outlined text-green-600 dark:text-green-400">tune</span>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Filtros de búsqueda</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Búsqueda por nombre o email -->
                <div>
                    <label for="search" class="flex items-center gap-2 text-sm font-semibold text-gray-800 dark:text-gray-200 mb-2">
                        <span class="material-symbols-outlined text-lg text-green-600 dark:text-green-400">search</span>
                        Buscar Paciente
                    </label>
                    <input 
                        type="text" 
                        id="search" 
                        wire:model.live.debounce.500ms="search"
                        value="{{ $search }}"
                        placeholder="Nombre o email..."
                        class="w-full px-4 py-2.5 border-2 border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all"
                    >
                </div>

                <!-- Estado de Usuario -->
                <div>
                    <label for="estado" class="flex items-center gap-2 text-sm font-semibold text-gray-800 dark:text-gray-200 mb-2">
                        <span class="material-symbols-outlined text-lg text-green-600 dark:text-green-400">person</span>
                        Estado
                    </label>
                    <select 
                        id="estado" 
                        wire:model.live="estado"
                        class="w-full px-4 py-2.5 border-2 border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all [&>option]:bg-white [&>option]:dark:bg-gray-700 [&>option]:text-gray-900 [&>option]:dark:text-white"
                    >
                        <option value="">Todos los estados</option>
                        @foreach($userStates as $state)
                            <option value="{{ $state->id }}">
                                {{ ucfirst($state->name) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Solo con Citas Próximas -->
                <div>
                    <label for="proximas_citas" class="flex items-center gap-2 text-sm font-semibold text-gray-800 dark:text-gray-200 mb-2">
                        <span class="material-symbols-outlined text-lg text-green-600 dark:text-green-400">event</span>
                        Filtros Especiales
                    </label>
                    <select 
                        id="proximas_citas" 
                        wire:model.live="proximas_citas"
                        class="w-full px-4 py-2.5 border-2 border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all [&>option]:bg-white [&>option]:dark:bg-gray-700 [&>option]:text-gray-900 [&>option]:dark:text-white"
                    >
                        <option value="">Todos los pacientes</option>
                        <option value="1">Con citas próximas</option>
                    </select>
                </div>

                <!-- Ordenar Por -->
                <div>
                    <label for="sort" class="flex items-center gap-2 text-sm font-semibold text-gray-800 dark:text-gray-200 mb-2">
                        <span class="material-symbols-outlined text-lg text-green-600 dark:text-green-400">sort</span>
                        Ordenar Por
                    </label>
                    <select 
                        id="sort" 
                        wire:model.live="sort"
                        class="w-full px-4 py-2.5 border-2 border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all [&>option]:bg-white [&>option]:dark:bg-gray-700 [&>option]:text-gray-900 [&>option]:dark:text-white"
                    >
                        <option value="name">Nombre (A-Z)</option>
                        <option value="recent">Más Recientes</option>
                    </select>
                </div>
            </div>

            <!-- Botón Limpiar Filtros -->
            <div class="flex justify-end pt-2">
                <button 
                    wire:click="clearFilters"
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 font-semibold rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 hover:shadow-md hover:scale-105 active:scale-95 transition-all duration-200"
                >
                    <span class="material-symbols-outlined text-lg">filter_alt_off</span>
                    Limpiar Filtros
                </button>
            </div>
        </div>
    </div>

    @if($patients->isEmpty())
        <!-- Sin Resultados -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-12 text-center border border-gray-200 dark:border-gray-700">
            <span class="material-symbols-outlined text-8xl text-gray-300 dark:text-gray-600 mb-4">person_search</span>
            <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">No se encontraron pacientes</h3>
            <p class="text-gray-600 dark:text-gray-400 mb-6">
                @if($search || $estado || $proximas_citas)
                    Intenta ajustar los filtros de búsqueda
                @else
                    Aún no tienes pacientes registrados
                @endif
            </p>
            @if($search || $estado || $proximas_citas)
                <button 
                    wire:click="clearFilters"
                    class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white font-semibold rounded-lg hover:from-green-700 hover:to-emerald-700 hover:shadow-lg hover:scale-105 active:scale-95 transition-all duration-200"
                >
                    <span class="material-symbols-outlined">visibility</span>
                    Ver Todos los Pacientes
                </button>
            @endif
        </div>
    @else
        <!-- Grid de Pacientes -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($patients as $patient)
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden border border-gray-200 dark:border-gray-700">
                    <!-- Header con Estado -->
                    <div class="p-6 {{ $patient->isActive() ? 'bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20' : 'bg-gray-100 dark:bg-gray-700/50' }}">
                        <div class="flex items-start justify-between mb-4">
                            <!-- Avatar -->
                            <div class="flex-shrink-0">
                                <div class="w-16 h-16 rounded-full overflow-hidden flex items-center justify-center shadow-lg">
                                    @if($patient->personalData?->profile_photo)
                                        <img src="{{ asset('storage/' . $patient->personalData->profile_photo) }}" 
                                             alt="{{ $patient->name }}" 
                                             class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full bg-gradient-to-br from-green-400 to-emerald-500 flex items-center justify-center text-white text-xl font-bold">
                                            {{ $patient->initials() }}
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Estado Badge -->
                            <div class="flex flex-col items-end gap-1">
                                <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $patient->isActive() ? 'bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-400' : 'bg-gray-200 text-gray-700 dark:bg-gray-600 dark:text-gray-300' }}">
                                    {{ ucfirst($patient->userState->name) }}
                                </span>
                                @if(!$patient->estaHabilitadoClinicamente())
                                    <span class="px-2 py-0.5 text-xs font-medium rounded-full bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300 flex items-center gap-1">
                                        <span class="material-symbols-outlined text-xs">block</span>
                                        No habilitado
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Nombre y Email -->
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1">{{ $patient->name }}</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 truncate">{{ $patient->email }}</p>
                        
                        <!-- Motivo de Inhabilitación -->
                        @if(!$patient->estaHabilitadoClinicamente())
                            <div class="mt-3 p-2 bg-red-50 dark:bg-red-900/20 rounded-lg border border-red-200 dark:border-red-800">
                                <p class="text-xs text-red-800 dark:text-red-300 flex items-center gap-1">
                                    <span class="material-symbols-outlined text-sm">info</span>
                                    {{ $patient->motivoInhabilitacion() }}
                                </p>
                            </div>
                        @endif
                    </div>

                    <!-- Información del Paciente -->
                    <div class="p-6">
                        <!-- Próxima Cita -->
                        @php
                            $nextAppointment = $patient->appointmentsAsPaciente->first();
                        @endphp

                        @if($nextAppointment)
                            <div class="mb-4 p-4 bg-gradient-to-br from-blue-50 to-cyan-50 dark:from-blue-900/20 dark:to-cyan-900/20 border-2 border-blue-200 dark:border-blue-800 rounded-xl">
                                <div class="flex items-start gap-3 mb-3">
                                    <span class="material-symbols-outlined text-blue-600 dark:text-blue-400 text-xl mt-0.5">event_available</span>
                                    <div class="flex-1">
                                        <p class="text-xs font-semibold text-blue-800 dark:text-blue-400 mb-1">Próxima Cita</p>
                                        <p class="text-sm font-bold text-gray-900 dark:text-white">
                                            {{ \Carbon\Carbon::parse($nextAppointment->start_time)->locale('es')->isoFormat('dddd, D [de] MMMM') }}
                                        </p>
                                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-0.5">
                                            {{ \Carbon\Carbon::parse($nextAppointment->start_time)->format('H:i') }}
                                        </p>
                                    </div>
                                </div>
                                <a 
                                    href="{{ route('nutricionista.appointments.show', $nextAppointment) }}"
                                    class="flex items-center justify-center gap-2 w-full px-3 py-2 bg-white dark:bg-gray-800 text-blue-600 dark:text-blue-400 text-sm font-semibold rounded-lg hover:bg-blue-50 dark:hover:bg-blue-900/30 transition-all duration-200 border border-blue-200 dark:border-blue-700 hover:scale-[1.02] active:scale-95"
                                >
                                    <span class="material-symbols-outlined text-lg">visibility</span>
                                    Ver Detalle de Cita
                                </a>
                            </div>
                        @else
                            <div class="mb-4 p-4 bg-gray-50 dark:bg-gray-700/30 border border-gray-200 dark:border-gray-600 rounded-xl">
                                <div class="flex items-center gap-3">
                                    <span class="material-symbols-outlined text-gray-400 dark:text-gray-500 text-xl">event_busy</span>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Sin citas programadas</p>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Botón de Acción Principal -->
                    <div class="p-6 pt-0">
                        <a 
                            href="{{ route('nutricionista.patients.show', $patient) }}"
                            class="flex items-center justify-center gap-2 w-full px-4 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white font-semibold rounded-lg hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-md hover:shadow-lg hover:scale-[1.02] active:scale-95"
                        >
                            <span class="material-symbols-outlined">person_search</span>
                            Ver Detalles del Paciente
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Paginación -->
        <div class="mt-8">
            {{ $patients->links() }}
        </div>
    @endif
</div>
