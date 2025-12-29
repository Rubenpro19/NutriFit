<x-layouts.app :title="__('Editar Usuario')">
    <div class="flex h-full w-full flex-1 flex-col gap-6">
        {{-- Encabezado --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Editar Usuario</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400">Actualiza la información del usuario</p>
            </div>
            <a href="{{ route('admin.users.index') }}" 
               class="flex items-center gap-2 rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-800">
                <span class="material-symbols-outlined text-base">arrow_back</span>
                Volver
            </a>
        </div>

        {{-- Contenedor principal --}}
        <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900">
            <div class="p-6">
                    {{-- Información del rol (no editable) --}}
                    <div class="mb-6 rounded-lg bg-blue-50 p-4 dark:bg-blue-900/20">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-blue-600 dark:text-blue-400">info</span>
                            <div>
                                <p class="text-sm font-medium text-blue-800 dark:text-blue-300">
                                    Rol actual: <span class="font-semibold">{{ ucfirst($user->role->name) }}</span>
                                </p>
                                <p class="text-xs text-blue-600 dark:text-blue-400">
                                    El rol del usuario no puede ser modificado
                                </p>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('admin.users.update', $user) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div class="grid gap-6 md:grid-cols-2">
                            {{-- Nombre --}}
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Nombre completo <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       name="name" 
                                       id="name" 
                                       value="{{ old('name', $user->name) }}"
                                       required
                                       class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white">
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Email --}}
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Correo electrónico <span class="text-red-500">*</span>
                                </label>
                                <input type="email" 
                                       name="email" 
                                       id="email" 
                                       value="{{ old('email', $user->email) }}"
                                       required
                                       class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white">
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Cambiar contraseña (opcional) --}}
                        <div class="rounded-lg border border-gray-200 p-4 dark:border-gray-700">
                            <h3 class="mb-4 flex items-center gap-2 text-sm font-semibold text-gray-700 dark:text-gray-300">
                                <span class="material-symbols-outlined text-base">lock</span>
                                Cambiar contraseña (opcional)
                            </h3>
                            <p class="mb-4 text-xs text-gray-500 dark:text-gray-400">
                                Deja estos campos vacíos si no deseas cambiar la contraseña
                            </p>

                            <div class="grid gap-6 md:grid-cols-2">
                                {{-- Nueva contraseña --}}
                                <div>
                                    <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Nueva contraseña
                                    </label>
                                    <input type="password" 
                                           name="password" 
                                           id="password" 
                                           class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white">
                                    @error('password')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Confirmar contraseña --}}
                                <div>
                                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Confirmar contraseña
                                    </label>
                                    <input type="password" 
                                           name="password_confirmation" 
                                           id="password_confirmation" 
                                           class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white">
                                </div>
                            </div>
                        </div>

                        {{-- Estado del usuario --}}
                        <div>
                            <label for="user_state_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Estado del usuario <span class="text-red-500">*</span>
                            </label>
                            <select name="user_state_id" 
                                    id="user_state_id" 
                                    required
                                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white">
                                @foreach($states as $state)
                                    <option value="{{ $state->id }}" 
                                            {{ old('user_state_id', $user->user_state_id) == $state->id ? 'selected' : '' }}>
                                        {{ ucfirst($state->name) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('user_state_id')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Datos personales (solo para pacientes) --}}
                        @if($user->isPaciente())
                        <div class="rounded-lg border border-gray-200 p-4 dark:border-gray-700">
                            <h3 class="mb-4 flex items-center gap-2 text-sm font-semibold text-gray-700 dark:text-gray-300">
                                <span class="material-symbols-outlined text-base">badge</span>
                                Datos Personales del Paciente
                            </h3>
                            
                            <div class="grid gap-6 md:grid-cols-2">
                                {{-- Sexo --}}
                                <div>
                                    <label for="gender" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Sexo
                                    </label>
                                    @php
                                        $currentGender = old('gender', optional($user->personalData)->gender);
                                    @endphp
                                    <select name="gender" 
                                            id="gender" 
                                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white">
                                        <option value="">Seleccionar...</option>
                                        <option value="male" @selected($currentGender === 'male')>Masculino</option>
                                        <option value="female" @selected($currentGender === 'female')>Femenino</option>
                                    </select>
                                    @error('gender')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Fecha de nacimiento --}}
                                <div>
                                    <label for="birth_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Fecha de nacimiento
                                    </label>
                                    <input type="date" 
                                           name="birth_date" 
                                           id="birth_date" 
                                           value="{{ old('birth_date', $user->personalData?->birth_date?->format('Y-m-d')) }}"
                                           max="{{ now()->format('Y-m-d') }}"
                                           class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white">
                                    @error('birth_date')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        @endif

                        {{-- Botones de acción --}}
                        <div class="flex items-center justify-end gap-3 border-t border-gray-200 pt-6 dark:border-gray-700">
                            <a href="{{ route('admin.users.index') }}" 
                               class="flex items-center gap-2 rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-800">
                                <span class="material-symbols-outlined text-base">close</span>
                                Cancelar
                            </a>
                            <button type="submit" 
                                    class="flex items-center gap-2 rounded-lg bg-blue-600 px-6 py-2.5 text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                <span class="material-symbols-outlined text-base">save</span>
                                Actualizar usuario
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
