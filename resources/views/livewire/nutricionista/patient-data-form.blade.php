<div class="max-w-5xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center gap-4 mb-4">
            <a 
                href="{{ route('nutricionista.patients.index') }}"
                class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-gray-100 hover:bg-gray-200 transition-colors"
            >
                <span class="material-symbols-outlined">arrow_back</span>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-gray-900">
                    @if($hasPersonalData)
                        📋 Datos Personales del Paciente
                    @else
                        ✏️ Completar Datos Personales
                    @endif
                </h1>
                <p class="text-gray-600 mt-1">
                    Paciente: <span class="font-semibold">{{ $patient->name }}</span>
                </p>
            </div>
        </div>

            @if($hasPersonalData)
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex items-start gap-3">
                        <span class="material-symbols-outlined text-blue-600 mt-0.5">info</span>
                        <div>
                            <p class="text-blue-900 font-semibold">Este paciente ya tiene datos personales</p>
                            <p class="text-blue-700 text-sm mt-1">Los datos se muestran en modo de solo lectura. El género no puede ser modificado una vez asignado.</p>
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                    <div class="flex items-start gap-3">
                        <span class="material-symbols-outlined text-green-600 mt-0.5">task_alt</span>
                        <div>
                            <p class="text-green-900 font-semibold">Complete los datos personales del paciente</p>
                            <p class="text-green-700 text-sm mt-1">Los campos de género y fecha de nacimiento son obligatorios. El género no podrá ser modificado posteriormente.</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Formulario -->
        <div class="bg-white rounded-xl shadow-lg p-8">
            @if(session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-green-600">check_circle</span>
                        <p class="text-green-900 font-semibold">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if(session('warning'))
                <div class="mb-6 bg-amber-50 border border-amber-200 rounded-lg p-4">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-amber-600">warning</span>
                        <p class="text-amber-900 font-semibold">{{ session('warning') }}</p>
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

            <form wire:submit.prevent="save">
                <div class="space-y-6">
                    <!-- Género -->
                    <div>
                        <label for="gender" class="block text-sm font-semibold text-gray-900 mb-2">
                            👤 Género <span class="text-red-500">*</span>
                        </label>
                        <select 
                            id="gender" 
                            wire:model="gender"
                            @if($isReadOnly) disabled @endif
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('gender') border-red-500 @enderror @if($isReadOnly) bg-gray-100 cursor-not-allowed @endif"
                        >
                            <option value="">Seleccione el género</option>
                            <option value="male">Masculino</option>
                            <option value="female">Femenino</option>
                            <option value="other">Otro</option>
                        </select>
                        @error('gender')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        @if($isReadOnly)
                            <p class="mt-2 text-sm text-gray-600 flex items-center gap-1">
                                <span class="material-symbols-outlined text-xs">lock</span>
                                Este campo no puede ser modificado
                            </p>
                        @endif
                    </div>

                    <!-- Fecha de Nacimiento -->
                    <div>
                        <label for="birth_date" class="block text-sm font-semibold text-gray-900 mb-2">
                            🎂 Fecha de Nacimiento <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="date" 
                            id="birth_date"
                            wire:model="birth_date"
                            max="{{ date('Y-m-d') }}"
                            @if($isReadOnly) disabled @endif
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('birth_date') border-red-500 @enderror @if($isReadOnly) bg-gray-100 cursor-not-allowed @endif"
                        >
                        @error('birth_date')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        @if($birth_date && $patient->personalData)
                            <p class="mt-2 text-sm text-gray-600">
                                Edad: <span class="font-semibold">{{ $patient->personalData->age }} años</span>
                            </p>
                        @endif
                    </div>

                    <!-- Teléfono -->
                    <div>
                        <label for="phone" class="block text-sm font-semibold text-gray-900 mb-2">
                            📱 Teléfono <span class="text-gray-400 text-xs">(Opcional)</span>
                        </label>
                        <input 
                            type="text" 
                            id="phone"
                            wire:model="phone"
                            maxlength="10"
                            placeholder="Ej: 1234567890"
                            @if($isReadOnly) disabled @endif
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('phone') border-red-500 @enderror @if($isReadOnly) bg-gray-100 cursor-not-allowed @endif"
                        >
                        @error('phone')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Dirección -->
                    <div>
                        <label for="address" class="block text-sm font-semibold text-gray-900 mb-2">
                            🏠 Dirección <span class="text-gray-400 text-xs">(Opcional)</span>
                        </label>
                        <textarea 
                            id="address"
                            wire:model="address"
                            rows="3"
                            placeholder="Dirección completa del paciente"
                            @if($isReadOnly) disabled @endif
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('address') border-red-500 @enderror @if($isReadOnly) bg-gray-100 cursor-not-allowed @endif"
                        ></textarea>
                        @error('address')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Botones -->
                <div class="mt-8 flex justify-end gap-4">
                    <a 
                        href="{{ route('nutricionista.patients.index') }}"
                        class="px-6 py-3 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition-all duration-200"
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