<div>
    <!-- Filtros -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 mb-6 border border-gray-200 dark:border-gray-700">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="w-full">
                <label for="estado" class="flex items-center gap-1 text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    <span class="material-symbols-outlined text-base">filter_alt</span>
                    Filtrar por estado
                </label>
                <select wire:model.live="estado" id="estado" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    <option value="">Todas las citas</option>
                    <option value="pendiente">Pendientes</option>
                    <option value="completada">Completadas</option>
                    <option value="cancelada">Canceladas</option>
                    <option value="vencida">Vencidas</option>
                </select>
            </div>

            <div class="w-full">
                <label for="nutricionista" class="flex items-center gap-1 text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    <span class="material-symbols-outlined text-base">person</span>
                    Filtrar por nutricionista
                </label>
                <select wire:model.live="nutricionista" id="nutricionista" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    <option value="">Todos los nutricionistas</option>
                    @foreach($nutricionistas as $nut)
                        <option value="{{ $nut->id }}">{{ $nut->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="w-full">
                <label for="fecha_desde" class="flex items-center gap-1 text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    <span class="material-symbols-outlined text-base">event</span>
                    Fecha desde
                </label>
                <input wire:model.live="fecha_desde" type="date" id="fecha_desde" 
                       class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-green-500 focus:border-transparent">
            </div>

            <div class="w-full">
                <label for="fecha_hasta" class="flex items-center gap-1 text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    <span class="material-symbols-outlined text-base">event</span>
                    Fecha hasta
                </label>
                <input wire:model.live="fecha_hasta" type="date" id="fecha_hasta" 
                       class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-green-500 focus:border-transparent">
            </div>
        </div>

        <div class="flex justify-center mt-4">
            <button wire:click="limpiarFiltros" type="button" class="flex items-center gap-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 font-semibold py-2 px-6 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                <span class="material-symbols-outlined">refresh</span>
                Limpiar
            </button>
        </div>
    </div>

    <!-- Lista de Citas -->
    @if($appointments->count() > 0)
        <div class="grid gap-6 mb-8">
            @foreach($appointments as $appointment)
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden border border-gray-200 dark:border-gray-700 hover:shadow-xl transition">
                    <div class="p-6">
                        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                            <!-- Info del Nutricionista -->
                            <div class="flex items-start gap-4 flex-1">
                                @if($appointment->nutricionista->personalData?->profile_photo)
                                    <img src="{{ asset('storage/' . $appointment->nutricionista->personalData->profile_photo) }}" 
                                         alt="{{ $appointment->nutricionista->name }}"
                                         class="w-16 h-16 rounded-full object-cover border-2 border-gray-200 dark:border-gray-600 flex-shrink-0">
                                @else
                                    <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-500 rounded-full flex items-center justify-center text-white font-bold text-xl flex-shrink-0 border-2 border-gray-200 dark:border-gray-600">
                                        {{ substr($appointment->nutricionista->name, 0, 1) }}
                                    </div>
                                @endif
                                <div class="flex-1">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">
                                        {{ $appointment->nutricionista->name }}
                                    </h3>
                                    <div class="flex flex-wrap gap-4 text-sm text-gray-600 dark:text-gray-400">
                                        <div class="flex items-center gap-1">
                                            <span class="material-symbols-outlined text-sm">calendar_month</span>
                                            <span>{{ $appointment->start_time->format('d/m/Y') }}</span>
                                        </div>
                                        <div class="flex items-center gap-1">
                                            <span class="material-symbols-outlined text-sm">schedule</span>
                                            <span>{{ $appointment->start_time->format('h:i A') }} - {{ $appointment->end_time->format('h:i A') }}</span>
                                        </div>
                                        <div class="flex items-center gap-1">
                                            <span class="material-symbols-outlined text-sm">category</span>
                                            <span>{{ $appointment->appointment_type === 'primera_vez' ? 'Primera vez' : 'Seguimiento' }}</span>
                                        </div>
                                    </div>
                                    @if($appointment->reason)
                                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                                            <span class="font-medium">Motivo:</span> {{ Str::limit($appointment->reason, 100) }}
                                        </p>
                                    @endif
                                </div>
                            </div>

                            <!-- Estado y Acciones -->
                            <div class="flex flex-col items-end gap-3">
                                @php
                                    $statusClasses = [
                                        'pendiente' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400',
                                        'completada' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
                                        'cancelada' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400',
                                        'vencida' => 'bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-400',
                                    ];
                                    $statusIcons = [
                                        'pendiente' => 'pending',
                                        'completada' => 'check_circle',
                                        'cancelada' => 'cancel',
                                        'vencida' => 'event_busy',
                                    ];
                                @endphp
                                <span class="flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold {{ $statusClasses[$appointment->appointmentState->name] ?? 'bg-gray-100 text-gray-800' }}">
                                    <span class="material-symbols-outlined text-sm">{{ $statusIcons[$appointment->appointmentState->name] ?? 'help' }}</span>
                                    {{ ucfirst($appointment->appointmentState->name) }}
                                </span>

                                <div class="flex gap-2">
                                    @if($appointment->appointmentState->name === 'completada')
                                        <a href="{{ route('paciente.appointments.show', $appointment) }}" 
                                           class="flex items-center gap-1 bg-gradient-to-r from-green-600 to-emerald-600 text-white font-semibold py-2 px-4 rounded-lg hover:from-green-700 hover:to-emerald-700 transition text-sm">
                                            <span class="material-symbols-outlined text-sm">visibility</span>
                                            Ver Detalle
                                        </a>
                                    @elseif($appointment->appointmentState->name === 'pendiente')
                                        <a href="{{ route('paciente.appointments.show', $appointment) }}" 
                                           class="flex items-center gap-1 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 font-semibold py-2 px-4 rounded-lg hover:bg-green-200 dark:hover:bg-green-900/50 transition text-sm">
                                            <span class="material-symbols-outlined text-sm">info</span>
                                            Ver Info
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Paginación -->
        <div class="mt-8">
            {{ $appointments->links() }}
        </div>
    @else
        <!-- Estado Vacío -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-12 text-center border border-gray-200 dark:border-gray-700">
            <span class="material-symbols-outlined text-8xl text-gray-300 dark:text-gray-600 mb-4">calendar_month</span>
            <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">No hay citas</h3>
            <p class="text-gray-600 dark:text-gray-400 mb-6">
                @if($estado || $nutricionista || $fecha_desde || $fecha_hasta)
                    No se encontraron citas con los filtros seleccionados.
                @else
                    Aún no has agendado ninguna cita.
                @endif
            </p>
            <a href="{{ route('paciente.booking.index') }}" 
               class="inline-flex items-center gap-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white font-semibold py-3 px-6 rounded-lg hover:from-green-700 hover:to-emerald-700 transition shadow-lg">
                <span class="material-symbols-outlined">add</span>
                Agendar Nueva Cita
            </a>
        </div>
    @endif
</div>
