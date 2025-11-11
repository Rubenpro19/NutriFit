<x-layouts.app :title="__('Reportes y Estadísticas')">
    <div class="flex h-full w-full flex-1 flex-col gap-6">
        {{-- Encabezado --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Reportes y Estadísticas</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400">Visualiza el rendimiento y actividad del sistema</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-sm text-gray-600 dark:text-gray-400">
                    Actualizado: {{ now()->format('d/m/Y H:i') }}
                </span>
            </div>
        </div>

        {{-- Estadísticas Principales --}}
        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-4">
            {{-- Total Usuarios --}}
            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                <div class="flex items-center justify-between">
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Usuarios</p>
                        <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">
                            {{ $stats['users_by_role']->sum('total') }}
                        </p>
                    </div>
                    <div class="flex-shrink-0 rounded-lg bg-blue-100 p-3 dark:bg-blue-900/30">
                        <span class="material-symbols-outlined text-2xl text-blue-600 dark:text-blue-400">group</span>
                    </div>
                </div>
                <div class="mt-4 flex flex-wrap items-center gap-2 text-xs text-gray-600 dark:text-gray-400">
                    @foreach($stats['users_by_role'] as $userRole)
                        <span class="whitespace-nowrap">{{ ucfirst($userRole->role->name) }}: <strong>{{ $userRole->total }}</strong></span>
                    @endforeach
                </div>
            </div>

            {{-- Citas del Mes --}}
            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                <div class="flex items-center justify-between">
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Citas del Mes</p>
                        <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">
                            {{ $stats['appointments_this_month'] }}
                        </p>
                    </div>
                    <div class="flex-shrink-0 rounded-lg bg-green-100 p-3 dark:bg-green-900/30">
                        <span class="material-symbols-outlined text-2xl text-green-600 dark:text-green-400">calendar_month</span>
                    </div>
                </div>
                <p class="mt-4 truncate text-xs text-gray-600 dark:text-gray-400">
                    {{ now()->format('F Y') }}
                </p>
            </div>

            {{-- Citas Hoy --}}
            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                <div class="flex items-center justify-between">
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Citas Hoy</p>
                        <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">
                            {{ $stats['appointments_today'] }}
                        </p>
                    </div>
                    <div class="flex-shrink-0 rounded-lg bg-purple-100 p-3 dark:bg-purple-900/30">
                        <span class="material-symbols-outlined text-2xl text-purple-600 dark:text-purple-400">today</span>
                    </div>
                </div>
                <p class="mt-4 truncate text-xs text-gray-600 dark:text-gray-400">
                    {{ now()->format('d/m/Y') }}
                </p>
            </div>

            {{-- Usuarios Activos --}}
            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                <div class="flex items-center justify-between">
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Usuarios Activos</p>
                        <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">
                            {{ $stats['users_by_state']->where('userState.name', 'activo')->first()->total ?? 0 }}
                        </p>
                    </div>
                    <div class="flex-shrink-0 rounded-lg bg-orange-100 p-3 dark:bg-orange-900/30">
                        <span class="material-symbols-outlined text-2xl text-orange-600 dark:text-orange-400">check_circle</span>
                    </div>
                </div>
                <p class="mt-4 text-xs text-gray-600 dark:text-gray-400">
                    Inactivos: <strong>{{ $stats['users_by_state']->where('userState.name', 'inactivo')->first()->total ?? 0 }}</strong>
                </p>
            </div>
        </div>

        {{-- Contenido Principal --}}
        <div class="grid gap-6 lg:grid-cols-2">
            {{-- Top Nutricionistas --}}
            <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900">
                <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-800">
                    <h2 class="flex items-center gap-2 text-lg font-semibold text-gray-900 dark:text-white">
                        <span class="material-symbols-outlined text-xl">trending_up</span>
                        Top 5 Nutricionistas
                    </h2>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Ordenados por cantidad de citas</p>
                </div>
                <div class="p-6">
                    @if($stats['top_nutricionistas']->count() > 0)
                        <div class="space-y-4">
                            @foreach($stats['top_nutricionistas'] as $index => $nutricionista)
                                <div class="flex items-center justify-between gap-4 rounded-lg border border-gray-200 p-4 dark:border-gray-700">
                                    <div class="flex min-w-0 flex-1 items-center gap-3">
                                        <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-lg bg-gray-200 text-sm font-bold text-gray-700 dark:bg-gray-700 dark:text-gray-300">
                                            #{{ $index + 1 }}
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <p class="truncate font-medium text-gray-900 dark:text-white">{{ $nutricionista->name }}</p>
                                            <p class="truncate text-sm text-gray-600 dark:text-gray-400">{{ $nutricionista->email }}</p>
                                        </div>
                                    </div>
                                    <div class="flex-shrink-0 text-right">
                                        <p class="text-2xl font-bold text-gray-900 dark:text-white">
                                            {{ $nutricionista->appointments_as_nutricionista_count }}
                                        </p>
                                        <p class="text-xs text-gray-600 dark:text-gray-400">citas</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="flex flex-col items-center justify-center py-8 text-center">
                            <span class="material-symbols-outlined text-4xl text-gray-400">person_off</span>
                            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">No hay nutricionistas registrados</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Estado de Citas --}}
            <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900">
                <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-800">
                    <h2 class="flex items-center gap-2 text-lg font-semibold text-gray-900 dark:text-white">
                        <span class="material-symbols-outlined text-xl">analytics</span>
                        Distribución de Citas
                    </h2>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Por estado</p>
                </div>
                <div class="p-6">
                    @if($stats['appointments_by_state']->count() > 0)
                        <div class="space-y-4">
                            @php
                                $totalAppointments = $stats['appointments_by_state']->sum('total');
                                $colors = [
                                    'pendiente' => ['bg' => 'bg-yellow-500', 'text' => 'text-yellow-600', 'dark' => 'dark:text-yellow-400'],
                                    'confirmada' => ['bg' => 'bg-blue-500', 'text' => 'text-blue-600', 'dark' => 'dark:text-blue-400'],
                                    'completada' => ['bg' => 'bg-green-500', 'text' => 'text-green-600', 'dark' => 'dark:text-green-400'],
                                    'cancelada' => ['bg' => 'bg-red-500', 'text' => 'text-red-600', 'dark' => 'dark:text-red-400'],
                                ];
                            @endphp
                            
                            @foreach($stats['appointments_by_state'] as $appointmentState)
                                @php
                                    $stateName = $appointmentState->appointmentState->name;
                                    $percentage = $totalAppointments > 0 ? round(($appointmentState->total / $totalAppointments) * 100, 1) : 0;
                                    $color = $colors[$stateName] ?? ['bg' => 'bg-gray-500', 'text' => 'text-gray-600', 'dark' => 'dark:text-gray-400'];
                                @endphp
                                
                                <div>
                                    <div class="mb-2 flex items-center justify-between">
                                        <span class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ ucfirst($stateName) }}
                                        </span>
                                        <div class="flex items-center gap-2">
                                            <span class="text-sm font-bold {{ $color['text'] }} {{ $color['dark'] }}">
                                                {{ $appointmentState->total }}
                                            </span>
                                            <span class="text-xs text-gray-600 dark:text-gray-400">
                                                ({{ $percentage }}%)
                                            </span>
                                        </div>
                                    </div>
                                    <div class="h-2 w-full overflow-hidden rounded-full bg-gray-200 dark:bg-gray-700">
                                        <div class="{{ $color['bg'] }} h-full rounded-full" style="width: {{ $percentage }}%"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="flex flex-col items-center justify-center py-8 text-center">
                            <span class="material-symbols-outlined text-4xl text-gray-400">event_busy</span>
                            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">No hay citas registradas</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Últimos Usuarios Registrados --}}
        <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900">
            <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-800">
                <h2 class="flex items-center gap-2 text-lg font-semibold text-gray-900 dark:text-white">
                    <span class="material-symbols-outlined text-xl">person_add</span>
                    Últimos Usuarios Registrados
                </h2>
                <p class="text-sm text-gray-600 dark:text-gray-400">Los 10 registros más recientes</p>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="border-b border-gray-200 bg-gray-50 dark:border-gray-800 dark:bg-gray-800/50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-600 dark:text-gray-400">
                                Usuario
                            </th>
                            <th class="hidden px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-600 dark:text-gray-400 sm:table-cell">
                                Correo
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-600 dark:text-gray-400">
                                Rol
                            </th>
                            <th class="hidden px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-600 dark:text-gray-400 md:table-cell">
                                Estado
                            </th>
                            <th class="hidden px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-600 dark:text-gray-400 lg:table-cell">
                                Registrado
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                        @foreach($stats['recent_users'] as $user)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-lg bg-gray-200 text-xs font-semibold text-gray-700 dark:bg-gray-700 dark:text-gray-300">
                                            {{ $user->initials() }}
                                        </div>
                                        <div class="min-w-0">
                                            <p class="truncate font-medium text-gray-900 dark:text-white">{{ $user->name }}</p>
                                            <p class="truncate text-xs text-gray-600 dark:text-gray-400 sm:hidden">{{ $user->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="hidden max-w-xs px-6 py-4 sm:table-cell">
                                    <p class="truncate text-sm text-gray-600 dark:text-gray-400">{{ $user->email }}</p>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4">
                                    <span class="inline-flex rounded-full bg-blue-100 px-2 py-1 text-xs font-semibold text-blue-800 dark:bg-blue-900/30 dark:text-blue-400">
                                        {{ ucfirst($user->role->name) }}
                                    </span>
                                </td>
                                <td class="hidden whitespace-nowrap px-6 py-4 md:table-cell">
                                    @if($user->userState->name === 'activo')
                                        <span class="inline-flex items-center gap-1 rounded-full bg-green-100 px-2 py-1 text-xs font-semibold text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                            <span class="h-1.5 w-1.5 rounded-full bg-green-600"></span>
                                            Activo
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 rounded-full bg-gray-100 px-2 py-1 text-xs font-semibold text-gray-800 dark:bg-gray-800 dark:text-gray-400">
                                            <span class="h-1.5 w-1.5 rounded-full bg-gray-600"></span>
                                            Inactivo
                                        </span>
                                    @endif
                                </td>
                                <td class="hidden whitespace-nowrap px-6 py-4 text-sm text-gray-600 dark:text-gray-400 lg:table-cell">
                                    {{ $user->created_at->format('d/m/Y H:i') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layouts.app>
