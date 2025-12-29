<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">👤 Mi Perfil</h1>
        <p class="text-gray-600 dark:text-gray-400">Gestiona tu información personal y configuración de cuenta</p>
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
                    <p class="text-sm text-amber-700 dark:text-amber-500">
                        Una vez completados, podrás actualizar algunos campos desde aquí.
                    </p>
                </div>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Columna Izquierda: Avatar e Información de Cuenta -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Card de Avatar -->
            <div class="bg-white dark:bg-gray-900 rounded-xl shadow-lg p-6">
                <div class="text-center">
                    <div class="w-24 h-24 bg-gradient-to-br from-green-400 to-emerald-500 rounded-full flex items-center justify-center text-white text-3xl font-bold shadow-lg mx-auto mb-4">
                        {{ strtoupper(substr($name, 0, 1)) }}{{ strtoupper(substr(explode(' ', $name)[1] ?? '', 0, 1)) }}
                    </div>
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
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">📋 Información de Perfil</h2>

                @if(session('success'))
                    <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4 dark:bg-green-900/20 dark:border-green-800">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-green-600 dark:text-green-400">check_circle</span>
                            <p class="text-green-900 dark:text-green-300 font-semibold">{{ session('success') }}</p>
                        </div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4 dark:bg-red-900/20 dark:border-red-800">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-red-600 dark:text-red-400">error</span>
                            <p class="text-red-900 dark:text-red-300 font-semibold">{{ session('error') }}</p>
                        </div>
                    </div>
                @endif

                <form wire:submit.prevent="saveProfile">
                    <div class="space-y-6">
                        <!-- Nombre (Editable) -->
                        <div>
                            <label for="name" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                                👤 Nombre Completo
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
                            <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                                📧 Correo Electrónico
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
                            <!-- Género (Solo Lectura) -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                                    🚻 Sexo
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
                                <label for="birth_date" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                                    🎂 Fecha de Nacimiento
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

                            <!-- Teléfono (Editable) -->
                            <div>
                                <label for="phone" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                                    📱 Teléfono
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
                                <label for="address" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                                    🏠 Dirección
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

            <!-- Cambiar Contraseña -->
            <div class="bg-white dark:bg-gray-900 rounded-xl shadow-lg p-8">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">🔒 Cambiar Contraseña</h2>

                @if(session('password_success'))
                    <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4 dark:bg-green-900/20 dark:border-green-800">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-green-600 dark:text-green-400">check_circle</span>
                            <p class="text-green-900 dark:text-green-300 font-semibold">{{ session('password_success') }}</p>
                        </div>
                    </div>
                @endif

                @if(session('password_error'))
                    <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4 dark:bg-red-900/20 dark:border-red-800">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-red-600 dark:text-red-400">error</span>
                            <p class="text-red-900 dark:text-red-300 font-semibold">{{ session('password_error') }}</p>
                        </div>
                    </div>
                @endif

                <form wire:submit.prevent="updatePassword">
                    <div class="space-y-6">
                        @if($hasPassword)
                            <!-- Contraseña Actual -->
                            <div>
                                <label for="current_password" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                                    🔑 Contraseña Actual
                                </label>
                                <input 
                                    type="password" 
                                    id="current_password"
                                    wire:model="current_password"
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-green-500 focus:border-transparent @error('current_password') border-red-500 @enderror"
                                >
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
                        <div>
                            <label for="password" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                                🔐 Nueva Contraseña
                            </label>
                            <input 
                                type="password" 
                                id="password"
                                wire:model="password"
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-green-500 focus:border-transparent @error('password') border-red-500 @enderror"
                            >
                            @error('password')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Confirmar Nueva Contraseña -->
                        <div>
                            <label for="password_confirmation" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                                🔐 Confirmar Nueva Contraseña
                            </label>
                            <input 
                                type="password" 
                                id="password_confirmation"
                                wire:model="password_confirmation"
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-green-500 focus:border-transparent"
                            >
                        </div>

                        <!-- Botón Cambiar Contraseña -->
                        <div class="border-t dark:border-gray-700 pt-6">
                            <button type="submit" 
                                    class="w-full flex items-center justify-center gap-2 rounded-lg bg-green-600 px-6 py-3 text-sm font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-4 focus:ring-green-300 dark:bg-green-600 dark:hover:bg-green-700">
                                <span class="material-symbols-outlined text-lg">lock</span>
                                Actualizar Contraseña
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
