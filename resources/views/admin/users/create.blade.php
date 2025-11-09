<x-layouts.app :title="__('Crear Usuario')">
    <div class="flex h-full w-full flex-1 flex-col gap-6">
        {{-- Encabezado --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Crear Usuario</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400">Completa el formulario para crear un nuevo usuario</p>
            </div>
            <a href="{{ route('admin.users.index') }}" 
               class="flex items-center gap-2 rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-800">
                <span class="material-symbols-outlined text-base">arrow_back</span>
                Volver
            </a>
        </div>

        {{-- Formulario --}}
        <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-900">
            <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-6">
                @csrf

                <div class="grid gap-6 md:grid-cols-2">
                    {{-- Nombre --}}
                    <div>
                        <label for="name" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">
                            Nombre completo <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="name" 
                            name="name" 
                            value="{{ old('name') }}"
                            required
                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:focus:border-blue-500"
                            placeholder="Juan Pérez">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label for="email" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">
                            Correo electrónico <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            value="{{ old('email') }}"
                            required
                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:focus:border-blue-500"
                            placeholder="correo@ejemplo.com">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Contraseña --}}
                    <div>
                        <label for="password" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">
                            Contraseña <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            required
                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:focus:border-blue-500"
                            placeholder="Mínimo 8 caracteres">
                        @error('password')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Mínimo 8 caracteres</p>
                    </div>

                    {{-- Confirmar contraseña --}}
                    <div>
                        <label for="password_confirmation" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">
                            Confirmar contraseña <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="password" 
                            id="password_confirmation" 
                            name="password_confirmation" 
                            required
                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:focus:border-blue-500"
                            placeholder="Confirma la contraseña">
                    </div>

                    {{-- Rol --}}
                    <div>
                        <label for="role_id" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">
                            Rol <span class="text-red-500">*</span>
                        </label>
                        <select 
                            id="role_id" 
                            name="role_id" 
                            required
                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:focus:border-blue-500">
                            <option value="">Selecciona un rol</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                    {{ ucfirst($role->name) }}
                                </option>
                            @endforeach
                        </select>
                        @error('role_id')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Estado --}}
                    <div>
                        <label for="user_state_id" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">
                            Estado <span class="text-red-500">*</span>
                        </label>
                        <select 
                            id="user_state_id" 
                            name="user_state_id" 
                            required
                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:focus:border-blue-500">
                            <option value="">Selecciona un estado</option>
                            @foreach($states as $state)
                                <option value="{{ $state->id }}" {{ old('user_state_id') == $state->id ? 'selected' : '' }}>
                                    {{ ucfirst($state->name ?? $state->user_state_name ?? '') }}
                                </option>
                            @endforeach
                        </select>
                        @error('user_state_id')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Información adicional --}}
                <div class="rounded-lg bg-blue-50 p-4 dark:bg-blue-900/20">
                    <div class="flex items-start gap-3">
                        <span class="material-symbols-outlined text-blue-600 dark:text-blue-400">info</span>
                        <div class="text-sm text-blue-800 dark:text-blue-300">
                            <p class="font-medium">Información importante:</p>
                            <ul class="mt-2 list-inside list-disc space-y-1">
                                <li>La contraseña debe tener al menos 8 caracteres</li>
                                <li>El usuario recibirá un correo de bienvenida</li>
                                <li>Puedes cambiar el estado después de crear el usuario</li>
                            </ul>
                        </div>
                    </div>
                </div>

                {{-- Botones --}}
                <div class="flex items-center justify-end gap-3 border-t border-gray-200 pt-6 dark:border-gray-700">
                    <a href="{{ route('admin.users.index') }}" 
                       class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-800">
                        Cancelar
                    </a>
                    <button 
                        type="submit"
                        class="flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 dark:bg-blue-500 dark:hover:bg-blue-600 dark:focus:ring-blue-800">
                        <span class="material-symbols-outlined text-base">add</span>
                        Crear Usuario
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
