<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-8 bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-4 sm:p-6 border border-gray-200 dark:border-gray-700">
        <div class="flex items-center justify-between gap-3">
            <div class="flex items-center gap-3 flex-1 min-w-0">
                <a href="{{ route('paciente.dashboard') }}" class="text-gray-700 dark:text-gray-300 hover:text-green-600 dark:hover:text-green-400 hover:scale-110 transition-all">
                    <span class="material-symbols-outlined text-2xl">arrow_back</span>
                </a>
                <div class="min-w-0 flex-1">
                    <h1 class="text-2xl sm:text-3xl font-bold bg-gradient-to-r from-green-600 to-emerald-600 dark:from-green-400 dark:to-emerald-400 bg-clip-text text-transparent">
                        Mi Perfil
                    </h1>
                    <p class="text-sm sm:text-base text-gray-700 dark:text-gray-300 mt-1 truncate">
                        Gestiona tu información personal y configuración de cuenta
                    </p>
                </div>
            </div>
            <div class="flex-shrink-0 hidden sm:flex items-center">
                <span class="material-symbols-outlined text-3xl sm:text-4xl text-green-600 dark:text-green-400">person</span>
            </div>
        </div>
    </div>

    @if(!$hasPersonalData)
        <!-- Alerta: Sin Datos Personales -->
        <div class="bg-amber-50 border border-amber-200 rounded-xl p-6 mb-8 dark:bg-amber-900/20 dark:border-amber-800">
            <div class="flex items-start gap-4">
                <span class="material-symbols-outlined text-amber-600 text-3xl dark:text-amber-400">info</span>
                <div>
                    <h3 class="text-lg font-bold text-amber-900 dark:text-amber-300 mb-2">Datos Personales Incompletos</h3>
                    <p class="text-amber-800 dark:text-amber-400 mb-3">
                        Tu nutricionista aún no ha completado tus datos personales. Estos datos se completarán durante tu primera consulta.
                    </p>
                    <p class="text-sm text-amber-700 dark:text-amber-500 mb-2">
                        Una vez completados, podrás actualizar algunos campos desde aquí.
                    </p>
                    <p class="text-sm text-amber-700 dark:text-amber-500 flex items-center gap-2">
                        <span class="material-symbols-outlined text-lg">photo_camera</span>
                        <span>También podrás agregar tu foto de perfil una vez que tus datos personales estén registrados.</span>
                    </p>
                </div>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Columna Izquierda: Avatar e Información de Cuenta -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Card de Avatar -->
            <div class="bg-white dark:bg-gray-900 rounded-xl shadow-lg p-6"
                 x-data="{ previewUrl: null }"
                 @photo-preview.window="previewUrl = $event.detail.url"
                 @photo-cleared.window="previewUrl = null">
                <div class="text-center">
                    <!-- Foto de Perfil o Iniciales -->
                    <template x-if="previewUrl">
                        <!-- Vista previa de nueva foto (desde JavaScript) -->
                        <div class="relative inline-block mb-4">
                            <img :src="previewUrl" alt="Vista previa" class="w-24 h-24 rounded-full object-cover shadow-lg mx-auto">
                            <button @click="
                                        previewUrl = null; 
                                        document.getElementById('profile_photo_paciente').value = ''; 
                                        window.dispatchEvent(new CustomEvent('photo-cleared'));
                                    " 
                                    type="button" 
                                    class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600 transition">
                                <span class="material-symbols-outlined text-sm">close</span>
                            </button>
                        </div>
                    </template>
                    <template x-if="!previewUrl && '{{ $profile_photo_path }}'">
                        <!-- Foto guardada -->
                        <div class="relative inline-block mb-4" x-data="{ showModal: false }">
                            <img src="{{ asset('storage/' . $profile_photo_path) }}" 
                                 alt="{{ $name }}" 
                                 @click="showModal = true"
                                 class="w-24 h-24 rounded-full object-cover shadow-lg mx-auto cursor-pointer hover:opacity-90 transition">
                            
                            <!-- Modal inline -->
                            <div x-show="showModal"
                                 x-cloak
                                 @keydown.escape.window="showModal = false"
                                 class="fixed inset-0 z-50 flex items-center justify-center p-4"
                                 style="display: none;">
                                
                                <!-- Overlay -->
                                <div class="fixed inset-0 bg-black/60 dark:bg-black/80" @click="showModal = false"></div>
                                
                                <!-- Modal Content -->
                                <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-hidden"
                                     @click.stop>
                                    
                                    <!-- Header -->
                                    <div class="bg-gradient-to-r from-green-600 to-emerald-600 px-6 py-4 flex items-center justify-between">
                                        <h3 class="text-lg font-bold text-white flex items-center gap-2">
                                            <span class="material-symbols-outlined">photo_camera</span>
                                            Foto de Perfil
                                        </h3>
                                        <button @click="showModal = false" class="text-white hover:text-gray-200 transition">
                                            <span class="material-symbols-outlined">close</span>
                                        </button>
                                    </div>
                                    
                                    <!-- Image -->
                                    <div class="p-6 overflow-auto max-h-[calc(90vh-140px)]">
                                        <img src="{{ asset('storage/' . $profile_photo_path) }}" 
                                             alt="{{ $name }}" 
                                             class="w-full h-auto rounded-lg">
                                    </div>
                                    
                                    <!-- Footer -->
                                    <div class="bg-gray-50 dark:bg-gray-700 px-6 py-4 flex justify-between items-center">
                                        <button @click="showModal = false; $nextTick(() => document.getElementById('profile_photo_paciente').click())" 
                                                class="px-6 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-lg hover:from-green-700 hover:to-emerald-700 transition flex items-center gap-2">
                                            <span class="material-symbols-outlined text-sm">photo_camera</span>
                                            Cambiar Foto
                                        </button>
                                        <button @click="showModal = false" 
                                                class="px-6 py-2 bg-gray-200 dark:bg-gray-600 text-gray-800 dark:text-gray-200 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-500 transition">
                                            Cerrar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                    <template x-if="!previewUrl && !'{{ $profile_photo_path }}'">
                        <!-- Iniciales por defecto -->
                        <div class="w-24 h-24 bg-gradient-to-br from-green-400 to-emerald-500 rounded-full flex items-center justify-center text-white text-3xl font-bold shadow-lg mx-auto mb-4">
                            {{ strtoupper(substr($name, 0, 1)) }}{{ strtoupper(substr(explode(' ', $name)[1] ?? '', 0, 1)) }}
                        </div>
                    </template>
                    
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ $name }}</h2>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ $email }}</p>
                    <span class="inline-block mt-3 px-3 py-1 bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400 text-xs font-semibold rounded-full">
                        Paciente
                    </span>
                </div>

                <div class="border-t dark:border-gray-700 mt-6 pt-6 space-y-4">
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase font-semibold mb-1">Rol</p>
                        <p class="text-gray-900 dark:text-white font-medium">Paciente</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase font-semibold mb-1">Cuenta Creada</p>
                        <p class="text-gray-900 dark:text-white font-medium">{{ auth()->user()->created_at->format('d/m/Y') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Columna Derecha: Formularios -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Información de Perfil -->
            <div class="bg-white dark:bg-gray-900 rounded-xl shadow-lg p-8">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
                    <span class="material-symbols-outlined text-3xl">assignment</span>
                    Información de Perfil
                </h2>

                <form wire:submit.prevent="saveProfile">
                    <div class="space-y-6">
                        <!-- Foto de Perfil -->
                        @if($hasPersonalData)
                            <div x-data="{ isCompressing: false }">
                                <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-2 flex items-center gap-2">
                                    <span class="material-symbols-outlined text-lg">photo_camera</span>
                                    Foto de Perfil
                                </label>
                                <div class="flex items-center gap-4">
                                    <input 
                                        type="file" 
                                        id="profile_photo_paciente"
                                        accept="image/*"
                                        @change="isCompressing = true"
                                        class="block w-full text-sm text-gray-900 dark:text-gray-300 border border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer bg-white dark:bg-gray-800 focus:ring-2 focus:ring-green-500 focus:border-transparent file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100 dark:file:bg-green-900/30 dark:file:text-green-400 dark:hover:file:bg-green-800/50 file:transition-colors"
                                    >
                                </div>
                                @error('profile_photo')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                                <p class="mt-2 text-xs text-gray-600 dark:text-gray-400">
                                    Formatos permitidos: JPG, PNG, GIF. Tamaño máximo: 2MB. Las imágenes se comprimen automáticamente.
                                </p>
                                <div x-show="isCompressing" x-cloak class="mt-2 text-sm text-amber-600 dark:text-amber-400 flex items-center gap-2">
                                    <span class="material-symbols-outlined animate-spin text-sm">sync</span>
                                    Comprimiendo imagen...
                                </div>
                                <div wire:loading wire:target="profile_photo" class="mt-2 text-sm text-blue-600 dark:text-blue-400 flex items-center gap-2">
                                    <span class="material-symbols-outlined animate-spin text-sm">progress_activity</span>
                                    Subiendo imagen al servidor...
                                </div>
                            </div>
                        @endif

                        <!-- Nombre (Editable) -->
                        <div>
                            <label for="name" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2 flex items-center gap-2">
                                <span class="material-symbols-outlined text-lg">person</span>
                                Nombre Completo
                            </label>
                            <input 
                                type="text" 
                                id="name"
                                wire:model="name"
                                required
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-green-500 focus:border-transparent @error('name') border-red-500 @enderror"
                            >
                            @error('name')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email (Solo Lectura) -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-2 flex items-center gap-2">
                                <span class="material-symbols-outlined text-lg">mail</span>
                                Correo Electrónico
                            </label>
                            <div class="relative">
                                <input 
                                    type="email" 
                                    value="{{ $email }}"
                                    disabled
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-800 text-gray-700 dark:text-gray-300 cursor-not-allowed"
                                >
                                <div class="absolute right-3 top-3 flex items-center gap-2 text-gray-500 dark:text-gray-400">
                                    <span class="material-symbols-outlined text-sm">lock</span>
                                    <span class="text-xs">Solo lectura</span>
                                </div>
                            </div>
                            <p class="mt-2 text-xs text-gray-600 dark:text-gray-400">El correo electrónico no puede ser modificado.</p>
                        </div>

                        @if($hasPersonalData)
                            <!-- Grid para campos de información personal -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Género (Solo Lectura) -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-2 flex items-center gap-2">
                                        <span class="material-symbols-outlined text-lg">wc</span>
                                        Sexo
                                    </label>
                                    <div class="relative">
                                        <input 
                                            type="text" 
                                            value="{{ 
                                                $gender === 'male' ? 'Masculino' : 
                                                ($gender === 'female' ? 'Femenino' : 'No especificado')
                                            }}"
                                            disabled
                                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-800 text-gray-700 dark:text-gray-300 cursor-not-allowed"
                                        >
                                        <div class="absolute right-3 top-3 flex items-center gap-2 text-gray-500 dark:text-gray-400">
                                            <span class="material-symbols-outlined text-sm">lock</span>
                                            <span class="text-xs">Solo lectura</span>
                                        </div>
                                    </div>
                                    <p class="mt-2 text-xs text-gray-600 dark:text-gray-400">Este campo es asignado por el nutricionista.</p>
                                </div>

                                <!-- Fecha de Nacimiento (Editable) -->
                                <div>
                                    <label for="birth_date" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2 flex items-center gap-2">
                                        <span class="material-symbols-outlined text-lg">cake</span>
                                        Fecha de Nacimiento
                                    </label>
                                    <input 
                                        type="date" 
                                        id="birth_date"
                                        wire:model="birth_date"
                                        max="{{ now()->format('Y-m-d') }}"
                                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-green-500 focus:border-transparent @error('birth_date') border-red-500 @enderror"
                                    >
                                    @error('birth_date')
                                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                    @if($age)
                                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Edad actual: <span class="font-semibold">{{ $age }} años</span></p>
                                    @endif
                                </div>
                            </div>

                            <!-- Teléfono (Editable) -->
                            <div>
                                <label for="phone" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2 flex items-center gap-2">
                                    <span class="material-symbols-outlined text-lg">phone</span>
                                    Teléfono
                                </label>
                                <input 
                                    type="text" 
                                    id="phone"
                                    wire:model="phone"
                                    maxlength="10"
                                    placeholder="Ej: 1234567890"
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-green-500 focus:border-transparent @error('phone') border-red-500 @enderror"
                                >
                                @error('phone')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Dirección (Editable) -->
                            <div>
                                <label for="address" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2 flex items-center gap-2">
                                    <span class="material-symbols-outlined text-lg">home</span>
                                    Dirección
                                </label>
                                <textarea 
                                    id="address"
                                    wire:model="address"
                                    rows="3"
                                    placeholder="Ej: Calle Principal 123, Ciudad"
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-green-500 focus:border-transparent @error('address') border-red-500 @enderror"
                                ></textarea>
                                @error('address')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        @endif

                        <!-- Botón Guardar Perfil -->
                        <div class="border-t dark:border-gray-700 pt-6">
                            <button type="submit" 
                                    class="w-full flex items-center justify-center gap-2 rounded-lg bg-green-600 px-6 py-3 text-sm font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-4 focus:ring-green-300 dark:bg-green-600 dark:hover:bg-green-700">
                                <span class="material-symbols-outlined text-lg">save</span>
                                Guardar Cambios de Perfil
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Cambiar Contraseña (Formulario Tradicional - Sin Livewire) -->
            <div class="bg-white dark:bg-gray-900 rounded-xl shadow-lg p-8" id="password-section">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
                    <span class="material-symbols-outlined text-3xl">lock</span>
                    Cambiar Contraseña
                </h2>

                @if ($errors->has('current_password') || $errors->has('password') || $errors->has('password_confirmation'))
                    <div class="mb-6 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-800 dark:text-red-200 px-4 py-3 rounded-lg">
                        <div class="flex items-start gap-3">
                            <span class="material-symbols-outlined text-red-600 dark:text-red-400 flex-shrink-0">error</span>
                            <div class="flex-1">
                                <h3 class="font-bold mb-1">Error al actualizar contraseña</h3>
                                <ul class="list-disc list-inside space-y-1 text-sm">
                                    @foreach (['current_password', 'password', 'password_confirmation'] as $field)
                                        @error($field)
                                            <li>{{ $message }}</li>
                                        @enderror
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                <form action="{{ route('paciente.profile.update-password') }}" method="POST" 
                      x-data="{ 
                          currentPassword: '', 
                          password: '', 
                          passwordConfirmation: '',
                          get isValid() {
                              @if($hasPassword)
                                  return this.currentPassword.length >= 8 && this.password.length >= 8 && this.passwordConfirmation.length >= 8;
                              @else
                                  return this.password.length >= 8 && this.passwordConfirmation.length >= 8;
                              @endif
                          }
                      }">
                    @csrf
                    <div class="space-y-6">
                        @if($hasPassword)
                            <!-- Contraseña Actual -->
                            <div x-data="{ showCurrentPassword: false }">
                                <label for="current_password" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2 flex items-center gap-2">
                                    <span class="material-symbols-outlined text-lg">vpn_key</span>
                                    Contraseña Actual
                                </label>
                                <div class="relative">
                                    <input 
                                        :type="showCurrentPassword ? 'text' : 'password'" 
                                        id="current_password"
                                        name="current_password"
                                        autocomplete="current-password"
                                        required
                                        x-model="currentPassword"
                                        value="{{ old('current_password') }}"
                                        class="w-full px-4 py-3 pr-12 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-green-500 focus:border-transparent @error('current_password') border-red-500 @enderror"
                                    >
                                    <button 
                                        type="button"
                                        @click="showCurrentPassword = !showCurrentPassword"
                                        class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-colors"
                                    >
                                        <span class="material-symbols-outlined" x-show="!showCurrentPassword">visibility</span>
                                        <span class="material-symbols-outlined" x-show="showCurrentPassword" x-cloak>visibility_off</span>
                                    </button>
                                </div>
                                @error('current_password')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        @else
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 dark:bg-blue-900/20 dark:border-blue-800">
                                <div class="flex items-start gap-3">
                                    <span class="material-symbols-outlined text-blue-600 dark:text-blue-400">info</span>
                                    <div>
                                        <p class="text-sm text-blue-900 dark:text-blue-300">
                                            Iniciaste sesión con Google. Puedes establecer una contraseña aquí si deseas iniciar sesión también con correo y contraseña.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Nueva Contraseña -->
                        <div x-data="{ showPassword: false }">
                            <label for="password" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2 flex items-center gap-2">
                                <span class="material-symbols-outlined text-lg">key</span>
                                Nueva Contraseña
                            </label>
                            <div class="relative">
                                <input 
                                    :type="showPassword ? 'text' : 'password'" 
                                    id="password"
                                    name="password"
                                    autocomplete="new-password"
                                    required
                                    minlength="8"
                                    x-model="password"
                                    class="w-full px-4 py-3 pr-12 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-green-500 focus:border-transparent @error('password') border-red-500 @enderror"
                                >
                                <button 
                                    type="button"
                                    @click="showPassword = !showPassword"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-colors"
                                >
                                    <span class="material-symbols-outlined" x-show="!showPassword">visibility</span>
                                    <span class="material-symbols-outlined" x-show="showPassword" x-cloak>visibility_off</span>
                                </button>
                            </div>
                            @error('password')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                            <p class="mt-2 text-xs text-gray-600 dark:text-gray-400">
                                Mínimo 8 caracteres
                            </p>
                        </div>

                        <!-- Confirmar Nueva Contraseña -->
                        <div x-data="{ showPasswordConfirmation: false }">
                            <label for="password_confirmation" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2 flex items-center gap-2">
                                <span class="material-symbols-outlined text-lg">key</span>
                                Confirmar Nueva Contraseña
                            </label>
                            <div class="relative">
                                <input 
                                    :type="showPasswordConfirmation ? 'text' : 'password'" 
                                    required
                                    minlength="8"
                                    x-model="passwordConfirmation"
                                    id="password_confirmation"
                                    name="password_confirmation"
                                    autocomplete="new-password"
                                    class="w-full px-4 py-3 pr-12 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                >
                                <button 
                                    type="button"
                                    @click="showPasswordConfirmation = !showPasswordConfirmation"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-colors"
                                >
                                    <span class="material-symbols-outlined" x-show="!showPasswordConfirmation">visibility</span>
                                    <span class="material-symbols-outlined" x-show="showPasswordConfirmation" x-cloak>visibility_off</span>
                                </button>
                            </div>
                        </div>

                        <!-- Botón Cambiar Contraseña -->
                        <div class="border-t dark:border-gray-700 pt-6">
                            <button type="submit" 
                                    :disabled="!isValid"
                                    :class="isValid ? 'bg-green-600 hover:bg-green-700 dark:bg-green-600 dark:hover:bg-green-700 cursor-pointer' : 'bg-gray-400 dark:bg-gray-600 cursor-not-allowed opacity-60'"
                                    class="w-full flex items-center justify-center gap-2 rounded-lg px-6 py-3 text-sm font-medium text-white focus:outline-none focus:ring-4 focus:ring-green-300 transition-all">
                                <span class="material-symbols-outlined text-lg">lock</span>
                                <span x-text="isValid ? 'Actualizar Contraseña' : 'Completa todos los campos (mínimo 8 caracteres)'"></span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @if ($errors->has('current_password') || $errors->has('password') || $errors->has('password_confirmation'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const passwordSection = document.getElementById('password-section');
                if (passwordSection) {
                    passwordSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        </script>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const fileInput = document.getElementById('profile_photo_paciente');
            
            if (fileInput) {
                fileInput.addEventListener('change', async function(e) {
                    const file = e.target.files[0];
                    
                    if (!file || !file.type.startsWith('image/')) {
                        return;
                    }

                    try {
                        // Comprimir la imagen
                        const { compressedFile, previewUrl } = await compressImage(file, {
                            maxWidth: 1920,
                            maxHeight: 1920,
                            quality: 0.8,
                            maxSizeMB: 2
                        });

                        // Mostrar vista previa
                        window.dispatchEvent(new CustomEvent('photo-preview', { 
                            detail: { url: previewUrl } 
                        }));

                        // Crear un nuevo DataTransfer para asignar el archivo comprimido
                        const dataTransfer = new DataTransfer();
                        dataTransfer.items.add(compressedFile);
                        fileInput.files = dataTransfer.files;

                        // Actualizar Livewire manualmente
                        const livewireElement = fileInput.closest('[wire\\:id]');
                        if (livewireElement) {
                            const componentId = livewireElement.getAttribute('wire:id');
                            const livewireComponent = window.Livewire.find(componentId);
                            
                            if (livewireComponent) {
                                livewireComponent.upload('profile_photo', compressedFile, 
                                    // Success callback
                                    () => {
                                        console.log('Imagen subida correctamente');
                                    },
                                    // Error callback
                                    (error) => {
                                        console.error('Error al subir la imagen:', error);
                                    },
                                    // Progress callback
                                    (event) => {
                                        console.log('Progreso:', Math.round((event.detail.progress || 0)) + '%');
                                    }
                                );
                            }
                        }

                    } catch (error) {
                        console.error('Error al comprimir la imagen:', error);
                        alert('Error al procesar la imagen. Por favor, intenta con otra imagen.');
                    }
                });
            }
                                    console.log('Progreso:', Math.round((event.detail.progress || 0)));
                                }
                            );
                        }

                    } catch (error) {
                        console.error('Error al comprimir la imagen:', error);
                        alert('Error al procesar la imagen. Por favor, intenta con otra imagen.');
                    }
                });
            }

            /**
             * Comprime una imagen reduciendo su resolución y calidad
             */
            async function compressImage(file, options = {}) {
                const {
                    maxWidth = 1920,
                    maxHeight = 1920,
                    quality = 0.8,
                    maxSizeMB = 2
                } = options;

                return new Promise((resolve, reject) => {
                    const reader = new FileReader();
                    
                    reader.onload = function(e) {
                        const img = new Image();
                        
                        img.onload = function() {
                            // Calcular nuevas dimensiones manteniendo el aspect ratio
                            let width = img.width;
                            let height = img.height;
                            
                            if (width > maxWidth || height > maxHeight) {
                                const aspectRatio = width / height;
                                
                                if (width > height) {
                                    width = maxWidth;
                                    height = width / aspectRatio;
                                } else {
                                    height = maxHeight;
                                    width = height * aspectRatio;
                                }
                            }

                            // Crear canvas y redimensionar
                            const canvas = document.createElement('canvas');
                            canvas.width = width;
                            canvas.height = height;
                            
                            const ctx = canvas.getContext('2d');
                            ctx.drawImage(img, 0, 0, width, height);

                            // Función recursiva para ajustar la calidad hasta alcanzar el tamaño deseado
                            let currentQuality = quality;
                            
                            function attemptCompression() {
                                canvas.toBlob(function(blob) {
                                    const sizeMB = blob.size / 1024 / 1024;
                                    
                                    // Si el tamaño es aceptable o la calidad ya es muy baja, usar este blob
                                    if (sizeMB <= maxSizeMB || currentQuality <= 0.1) {
                                        const fileName = file.name.replace(/\.[^/.]+$/, '') + '_compressed.jpg';
                                        const compressedFile = new File([blob], fileName, {
                                            type: 'image/jpeg',
                                            lastModified: Date.now()
                                        });
                                        
                                        // Crear URL para vista previa
                                        const previewUrl = URL.createObjectURL(blob);
                                        
                                        console.log(`Imagen comprimida: ${(file.size / 1024 / 1024).toFixed(2)}MB → ${sizeMB.toFixed(2)}MB`);
                                        resolve({ compressedFile, previewUrl });
                                    } else {
                                        // Reducir calidad y volver a intentar
                                        currentQuality -= 0.1;
                                        attemptCompression();
                                    }
                                }, 'image/jpeg', currentQuality);
                            }
                            
                            attemptCompression();
                        };
                        
                        img.onerror = function() {
                            reject(new Error('No se pudo cargar la imagen'));
                        };
                        
                        img.src = e.target.result;
                    };
                    
                    reader.onerror = function() {
                        reject(new Error('No se pudo leer el archivo'));
                    };
                    
                    reader.readAsDataURL(file);
                });
            }
        });
    </script>

</div>
