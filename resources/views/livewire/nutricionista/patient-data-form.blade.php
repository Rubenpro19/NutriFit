<div>
    <!-- Mensajes de Error y Advertencias -->
    @if($errors->any())
        <div class="mb-6 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl p-4">
            <div class="flex items-start gap-3">
                <span class="material-symbols-outlined text-red-600 dark:text-red-400">error</span>
                <div>
                    <p class="text-red-900 dark:text-red-200 font-semibold mb-2">Por favor corrige los siguientes errores:</p>
                    <ul class="list-disc list-inside text-sm text-red-800 dark:text-red-300 space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    @if(session('warning'))
        <div class="mb-6 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-xl p-4">
            <div class="flex items-center gap-3">
                <span class="material-symbols-outlined text-amber-600 dark:text-amber-400">warning</span>
                <p class="text-amber-900 dark:text-amber-200 font-semibold">{{ session('warning') }}</p>
            </div>
        </div>
    @endif

    <!-- Formulario -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-4 sm:p-6 lg:p-8 border border-gray-200 dark:border-gray-700">
        <div class="mb-6">
            <h2 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                <span class="material-symbols-outlined text-green-600">{{ $hasPersonalData ? 'visibility' : 'edit_note' }}</span>
                {{ $hasPersonalData ? 'Datos Personales (Solo Lectura)' : 'Completar Datos Personales' }}
            </h2>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                {{ $hasPersonalData ? 'Los datos personales ya han sido registrados y no pueden modificarse.' : 'Completa la información personal del paciente. Los campos marcados con * son obligatorios.' }}
            </p>
        </div>

        <form wire:submit="save">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Cédula -->
                <div>
                    <label for="cedula" class="flex items-center gap-2 text-sm font-semibold text-gray-800 dark:text-gray-200 mb-2">
                        <span class="material-symbols-outlined text-lg text-green-600 dark:text-green-400">badge</span>
                        Cédula
                    </label>
                    <input 
                        type="text" 
                        id="cedula"
                        wire:model="cedula"
                        inputmode="numeric"
                        pattern="[0-9]*"
                        maxlength="20"
                        placeholder="Número de cédula (solo números)"
                        @if($isReadOnly) disabled @endif
                        class="w-full px-3 sm:px-4 py-2.5 text-sm sm:text-base rounded-lg border-2 border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all @error('cedula') border-red-500 @enderror @if($isReadOnly) bg-gray-100 dark:bg-gray-900 cursor-not-allowed @endif"
                    >
                    @error('cedula')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Teléfono -->
                <div>
                    <label for="phone" class="flex items-center gap-2 text-sm font-semibold text-gray-800 dark:text-gray-200 mb-2">
                        <span class="material-symbols-outlined text-lg text-green-600 dark:text-green-400">phone</span>
                        Teléfono
                    </label>
                    <input 
                        type="text" 
                        id="phone"
                        wire:model="phone"
                        inputmode="numeric"
                        pattern="[0-9]*"
                        maxlength="10"
                        placeholder="10 dígitos (solo números)"
                        @if($isReadOnly) disabled @endif
                        class="w-full px-3 sm:px-4 py-2.5 text-sm sm:text-base rounded-lg border-2 border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all @error('phone') border-red-500 @enderror @if($isReadOnly) bg-gray-100 dark:bg-gray-900 cursor-not-allowed @endif"
                    >
                    @error('phone')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Género -->
                <div>
                    <label for="gender" class="flex items-center gap-2 text-sm font-semibold text-gray-800 dark:text-gray-200 mb-2">
                        <span class="material-symbols-outlined text-lg text-green-600 dark:text-green-400">person</span>
                        Género <span class="text-red-500 dark:text-red-400">*</span>
                    </label>
                    <select 
                        id="gender" 
                        wire:model="gender"
                        @if($isReadOnly) disabled @endif
                        class="w-full px-3 sm:px-4 py-2.5 text-sm sm:text-base rounded-lg border-2 border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all @error('gender') border-red-500 @enderror @if($isReadOnly) bg-gray-100 dark:bg-gray-900 cursor-not-allowed @endif [&>option]:bg-white [&>option]:dark:bg-gray-700"
                    >
                        <option value="">Seleccione</option>
                        <option value="male">Masculino</option>
                        <option value="female">Femenino</option>
                        <option value="other">Otro</option>
                    </select>
                    @error('gender')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Fecha de Nacimiento -->
                <div>
                    <label for="birth_date" class="flex items-center gap-2 text-sm font-semibold text-gray-800 dark:text-gray-200 mb-2">
                        <span class="material-symbols-outlined text-lg text-green-600 dark:text-green-400">cake</span>
                        Fecha de Nacimiento <span class="text-red-500 dark:text-red-400">*</span>
                    </label>
                    <input 
                        type="date" 
                        id="birth_date"
                        wire:model="birth_date"
                        max="{{ date('Y-m-d') }}"
                        @if($isReadOnly) disabled @endif
                        class="w-full px-3 sm:px-4 py-2.5 text-sm sm:text-base rounded-lg border-2 border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all @error('birth_date') border-red-500 @enderror @if($isReadOnly) bg-gray-100 dark:bg-gray-900 cursor-not-allowed @endif"
                    >
                    @error('birth_date')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                    @if($birth_date && $patient->personalData)
                        <p class="mt-2 text-sm text-green-600 dark:text-green-400 font-medium">
                            Edad: {{ $patient->personalData->age }} años
                        </p>
                    @endif
                </div>

                <!-- Dirección -->
                <div class="md:col-span-2">
                    <label for="address" class="flex items-center gap-2 text-sm font-semibold text-gray-800 dark:text-gray-200 mb-2">
                        <span class="material-symbols-outlined text-lg text-green-600 dark:text-green-400">home</span>
                        Dirección
                    </label>
                    <textarea 
                        id="address"
                        wire:model="address"
                        rows="3"
                        placeholder="Dirección completa"
                        @if($isReadOnly) disabled @endif
                        class="w-full px-3 sm:px-4 py-2.5 text-sm sm:text-base rounded-lg border-2 border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all @error('address') border-red-500 @enderror @if($isReadOnly) bg-gray-100 dark:bg-gray-900 cursor-not-allowed @endif"
                    ></textarea>
                    @error('address')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Botones -->
            <div class="mt-8 flex flex-col sm:flex-row justify-end gap-3">
                <a 
                    href="{{ route('nutricionista.patients.index') }}"
                    class="px-6 py-3 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 font-semibold rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-all duration-200 text-center"
                >
                    {{ $isReadOnly ? 'Volver' : 'Cancelar' }}
                </a>
                
                @if(!$isReadOnly)
                    <button 
                        type="submit"
                        wire:loading.attr="disabled"
                        class="flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white font-semibold rounded-lg hover:from-green-700 hover:to-emerald-700 hover:shadow-lg hover:scale-105 active:scale-95 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:scale-100"
                    >
                        <span class="material-symbols-outlined" wire:loading.remove>save</span>
                        <svg wire:loading class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span wire:loading.remove>Guardar Datos Personales</span>
                        <span wire:loading>Guardando...</span>
                    </button>
                @endif
            </div>
        </form>
    </div>
</div>