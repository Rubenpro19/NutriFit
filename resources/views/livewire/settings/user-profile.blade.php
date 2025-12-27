<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">👤 Mi Perfil</h1>
        <p class="text-gray-600">Visualiza y actualiza tu información personal</p>
    </div>

        @if(!$hasPersonalData)
            <!-- Alerta: Sin Datos Personales -->
            <div class="bg-amber-50 border border-amber-200 rounded-xl p-6 mb-8">
                <div class="flex items-start gap-4">
                    <span class="material-symbols-outlined text-amber-600 text-3xl">info</span>
                    <div>
                        <h3 class="text-lg font-bold text-amber-900 mb-2">Datos Personales Incompletos</h3>
                        <p class="text-amber-800 mb-3">
                            Tu nutricionista aún no ha completado tus datos personales. Estos datos se completarán durante tu primera consulta.
                        </p>
                        <p class="text-sm text-amber-700">
                            Una vez completados, podrás actualizar tu teléfono y dirección desde aquí.
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Columna Izquierda: Información de Cuenta -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <div class="text-center mb-6">
                        <div class="w-24 h-24 bg-gradient-to-br from-green-400 to-emerald-500 rounded-full flex items-center justify-center text-white text-3xl font-bold shadow-lg mx-auto mb-4">
                            {{ strtoupper(substr($name, 0, 1)) }}{{ strtoupper(substr(explode(' ', $name)[1] ?? '', 0, 1)) }}
                        </div>
                        <h2 class="text-xl font-bold text-gray-900">{{ $name }}</h2>
                        <p class="text-sm text-gray-600 mt-1">{{ $email }}</p>
                        <span class="inline-block mt-3 px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">
                            Paciente
                        </span>
                    </div>

                    <div class="border-t pt-6 space-y-4">
                        <div>
                            <p class="text-xs text-gray-500 uppercase font-semibold mb-1">Rol</p>
                            <p class="text-gray-900 font-medium">Paciente</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase font-semibold mb-1">Cuenta Creada</p>
                            <p class="text-gray-900 font-medium">{{ auth()->user()->created_at->format('d/m/Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Columna Derecha: Datos Personales -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-lg p-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">📋 Datos Personales</h2>

                    @if(session('success'))
                        <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4">
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-green-600">check_circle</span>
                                <p class="text-green-900 font-semibold">{{ session('success') }}</p>
                            </div>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-red-600">error</span>
                                <p class="text-red-900 font-semibold">{{ session('error') }}</p>
                            </div>
                        </div>
                    @endif

                    @if($hasPersonalData)
                        <form wire:submit.prevent="save">
                            <div class="space-y-6">
                                <!-- Género (Solo Lectura) -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-900 mb-2">
                                        👤 Género
                                    </label>
                                    <div class="relative">
                                        <input 
                                            type="text" 
                                            value="{{ 
                                                $gender === 'male' ? 'Masculino' : 
                                                ($gender === 'female' ? 'Femenino' : 'Otro')
                                            }}"
                                            disabled
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 text-gray-700 cursor-not-allowed"
                                        >
                                        <div class="absolute right-3 top-3 flex items-center gap-2 text-gray-500">
                                            <span class="material-symbols-outlined text-sm">lock</span>
                                            <span class="text-xs">Solo lectura</span>
                                        </div>
                                    </div>
                                    <p class="mt-2 text-xs text-gray-600">Este campo es asignado por el nutricionista y no puede ser modificado.</p>
                                </div>

                                <!-- Fecha de Nacimiento (Solo Lectura) -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-900 mb-2">
                                        🎂 Fecha de Nacimiento
                                    </label>
                                    <div class="relative">
                                        <input 
                                            type="text" 
                                            value="{{ $birth_date ? \Carbon\Carbon::parse($birth_date)->format('d/m/Y') : 'No especificada' }}"
                                            disabled
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 text-gray-700 cursor-not-allowed"
                                        >
                                        <div class="absolute right-3 top-3 flex items-center gap-2 text-gray-500">
                                            <span class="material-symbols-outlined text-sm">lock</span>
                                            <span class="text-xs">Solo lectura</span>
                                        </div>
                                    </div>
                                    @if($age)
                                        <p class="mt-2 text-sm text-gray-600">Edad actual: <span class="font-semibold">{{ $age }} años</span></p>
                                    @endif
                                </div>

                                <div class="border-t pt-6">
                                    <p class="text-sm font-semibold text-gray-900 mb-4 flex items-center gap-2">
                                        <span class="material-symbols-outlined text-green-600">edit</span>
                                        Campos Editables
                                    </p>

                                    <!-- Teléfono (Editable) -->
                                    <div class="mb-6">
                                        <label for="phone" class="block text-sm font-semibold text-gray-900 mb-2">
                                            📱 Teléfono
                                        </label>
                                        <input 
                                            type="text" 
                                            id="phone"
                                            wire:model="phone"
                                            maxlength="10"
                                            placeholder="Ej: 1234567890"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('phone') border-red-500 @enderror"
                                        >
                                        @error('phone')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Dirección (Editable) -->
                                    <div>
                                        <label for="address" class="block text-sm font-semibold text-gray-900 mb-2">
                                            🏠 Dirección
                                        </label>
                                        <textarea 
                                            id="address"
                                            wire:model="address"
                                            rows="3"
                                            placeholder="Tu dirección completa"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('address') border-red-500 @enderror"
                                        ></textarea>
                                        @error('address')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Botón Guardar -->
                            <div class="mt-8 flex justify-end">
                                <button 
                                    type="submit"
                                    class="px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white font-semibold rounded-lg hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-lg hover:shadow-xl flex items-center gap-2"
                                >
                                    <span class="material-symbols-outlined">save</span>
                                    Guardar Cambios
                                </button>
                            </div>
                        </form>
                    @else
                        <!-- Placeholder cuando no hay datos -->
                        <div class="text-center py-12">
                            <span class="material-symbols-outlined text-6xl text-gray-300 mb-4">person_off</span>
                            <p class="text-gray-600 text-lg mb-2">No hay datos personales disponibles</p>
                            <p class="text-gray-500 text-sm">Contacta a tu nutricionista para completar esta información</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>