<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-8 bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-4 sm:p-6 border border-gray-200 dark:border-gray-700">
        <div class="flex items-center justify-between gap-3">
            <div class="flex items-center gap-3 flex-1 min-w-0">
                <a href="{{ route('nutricionista.dashboard') }}" class="text-gray-700 dark:text-gray-300 hover:text-green-600 dark:hover:text-green-400 hover:scale-110 transition-all">
                    <span class="material-symbols-outlined text-2xl">arrow_back</span>
                </a>
                <div class="min-w-0 flex-1">
                    <h1 class="text-2xl sm:text-3xl font-bold bg-gradient-to-r from-green-600 to-emerald-600 dark:from-green-400 dark:to-emerald-400 bg-clip-text text-transparent">
                        Mi Perfil Profesional
                    </h1>
                    <p class="text-sm sm:text-base text-gray-700 dark:text-gray-300 mt-1 truncate">
                        Gestiona tu información profesional y configuración de cuenta
                    </p>
                </div>
            </div>
            <div class="flex-shrink-0 hidden sm:flex items-center">
                <span class="material-symbols-outlined text-3xl sm:text-4xl text-green-600 dark:text-green-400">person</span>
            </div>
        </div>
    </div>

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
                                        document.getElementById('profile_photo').value = ''; 
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
                                        <button @click="showModal = false; $nextTick(() => document.getElementById('profile_photo').click())" 
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
                        Nutricionista
                    </span>
                </div>

                <div class="border-t dark:border-gray-700 mt-6 pt-6 space-y-4">
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase font-semibold mb-1">Rol</p>
                        <p class="text-gray-900 dark:text-white font-medium">Nutricionista</p>
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
                    Información Profesional
                </h2>

                @if(session('error'))
                    <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4 dark:bg-red-900/20 dark:border-red-800">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-red-600 dark:text-red-400">error</span>
                            <p class="text-red-900 dark:text-red-300 font-semibold">{{ session('error') }}</p>
                        </div>
                    </div>
                @endif

                <form wire:submit.prevent="saveProfile" class="space-y-6">
                    <!-- Nombre -->
                    <div>
                        <label for="name" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Nombre Completo *
                        </label>
                        <input 
                            type="text" 
                            wire:model="name" 
                            id="name"
                            class="w-full px-4 py-3 rounded-lg border-2 border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white focus:border-green-500 focus:ring-2 focus:ring-green-200 dark:focus:ring-green-800 transition"
                            placeholder="Tu nombre completo">
                        @error('name') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <!-- Email (solo lectura) -->
                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Correo Electrónico
                        </label>
                        <input 
                            type="email" 
                            value="{{ $email }}" 
                            disabled
                            class="w-full px-4 py-3 rounded-lg border-2 border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-700 dark:text-gray-400 cursor-not-allowed">
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">El correo electrónico no puede ser modificado</p>
                    </div>

                    <!-- Teléfono -->
                    <div>
                        <label for="phone" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Teléfono de Contacto
                        </label>
                        <input 
                            type="text" 
                            wire:model="phone" 
                            id="phone"
                            maxlength="10"
                            class="w-full px-4 py-3 rounded-lg border-2 border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white focus:border-green-500 focus:ring-2 focus:ring-green-200 dark:focus:ring-green-800 transition"
                            placeholder="10 dígitos">
                        @error('phone') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <!-- Precio de Consulta -->
                    <div>
                        <label for="consultation_price" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2 flex items-center gap-2">
                            <span class="material-symbols-outlined text-lg">payments</span>
                            Precio de Consulta ($) *
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-500 dark:text-gray-400 font-semibold">
                                $
                            </span>
                            <input 
                                type="number" 
                                step="0.01" 
                                min="0"
                                max="999999.99"
                                wire:model="consultation_price" 
                                id="consultation_price"
                                class="w-full pl-8 pr-4 py-3 rounded-lg border-2 border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white focus:border-green-500 focus:ring-2 focus:ring-green-200 dark:focus:ring-green-800 transition"
                                placeholder="30.00">
                        </div>
                        @error('consultation_price') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Este precio se mostrará a los pacientes al agendar una cita contigo</p>
                    </div>

                    <!-- Foto de Perfil -->
                    <div x-data="{ isCompressing: false }" 
                         @compression-complete.window="isCompressing = false"
                         @photo-cleared.window="isCompressing = false">
                        <label for="profile_photo" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2 flex items-center gap-2">
                            <span class="material-symbols-outlined text-lg">photo_camera</span>
                            Foto de Perfil
                        </label>
                        <div class="flex items-center gap-4">
                            <input 
                                type="file" 
                                id="profile_photo"
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

                    <!-- Botones de Acción -->
                    <div class="flex gap-4">
                        <button 
                            type="submit"
                            wire:loading.attr="disabled"
                            class="flex-1 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white font-bold py-3 px-6 rounded-lg transition-all duration-200 flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
                            <span wire:loading.remove wire:target="saveProfile" class="material-symbols-outlined">save</span>
                            <svg wire:loading wire:target="saveProfile" class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span wire:loading.remove wire:target="saveProfile">Guardar Cambios</span>
                            <span wire:loading wire:target="saveProfile">Guardando...</span>
                        </button>
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

                <form action="{{ route('nutricionista.profile.update-password') }}" method="POST" 
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
                            <div x-data="{ showCurrentPassword: false }">
                                <label for="current_password" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                    Contraseña Actual *
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
                                        class="w-full px-4 py-3 pr-12 rounded-lg border-2 border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white focus:border-green-500 focus:ring-2 focus:ring-green-200 dark:focus:ring-green-800 transition @error('current_password') border-red-500 @enderror">
                                    <button 
                                        type="button"
                                        @click="showCurrentPassword = !showCurrentPassword"
                                        class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-colors"
                                    >
                                        <span class="material-symbols-outlined" x-show="!showCurrentPassword">visibility</span>
                                        <span class="material-symbols-outlined" x-show="showCurrentPassword" x-cloak>visibility_off</span>
                                    </button>
                                </div>
                                @error('current_password') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                            </div>
                        @endif

                        <div x-data="{ showPassword: false }">
                            <label for="password" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                Nueva Contraseña *
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
                                    class="w-full px-4 py-3 pr-12 rounded-lg border-2 border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white focus:border-green-500 focus:ring-2 focus:ring-green-200 dark:focus:ring-green-800 transition @error('password') border-red-500 @enderror">
                                <button 
                                    type="button"
                                    @click="showPassword = !showPassword"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-colors"
                                >
                                    <span class="material-symbols-outlined" x-show="!showPassword">visibility</span>
                                    <span class="material-symbols-outlined" x-show="showPassword" x-cloak>visibility_off</span>
                                </button>
                            </div>
                            @error('password') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                            <p class="mt-2 text-xs text-gray-600 dark:text-gray-400">
                                Mínimo 8 caracteres
                            </p>
                        </div>

                        <div x-data="{ showPasswordConfirmation: false }">
                            <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                Confirmar Nueva Contraseña *
                            </label>
                            <div class="relative">
                                <input 
                                    :type="showPasswordConfirmation ? 'text' : 'password'" 
                                    id="password_confirmation"
                                    name="password_confirmation"
                                    autocomplete="new-password"
                                    required
                                    minlength="8"
                                    x-model="passwordConfirmation"
                                    class="w-full px-4 py-3 pr-12 rounded-lg border-2 border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white focus:border-green-500 focus:ring-2 focus:ring-green-200 dark:focus:ring-green-800 transition">
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

                        <button 
                            type="submit"
                            :disabled="!isValid"
                            :class="isValid ? 'bg-gradient-to-r from-blue-600 to-cyan-600 hover:from-blue-700 hover:to-cyan-700 cursor-pointer' : 'bg-gray-400 dark:bg-gray-600 cursor-not-allowed opacity-60'"
                            class="w-full text-white font-bold py-3 px-6 rounded-lg transition-all duration-200 flex items-center justify-center gap-2">
                            <span class="material-symbols-outlined">lock_reset</span>
                            <span x-text="isValid ? 'Actualizar Contraseña' : 'Completa todos los campos (mínimo 8 caracteres)'"></span>
                        </button>
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

    <!-- Image Compressor Script -->
    <script src="{{ asset('js/image-compressor.js') }}"></script>
    <script>
        initializeImageCompressor('profile_photo', 'profile_photo');
    </script>

</div>
