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
                                <span class="inline-flex rounded-full px-2 py-1 text-xs font-medium
                                    @if($user->userState->name === 'activo') bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400
                                    @else bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400
                                    @endif">
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
                                        <form action="{{ route('admin.users.toggle-status', $user) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" 
                                                    class="rounded-md p-2 text-orange-600 hover:bg-orange-50 dark:text-orange-400 dark:hover:bg-orange-900/30"
                                                    title="{{ $user->userState->name === 'activo' ? 'Desactivar usuario' : 'Activar usuario' }}">
                                                <span class="material-symbols-outlined text-base">
                                                    {{ $user->userState->name === 'activo' ? 'block' : 'check_circle' }}
                                                </span>
                                            </button>
                                        </form>
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
</div>
