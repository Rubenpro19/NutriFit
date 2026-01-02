<div class="max-w-5xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center gap-4 mb-4">
            <a 
                href="{{ route('nutricionista.patients.index') }}"
                class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors"
            >
                <span class="material-symbols-outlined dark:text-white">arrow_back</span>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                    @if($hasPersonalData)
                        📋 Datos Personales del Paciente
                    @else
                        ✏️ Completar Datos Personales
                    @endif
                </h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">
                    Paciente: <span class="font-semibold">{{ $patient->name }}</span>
                </p>
            </div>
        </div>

            @if($hasPersonalData)
                <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                    <div class="flex items-start gap-3">
                        <span class="material-symbols-outlined text-blue-600 dark:text-blue-400 mt-0.5">info</span>
                        <div>
                            <p class="text-blue-900 dark:text-blue-100 font-semibold">Este paciente ya tiene datos personales</p>
                            <p class="text-blue-700 dark:text-blue-300 text-sm mt-1">Los datos se muestran en modo de solo lectura. El género no puede ser modificado una vez asignado.</p>
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
                    <div class="flex items-start gap-3">
                        <span class="material-symbols-outlined text-green-600 dark:text-green-400 mt-0.5">task_alt</span>
                        <div>
                            <p class="text-green-900 dark:text-green-100 font-semibold">Complete los datos personales del paciente</p>
                            <p class="text-green-700 dark:text-green-300 text-sm mt-1">Los campos de género y fecha de nacimiento son obligatorios. El género no podrá ser modificado posteriormente.</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Formulario -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-8">
            @if(session('success'))
                <div class="mb-6 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-green-600 dark:text-green-400">check_circle</span>
                        <p class="text-green-900 dark:text-green-200 font-semibold">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if(session('warning'))
                <div class="mb-6 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-lg p-4">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-amber-600 dark:text-amber-400">warning</span>
                        <p class="text-amber-900 dark:text-amber-200 font-semibold">{{ session('warning') }}</p>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-red-600 dark:text-red-400">error</span>
                        <p class="text-red-900 dark:text-red-200 font-semibold">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            <form wire:submit.prevent="save">
                <div class="space-y-6">
                    <!-- Género -->
                    <div>
                        <label for="gender" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                            👤 Género <span class="text-red-500 dark:text-red-400">*</span>
                        </label>
                        <select 
                            id="gender" 
                            wire:model="gender"
                            @if($isReadOnly) disabled @endif
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-green-500 focus:border-transparent @error('gender') border-red-500 @enderror @if($isReadOnly) bg-gray-100 dark:bg-gray-900 cursor-not-allowed @endif"
                        >
                            <option value="">Seleccione el género</option>
                            <option value="male">Masculino</option>
                            <option value="female">Femenino</option>
                            <option value="other">Otro</option>
                        </select>
                        @error('gender')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                        @if($isReadOnly)
                            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400 flex items-center gap-1">
                                <span class="material-symbols-outlined text-xs">lock</span>
                                Este campo no puede ser modificado
                            </p>
                        @endif
                    </div>

                    <!-- Fecha de Nacimiento -->
                    <div>
                        <label for="birth_date" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                            🎂 Fecha de Nacimiento <span class="text-red-500 dark:text-red-400">*</span>
                        </label>
                        <input 
                            type="date" 
                            id="birth_date"
                            wire:model="birth_date"
                            max="{{ date('Y-m-d') }}"
                            @if($isReadOnly) disabled @endif
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-green-500 focus:border-transparent @error('birth_date') border-red-500 @enderror @if($isReadOnly) bg-gray-100 dark:bg-gray-900 cursor-not-allowed @endif"
                        >
                        @error('birth_date')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                        @if($birth_date && $patient->personalData)
                            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                                Edad: <span class="font-semibold">{{ $patient->personalData->age }} años</span>
                            </p>
                        @endif
                    </div>

                    <!-- Teléfono -->
                    <div>
                        <label for="phone" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                            📱 Teléfono <span class="text-gray-400 dark:text-gray-500 text-xs">(Opcional)</span>
                        </label>
                        <input 
                            type="text" 
                            id="phone"
                            wire:model="phone"
                            maxlength="10"
                            placeholder="Ej: 1234567890"
                            @if($isReadOnly) disabled @endif
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-green-500 focus:border-transparent @error('phone') border-red-500 @enderror @if($isReadOnly) bg-gray-100 dark:bg-gray-900 cursor-not-allowed @endif"
                        >
                        @error('phone')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Dirección -->
                    <div>
                        <label for="address" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                            🏠 Dirección <span class="text-gray-400 dark:text-gray-500 text-xs">(Opcional)</span>
                        </label>
                        <textarea 
                            id="address"
                            wire:model="address"
                            rows="3"
                            placeholder="Dirección completa del paciente"
                            @if($isReadOnly) disabled @endif
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-green-500 focus:border-transparent @error('address') border-red-500 @enderror @if($isReadOnly) bg-gray-100 dark:bg-gray-900 cursor-not-allowed @endif"
                        ></textarea>
                        @error('address')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Botones -->
                <div class="mt-8 flex justify-end gap-4">
                    <a 
                        href="{{ route('nutricionista.patients.index') }}"
                        class="px-6 py-3 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 font-semibold rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-all duration-200"
                    >
                        @if($isReadOnly)
                            Volver
                        @else
                            Cancelar
                        @endif
                    </a>
                    
                    @if(!$isReadOnly)
                        <button 
                            type="submit"
                            class="px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white font-semibold rounded-lg hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-lg hover:shadow-xl"
                        >
                            💾 Guardar Datos Personales
                        </button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>