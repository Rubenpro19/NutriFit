<x-layouts.app :title="__('Configuración del Sistema')">
    <div class="flex h-full w-full flex-1 flex-col gap-6">
        {{-- Header --}}
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.dashboard') }}" class="text-gray-600 dark:text-gray-400 hover:text-green-600 dark:hover:text-green-400 transition">
                    <span class="material-symbols-outlined text-2xl">arrow_back</span>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Configuración del Sistema</h1>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Administra los datos de contacto del sitio</p>
                </div>
            </div>
            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-green-100 dark:bg-green-900/40">
                <span class="material-symbols-outlined text-2xl text-green-600 dark:text-green-400">settings</span>
            </div>
        </div>

        {{-- Componente Livewire --}}
        <livewire:admin.system-settings />
    </div>
</x-layouts.app>
