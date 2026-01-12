<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-gray-800">
        <flux:sidebar sticky stashable class="border-e border-gray-200 bg-gray-50 dark:border-gray-700 dark:bg-gray-900">
            <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

            @php
                $dashboardRoute = auth()->user()->isAdmin() ? 'admin.dashboard' 
                    : (auth()->user()->isNutricionista() ? 'nutricionista.dashboard' 
                    : 'paciente.dashboard');
            @endphp

            <a href="{{ route($dashboardRoute) }}" class="me-5 flex items-center space-x-2 rtl:space-x-reverse" wire:navigate>
                <x-app-logo />
            </a>

            <flux:navlist variant="outline">
                @if(auth()->user()->isAdmin())
                    <flux:navlist.group :heading="__('Gestión')" class="flex flex-col gap-2">
                        <flux:navlist.item icon="home" :href="route('admin.dashboard')" :current="request()->routeIs('admin.dashboard')" wire:navigate>{{ __('Dashboard') }}</flux:navlist.item>
                        <flux:navlist.item icon="users" :href="route('admin.users.index')" :current="request()->routeIs('admin.users.*')" wire:navigate>{{ __('Gestión de Usuarios') }}</flux:navlist.item>
                        {{-- <flux:navlist.item icon="user-plus" :href="route('admin.nutricionistas.index')" :current="request()->routeIs('admin.nutricionistas.*')" wire:navigate>{{ __('Gestión de Nutricionistas') }}</flux:navlist.item>
                        <flux:navlist.item icon="user-group" :href="route('admin.pacientes.index')" :current="request()->routeIs('admin.pacientes.*')" wire:navigate>{{ __('Gestión de Pacientes') }}</flux:navlist.item>
                        <flux:navlist.item icon="calendar" :href="route('admin.appointments.index')" :current="request()->routeIs('admin.appointments.*')" wire:navigate>{{ __('Gestión de Citas') }}</flux:navlist.item> --}}
                    </flux:navlist.group>
                    <flux:navlist.group :heading="__('Sistema')" class="flex flex-col gap-2">
                        <flux:navlist.item icon="chart-bar" :href="route('admin.reports.index')" :current="request()->routeIs('admin.reports.*')" wire:navigate>{{ __('Reportes') }}</flux:navlist.item>
                        <flux:navlist.item icon="cog" :href="route('profile.edit')" :current="request()->routeIs('profile.edit')" wire:navigate>{{ __('Configuración') }}</flux:navlist.item>
                    </flux:navlist.group>
                @endif
            </flux:navlist>

            <flux:spacer />

            <!-- Desktop User Menu -->
            <flux:dropdown class="hidden lg:block" position="bottom" align="start">
                @if(auth()->user()->profilePhotoUrl())
                    <div class="flex items-center gap-2 cursor-pointer">
                        <div class="flex h-8 w-8 items-center justify-center rounded-full overflow-hidden">
                            <img src="{{ auth()->user()->profilePhotoUrl() }}" 
                                 alt="{{ auth()->user()->name }}" 
                                 class="w-full h-full object-cover">
                        </div>
                        <span class="text-sm font-medium">{{ auth()->user()->name }}</span>
                        <flux:icon.chevrons-up-down variant="micro" class="ml-auto" />
                    </div>
                @else
                    <flux:profile
                        :name="auth()->user()->name"
                        :initials="auth()->user()->initials()"
                        icon:trailing="chevrons-up-down"
                    />
                @endif

                <flux:menu class="w-[220px]">
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    @if(auth()->user()->profilePhotoUrl())
                                        <img src="{{ auth()->user()->profilePhotoUrl() }}" 
                                             alt="{{ auth()->user()->name }}" 
                                             class="w-full h-full object-cover">
                                    @else
                                        <span class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white">
                                            {{ auth()->user()->initials() }}
                                        </span>
                                    @endif
                                </span>

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>{{ __('Configuración') }}</flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                            {{ __('Cerrar Sesión') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:sidebar>

        <!-- Mobile User Menu -->
        <flux:header class="lg:hidden">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

            <flux:spacer />

            <flux:dropdown position="top" align="end">
                @if(auth()->user()->profilePhotoUrl())
                    <div class="cursor-pointer flex h-8 w-8 items-center justify-center rounded-full overflow-hidden">
                        <img src="{{ auth()->user()->profilePhotoUrl() }}" 
                             alt="{{ auth()->user()->name }}" 
                             class="w-full h-full object-cover">
                    </div>
                @else
                    <flux:profile
                        :initials="auth()->user()->initials()"
                        icon-trailing="chevron-down"
                    />
                @endif

                <flux:menu>
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    @if(auth()->user()->profilePhotoUrl())
                                        <img src="{{ auth()->user()->profilePhotoUrl() }}" 
                                             alt="{{ auth()->user()->name }}" 
                                             class="w-full h-full object-cover">
                                    @else
                                        <span class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white">
                                            {{ auth()->user()->initials() }}
                                        </span>
                                    @endif
                                </span>

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>{{ __('Configuración') }}</flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                            {{ __('Cerrar Sesión') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:header>

        {{ $slot }}

        @fluxScripts
    </body>
</html>
