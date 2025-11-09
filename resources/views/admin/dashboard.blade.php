<x-layouts.app :title="__('Dashboard Administrativo')">
    <div class="flex h-full w-full flex-1 flex-col gap-6">
        {{-- Título --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Dashboard Administrativo</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400">Resumen general del sistema</p>
            </div>
        </div>

        {{-- Estadísticas principales --}}
        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
            {{-- Total Usuarios --}}
            <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-900">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Usuarios</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $stats['total_users'] }}</p>
                    </div>
                    <div class="rounded-full bg-blue-100 p-3 dark:bg-blue-900/30">
                        <span class="material-symbols-outlined text-blue-600 dark:text-blue-400">group</span>
                    </div>
                </div>
            </div>

            {{-- Total Pacientes --}}
            <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-900">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Pacientes</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $stats['total_pacientes'] }}</p>
                    </div>
                    <div class="rounded-full bg-green-100 p-3 dark:bg-green-900/30">
                        <span class="material-symbols-outlined text-green-600 dark:text-green-400">person</span>
                    </div>
                </div>
            </div>

            {{-- Total Nutricionistas --}}
            <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-900">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Nutricionistas</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $stats['total_nutricionistas'] }}
                        </p>
                    </div>
                    <div class="rounded-full bg-purple-100 p-3 dark:bg-purple-900/30">
                        <span
                            class="material-symbols-outlined text-purple-600 dark:text-purple-400">medical_services</span>
                    </div>
                </div>
            </div>

            {{-- Citas Pendientes --}}
            <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-900">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Citas Pendientes</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $stats['pending_appointments'] }}
                        </p>
                    </div>
                    <div class="rounded-full bg-orange-100 p-3 dark:bg-orange-900/30">
                        <span class="material-symbols-outlined text-orange-600 dark:text-orange-400">event</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Acciones rápidas --}}
        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
            <a href="{{ route('admin.users.index') }}"
                class="group rounded-lg border border-gray-200 bg-white p-6 shadow-sm transition hover:shadow-md dark:border-gray-700 dark:bg-gray-900 dark:hover:border-gray-600">
                <div class="flex items-center gap-4">
                    <div class="rounded-full bg-blue-100 p-3 dark:bg-blue-900/30">
                        <span class="material-symbols-outlined text-blue-600 dark:text-blue-400">manage_accounts</span>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900 dark:text-white">Gestionar Usuarios</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Ver y administrar usuarios</p>
                    </div>
                </div>
            </a>

            {{-- <a href="{{ route('admin.nutricionistas.index') }}"
                class="group rounded-lg border border-gray-200 bg-white p-6 shadow-sm transition hover:shadow-md dark:border-gray-700 dark:bg-gray-900 dark:hover:border-gray-600">
                <div class="flex items-center gap-4">
                    <div class="rounded-full bg-purple-100 p-3 dark:bg-purple-900/30">
                        <span
                            class="material-symbols-outlined text-purple-600 dark:text-purple-400">health_and_safety</span>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900 dark:text-white">Nutricionistas</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Ver profesionales</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('admin.pacientes.index') }}"
                class="group rounded-lg border border-gray-200 bg-white p-6 shadow-sm transition hover:shadow-md dark:border-gray-700 dark:bg-gray-900 dark:hover:border-gray-600">
                <div class="flex items-center gap-4">
                    <div class="rounded-full bg-green-100 p-3 dark:bg-green-900/30">
                        <span class="material-symbols-outlined text-green-600 dark:text-green-400">group</span>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900 dark:text-white">Pacientes</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Ver pacientes</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('admin.appointments.index') }}"
                class="group rounded-lg border border-gray-200 bg-white p-6 shadow-sm transition hover:shadow-md dark:border-gray-700 dark:bg-gray-900 dark:hover:border-gray-600">
                <div class="flex items-center gap-4">
                    <div class="rounded-full bg-orange-100 p-3 dark:bg-orange-900/30">
                        <span
                            class="material-symbols-outlined text-orange-600 dark:text-orange-400">calendar_month</span>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900 dark:text-white">Citas</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Gestionar citas</p>
                    </div>
                </div>
            </a> --}}

            <a href="{{ route('admin.reports.index') }}"
                class="group rounded-lg border border-gray-200 bg-white p-6 shadow-sm transition hover:shadow-md dark:border-gray-700 dark:bg-gray-900 dark:hover:border-gray-600">
                <div class="flex items-center gap-4">
                    <div class="rounded-full bg-indigo-100 p-3 dark:bg-indigo-900/30">
                        <span class="material-symbols-outlined text-indigo-600 dark:text-indigo-400">bar_chart</span>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900 dark:text-white">Reportes</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Ver estadísticas</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('profile.edit') }}"
                class="group rounded-lg border border-gray-200 bg-white p-6 shadow-sm transition hover:shadow-md dark:border-gray-700 dark:bg-gray-900 dark:hover:border-gray-600">
                <div class="flex items-center gap-4">
                    <div class="rounded-full bg-gray-100 p-3 dark:bg-gray-800">
                        <span class="material-symbols-outlined text-gray-600 dark:text-gray-400">settings</span>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900 dark:text-white">Configuración</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Ajustes del usuario</p>
                    </div>
                </div>
            </a>
        </div>

        {{-- Información adicional --}}
        <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-900">
            <h2 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">Actividad Reciente</h2>
            <p class="text-sm text-gray-600 dark:text-gray-400">Total de citas: {{ $stats['total_appointments'] }}</p>
        </div>
    </div>
</x-layouts.app>
