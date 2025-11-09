<div>
    <div class="flex items-center justify-between mb-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Gestión de Usuarios</h1>
            <p class="text-sm text-gray-600 dark:text-gray-400">Administra todos los usuarios del sistema</p>
        </div>
        <a href="{{ route('admin.users.create') }}" 
           class="flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600">
            <span class="material-symbols-outlined text-base">add</span>
            Crear Usuario
        </a>
    </div>

    {{-- Mensajes --}}
    @if(session('success'))
        <div class="rounded-lg bg-green-50 p-4 text-sm text-green-800 dark:bg-green-900/30 dark:text-green-400 mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="rounded-lg border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-900 mb-4">
        <div class="grid gap-4 md:grid-cols-4">
            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Buscar</label>
                <input type="text" 
                       wire:model.live.debounce.500ms="search"
                       placeholder="Nombre o email..."
                       class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm dark:border-gray-600 dark:bg-gray-800 dark:text-white">
            </div>

            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Rol</label>
                <select wire:model.live="role_id" class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm dark:border-gray-600 dark:bg-gray-800 dark:text-white">
                    <option value="">Todos los roles</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}">{{ ucfirst($role->name) }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Estado</label>
                <select wire:model.live="user_state_id" class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm dark:border-gray-600 dark:bg-gray-800 dark:text-white">
                    <option value="">Todos los estados</option>
                    @foreach($states as $state)
                        <option value="{{ $state->id }}">{{ ucfirst($state->name ?? $state->user_state_name ?? '') }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-end gap-2">
                <button wire:click="clearFilters" class="flex-1 rounded-md border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-800">Limpiar</button>
            </div>
        </div>
    </div>

    <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-900">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="border-b border-gray-200 bg-gray-50 dark:border-gray-700 dark:bg-gray-800">
                    <tr>
                        <th class="px-4 py-3 font-medium text-gray-900 dark:text-white">Usuario</th>
                        <th class="px-4 py-3 font-medium text-gray-900 dark:text-white">Email</th>
                        <th class="px-4 py-3 font-medium text-gray-900 dark:text-white">Rol</th>
                        <th class="px-4 py-3 font-medium text-gray-900 dark:text-white">Estado</th>
                        <th class="px-4 py-3 font-medium text-gray-900 dark:text-white">Fecha de registro</th>
                        <th class="px-4 py-3 font-medium text-gray-900 dark:text-white text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                    @forelse($users as $user)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-100 text-sm font-medium text-blue-600 dark:bg-blue-900/30 dark:text-blue-400">
                                        {{ $user->initials() }}
                                    </div>
                                    <span class="font-medium text-gray-900 dark:text-white">{{ $user->name }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-gray-600 dark:text-gray-400">{{ $user->email }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex rounded-full px-2 py-1 text-xs font-medium 
                                    @if($user->role->name === 'administrador') bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400
                                    @elseif($user->role->name === 'nutricionista') bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400
                                    @else bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400
                                    @endif">
                                    {{ ucfirst($user->role->name) }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center gap-2 rounded-full px-2 py-1 text-xs font-medium
                                    @if($user->userState->name === 'activo') bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400
                                    @else bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400
                                    @endif">
                                    <span class="inline-block h-2 w-2 flex-shrink-0 rounded-full
                                        @if($user->userState->name === 'activo') bg-green-600 dark:bg-green-400
                                        @else bg-red-600 dark:bg-red-400
                                        @endif" aria-hidden="true"></span>
                                    {{ ucfirst($user->userState->name) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-gray-600 dark:text-gray-400">
                                {{ $user->created_at->format('d/m/Y') }}
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.users.edit', $user) }}" 
                                       class="rounded-md p-2 text-blue-600 hover:bg-blue-50 dark:text-blue-400 dark:hover:bg-blue-900/30"
                                       title="Editar usuario">
                                        <span class="material-symbols-outlined text-base">edit</span>
                                    </a>
                                    @if($user->id !== auth()->id())
                                        @if($user->userState->name === 'activo')
                                            {{-- Botón para desactivar (abre modal) --}}
                                            <button type="button"
                                                    wire:click="openDeactivateModal({{ $user->id }})"
                                                    class="rounded-md p-2 text-orange-600 hover:bg-orange-50 dark:text-orange-400 dark:hover:bg-orange-900/30"
                                                    title="Desactivar usuario">
                                                <span class="material-symbols-outlined text-base">block</span>
                                            </button>
                                        @else
                                            {{-- Botón para activar (abre modal) --}}
                                            <button type="button"
                                                    wire:click="openActivateModal({{ $user->id }})"
                                                    class="rounded-md p-2 text-green-600 hover:bg-green-50 dark:text-green-400 dark:hover:bg-green-900/30"
                                                    title="Activar usuario">
                                                <span class="material-symbols-outlined text-base">check_circle</span>
                                            </button>
                                            </form>
                                        @endif
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                No se encontraron usuarios
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="flex justify-center mt-4">
        {{ $users->links() }}
    </div>

    {{-- Modal de Confirmación para Desactivar Usuario --}}
    @if($userToToggle)
        @php
            $selectedUser = $users->firstWhere('id', $userToToggle);
        @endphp
        
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4" wire:click.self="closeModal">
            <div class="w-full max-w-md rounded-xl bg-white shadow-2xl dark:bg-gray-900" wire:click.stop>
                <div class="space-y-4 p-6">
                    <div class="flex items-center gap-3">
                        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-orange-100 dark:bg-orange-900/30">
                            <span class="material-symbols-outlined text-2xl text-orange-600 dark:text-orange-400">warning</span>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Desactivar Usuario</h3>
                        </div>
                    </div>

                    <div class="space-y-3 rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-800">
                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                            {{ ucfirst($selectedUser->role->name ?? $selectedUser->role_name ?? '') }}
                        </p>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                            {{ $selectedUser->name ?? '' }}
                        </p>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            {{ $selectedUser->email ?? '' }}
                        </p>
                    </div>

                    <div class="rounded-lg border border-orange-200 bg-orange-50 p-4 dark:border-orange-900/50 dark:bg-orange-900/20">
                        <div class="flex gap-3">
                            <span class="material-symbols-outlined flex-shrink-0 text-orange-600 dark:text-orange-400">info</span>
                            <div class="flex-1 space-y-2">
                                <p class="text-sm font-medium text-orange-800 dark:text-orange-300">
                                    Importante:
                                </p>
                                <p class="text-sm text-orange-700 dark:text-orange-400">
                                    Un usuario en estado <strong>inactivo</strong> no podrá iniciar sesión en la plataforma ni realizar ninguna acción hasta que sea reactivado.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-3 pt-2">
                        <button type="button"
                                wire:click="closeModal"
                                class="flex-1 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                            Cancelar
                        </button>
                        <form action="{{ $selectedUser ? route('admin.users.toggle-status', $selectedUser) : '#' }}" method="POST" class="flex-1">
                            @csrf
                            <button type="submit" 
                                    class="w-full rounded-lg bg-orange-600 px-4 py-2 text-sm font-medium text-white hover:bg-orange-700 dark:bg-orange-500 dark:hover:bg-orange-600">
                                Desactivar Usuario
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Modal de Confirmación para Activar Usuario --}}
    @if($userToActivate)
        @php
            $selectedUser = $users->firstWhere('id', $userToActivate);
        @endphp
        
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4" wire:click.self="closeModal">
            <div class="w-full max-w-md rounded-xl bg-white shadow-2xl dark:bg-gray-900" wire:click.stop>
                <div class="space-y-4 p-6">
                    <div class="flex items-center gap-3">
                        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-green-100 dark:bg-green-900/30">
                            <span class="material-symbols-outlined text-2xl text-green-600 dark:text-green-400">check_circle</span>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Activar Usuario</h3>
                        </div>
                    </div>

                    <div class="space-y-3 rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-800">
                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                            {{ ucfirst($selectedUser->role->name ?? $selectedUser->role_name ?? '') }}
                        </p>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                            {{ $selectedUser->name ?? '' }}
                        </p>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            {{ $selectedUser->email ?? '' }}
                        </p>
                    </div>

                    <div class="rounded-lg border border-green-200 bg-green-50 p-4 dark:border-green-900/50 dark:bg-green-900/20">
                        <div class="flex gap-3">
                            <span class="material-symbols-outlined flex-shrink-0 text-green-600 dark:text-green-400">info</span>
                            <div class="flex-1 space-y-2">
                                <p class="text-sm font-medium text-green-800 dark:text-green-300">
                                    Confirmación:
                                </p>
                                <p class="text-sm text-green-700 dark:text-green-400">
                                    Al activar este usuario, podrá <strong>iniciar sesión</strong> en la plataforma y realizar todas las acciones según su rol.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-3 pt-2">
                        <button type="button"
                                wire:click="closeModal"
                                class="flex-1 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                            Cancelar
                        </button>
                        <form action="{{ $selectedUser ? route('admin.users.toggle-status', $selectedUser) : '#' }}" method="POST" class="flex-1">
                            @csrf
                            <button type="submit" 
                                    class="w-full rounded-lg bg-green-600 px-4 py-2 text-sm font-medium text-white hover:bg-green-700 dark:bg-green-500 dark:hover:bg-green-600">
                                Activar Usuario
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
