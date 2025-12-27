<div>
    <!-- Filtros -->
    <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
        <div class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Búsqueda por nombre o email -->
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-2">
                        🔍 Buscar Paciente
                    </label>
                    <input 
                        type="text" 
                        id="search" 
                        wire:model.live.debounce.500ms="search"
                        value="{{ $search }}"
                        placeholder="Nombre o email..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                    >
                </div>

                <!-- Estado de Usuario -->
                <div>
                    <label for="estado" class="block text-sm font-medium text-gray-700 mb-2">
                        👤 Estado
                    </label>
                    <select 
                        id="estado" 
                        wire:model.live="estado"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
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
                    <label for="proximas_citas" class="block text-sm font-medium text-gray-700 mb-2">
                        📅 Filtros Especiales
                    </label>
                    <select 
                        id="proximas_citas" 
                        wire:model.live="proximas_citas"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                    >
                        <option value="">Todos los pacientes</option>
                        <option value="1">Con citas próximas</option>
                    </select>
                </div>

                <!-- Ordenar Por -->
                <div>
                    <label for="sort" class="block text-sm font-medium text-gray-700 mb-2">
                        📊 Ordenar Por
                    </label>
                    <select 
                        id="sort" 
                        wire:model.live="sort"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                    >
                        <option value="name">Nombre (A-Z)</option>
                        <option value="recent">Más Recientes</option>
                    </select>
                </div>
            </div>

            <!-- Botón Limpiar Filtros -->
            <div class="flex justify-end">
                <button 
                    wire:click="clearFilters"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition-all duration-200"
                >
                    <span class="material-symbols-outlined text-lg">filter_alt_off</span>
                    Limpiar Filtros
                </button>
            </div>
        </div>
    </div>

    @if($patients->isEmpty())
        <!-- Sin Resultados -->
        <div class="bg-white rounded-xl shadow-lg p-12 text-center">
            <div class="text-6xl mb-4">🔍</div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">No se encontraron pacientes</h3>
            <p class="text-gray-600 mb-6">
                @if($search || $estado || $proximas_citas)
                    Intenta ajustar los filtros de búsqueda
                @else
                    Aún no tienes pacientes registrados
                @endif
            </p>
            @if($search || $estado || $proximas_citas)
                <button 
                    wire:click="clearFilters"
                    class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white font-semibold rounded-lg hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-lg hover:shadow-xl"
                >
                    Ver Todos los Pacientes
                </button>
            @endif
        </div>
    @else
        <!-- Grid de Pacientes -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($patients as $patient)
                <div class="bg-white rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden">
                    <!-- Header con Estado -->
                    <div class="p-6 {{ $patient->isActive() ? 'bg-gradient-to-r from-green-50 to-emerald-50' : 'bg-gray-100' }}">
                        <div class="flex items-start justify-between mb-4">
                            <!-- Avatar -->
                            <div class="flex-shrink-0">
                                <div class="w-16 h-16 bg-gradient-to-br from-green-400 to-emerald-500 rounded-full flex items-center justify-center text-white text-xl font-bold shadow-lg">
                                    {{ $patient->initials() }}
                                </div>
                            </div>

                            <!-- Estado Badge -->
                            <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $patient->isActive() ? 'bg-green-100 text-green-800' : 'bg-gray-200 text-gray-700' }}">
                                {{ ucfirst($patient->userState->name) }}
                            </span>
                        </div>

                        <!-- Nombre y Email -->
                        <h3 class="text-lg font-bold text-gray-900 mb-1">{{ $patient->name }}</h3>
                        <p class="text-sm text-gray-600 truncate">{{ $patient->email }}</p>
                    </div>

                    <!-- Estadísticas -->
                    <div class="p-6 space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Total de Citas</span>
                            <span class="text-lg font-bold text-gray-900">{{ $patient->total_appointments }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Completadas</span>
                            <span class="text-lg font-bold text-green-600">{{ $patient->completed_appointments }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Próximas</span>
                            <span class="text-lg font-bold text-blue-600">{{ $patient->pending_appointments }}</span>
                        </div>

                        <!-- Indicador de Cita Próxima -->
                        @if($patient->has_upcoming_appointment)
                            <div class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                                <p class="text-xs text-blue-800 font-semibold flex items-center">
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
                                class="block w-full text-center px-4 py-2 bg-gray-100 text-gray-700 font-semibold rounded-lg hover:bg-gray-200 transition-all duration-200 flex items-center justify-center gap-2"
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
