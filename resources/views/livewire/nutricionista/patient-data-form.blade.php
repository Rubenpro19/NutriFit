<div>
    @if($hasPersonalData)
        <div class="mb-6 bg-blue-50 dark:bg-blue-900/20 border-2 border-blue-300 dark:border-blue-700 rounded-lg p-4">
            <div class="flex items-start gap-3">
                <span class="material-symbols-outlined text-blue-600 dark:text-blue-400 mt-0.5">info</span>
                <div>
                    <p class="text-blue-900 dark:text-blue-100 font-semibold">Este paciente ya tiene datos personales</p>
                    <p class="text-blue-700 dark:text-blue-300 text-sm mt-1">Los datos se muestran en modo de solo lectura. El género no puede ser modificado una vez asignado.</p>
                </div>
            </div>
        </div>
    @else
        <div class="mb-6 bg-green-50 dark:bg-green-900/20 border-2 border-green-300 dark:border-green-700 rounded-lg p-4">
            <div class="flex items-start gap-3">
                <span class="material-symbols-outlined text-green-600 dark:text-green-400 mt-0.5">task_alt</span>
                <div>
                    <p class="text-green-900 dark:text-green-100 font-semibold">Complete los datos personales del paciente</p>
                    <p class="text-green-700 dark:text-green-300 text-sm mt-1">Los campos de género y fecha de nacimiento son obligatorios. El género no podrá ser modificado posteriormente.</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Formulario -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-4 sm:p-6 lg:p-8 border border-gray-200 dark:border-gray-700">
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
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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
                            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                                Edad: <span class="font-semibold">{{ $patient->personalData->age }} años</span>
                            </p>
                        @endif
                    </div>

                    <!-- Teléfono -->
                    <div>
                        <label for="phone" class="flex items-center gap-2 text-sm font-semibold text-gray-800 dark:text-gray-200 mb-2">
                            <span class="material-symbols-outlined text-lg text-green-600 dark:text-green-400">phone</span>
                            Teléfono <span class="text-gray-400 dark:text-gray-500 text-xs font-normal">(Opcional)</span>
                        </label>
                        <input 
                            type="text" 
                            id="phone"
                            wire:model="phone"
                            maxlength="10"
                            placeholder="Ej: 1234567890"
                            @if($isReadOnly) disabled @endif
                            class="w-full px-3 sm:px-4 py-2.5 text-sm sm:text-base rounded-lg border-2 border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all @error('phone') border-red-500 @enderror @if($isReadOnly) bg-gray-100 dark:bg-gray-900 cursor-not-allowed @endif"
                        >
                        @error('phone')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Dirección -->
                    <div class="md:col-span-2">
                        <label for="address" class="flex items-center gap-2 text-sm font-semibold text-gray-800 dark:text-gray-200 mb-2">
                            <span class="material-symbols-outlined text-lg text-green-600 dark:text-green-400">home</span>
                            Dirección <span class="text-gray-400 dark:text-gray-500 text-xs font-normal">(Opcional)</span>
                        </label>
                        <textarea 
                            id="address"
                            wire:model="address"
                            rows="3"
                            placeholder="Dirección completa del paciente"
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
                        @if($isReadOnly)
                            Volver
                        @else
                            Cancelar
                        @endif
                    </a>
                    
                    @if(!$isReadOnly)
                        <button 
                            type="submit"
                            class="flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white font-semibold rounded-lg hover:from-green-700 hover:to-emerald-700 hover:shadow-lg hover:scale-105 active:scale-95 transition-all duration-200"
                        >
                            <span class="material-symbols-outlined">save</span>
                            Guardar Datos Personales
                        </button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>