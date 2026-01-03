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
                            <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $patient->isActive() ? 'bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-400' : 'bg-gray-200 text-gray-700 dark:bg-gray-600 dark:text-gray-300' }}">
                                {{ ucfirst($patient->userState->name) }}
                            </span>
                        </div>

                        <!-- Nombre y Email -->
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1">{{ $patient->name }}</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 truncate">{{ $patient->email }}</p>
                    </div>

                    <!-- Estadísticas -->
                    <div class="p-6 space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Total de Citas</span>
                            <span class="text-lg font-bold text-gray-900 dark:text-white">{{ $patient->total_appointments }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Completadas</span>
                            <span class="text-lg font-bold text-green-600 dark:text-green-400">{{ $patient->completed_appointments }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Próximas</span>
                            <span class="text-lg font-bold text-blue-600 dark:text-blue-400">{{ $patient->pending_appointments }}</span>
                        </div>

                        <!-- Indicador de Cita Próxima -->
                        @if($patient->has_upcoming_appointment)
                            <div class="mt-4 p-3 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                                <p class="text-xs text-blue-800 dark:text-blue-400 font-semibold flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                    </svg>
                                    Tiene cita próxima
                                </p>
                            </div>
                        @endif
                    </div>

                    <!-- Botones de Acción -->
                    <div class="p-6 pt-0 space-y-3">
                        <!-- Botón de Datos Personales -->
                        @if(!$patient->personalData)
                            <a 
                                href="{{ route('nutricionista.patients.data', $patient) }}"
                                class="block w-full text-center px-4 py-3 bg-gradient-to-r from-amber-500 to-orange-500 text-white font-semibold rounded-lg hover:from-amber-600 hover:to-orange-600 transition-all duration-200 shadow-md hover:shadow-lg flex items-center justify-center gap-2"
                            >
                                <span class="material-symbols-outlined text-xl">edit_note</span>
                                Completar Datos Personales
                            </a>
                        @else
                            <a 
                                href="{{ route('nutricionista.patients.data', $patient) }}"
                                class="block w-full text-center px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 font-semibold rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-all duration-200 flex items-center justify-center gap-2"
                            >
                                <span class="material-symbols-outlined text-lg">visibility</span>
                                Ver Datos Personales
                            </a>
                        @endif

                        <!-- Botón Ver Historial -->
                        <a 
                            href="{{ route('nutricionista.patients.show', $patient) }}"
                            class="block w-full text-center px-4 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white font-semibold rounded-lg hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-md hover:shadow-lg"
                        >
                            Ver Historial Completo
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
