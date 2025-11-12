{{-- HEADER --}}
<header class="sticky top-0 z-50 border-b border-gray-200 bg-white shadow-sm backdrop-blur-sm dark:border-gray-800 dark:bg-gray-900/95">
    <div class="container mx-auto px-4">
        <div class="relative flex h-16 items-center justify-between">
            {{-- Logo --}}
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-gradient-to-br from-green-500 to-emerald-600 shadow-md">
                    <span class="material-symbols-outlined text-2xl text-white">eco</span>
                </div>
                <a href="{{ route('home') }}" class="text-xl font-bold text-gray-900 dark:text-white">
                    Nutri<span class="text-green-600">Fit</span>
                </a>
            </div>

            {{-- Navigation Desktop - Centrado --}}
            <nav class="absolute left-1/2 hidden -translate-x-1/2 transform items-center gap-1 md:flex">
                <a href="{{ route('home') }}" 
                   class="flex items-center gap-2 rounded-lg px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-100 hover:text-green-600 dark:text-gray-300 dark:hover:bg-gray-800 dark:hover:text-green-500 {{ request()->routeIs('home') ? 'bg-green-50 text-green-600 dark:bg-green-900/20 dark:text-green-500' : '' }}">
                    <span class="material-symbols-outlined text-lg">home</span>
                    Inicio
                </a>
                <a href="{{ route('about') }}" 
                   class="flex items-center gap-2 rounded-lg px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-100 hover:text-green-600 dark:text-gray-300 dark:hover:bg-gray-800 dark:hover:text-green-500 {{ request()->routeIs('about') ? 'bg-green-50 text-green-600 dark:bg-green-900/20 dark:text-green-500' : '' }}">
                    <span class="material-symbols-outlined text-lg">group</span>
                    Sobre Nosotros
                </a>
                <a href="{{ route('contact') }}" 
                   class="flex items-center gap-2 rounded-lg px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-100 hover:text-green-600 dark:text-gray-300 dark:hover:bg-gray-800 dark:hover:text-green-500 {{ request()->routeIs('contact') ? 'bg-green-50 text-green-600 dark:bg-green-900/20 dark:text-green-500' : '' }}">
                    <span class="material-symbols-outlined text-lg">mail</span>
                    Contacto
                </a>
            </nav>

            {{-- Auth Buttons --}}
            <div class="hidden items-center gap-3 md:flex">
                @if (Route::has('login'))
                    @auth
                        {{-- Usuario autenticado - Menú desplegable --}}
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" 
                                    class="flex items-center gap-2 rounded-lg bg-gradient-to-r from-green-600 to-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow-md transition hover:from-green-700 hover:to-emerald-700 hover:shadow-lg">
                                <div class="flex h-8 w-8 items-center justify-center rounded-full bg-white/20">
                                    <span class="text-sm font-bold">{{ auth()->user()->initials() }}</span>
                                </div>
                                <span>{{ auth()->user()->name }}</span>
                                <span class="material-symbols-outlined text-lg">expand_more</span>
                            </button>

                            {{-- Dropdown Menu --}}
                            <div x-show="open" 
                                 @click.away="open = false"
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 scale-95"
                                 x-transition:enter-end="opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-150"
                                 x-transition:leave-start="opacity-100 scale-100"
                                 x-transition:leave-end="opacity-0 scale-95"
                                 class="absolute right-0 mt-2 w-56 origin-top-right rounded-lg border border-gray-200 bg-white shadow-lg dark:border-gray-700 dark:bg-gray-800"
                                 style="display: none;">
                                
                                {{-- Usuario info --}}
                                <div class="border-b border-gray-200 px-4 py-3 dark:border-gray-700">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ auth()->user()->name }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ auth()->user()->email }}</p>
                                </div>

                                <div class="py-1">
                                    @php
                                        $dashboardRoute = auth()->user()->isAdmin() ? 'admin.dashboard' 
                                            : (auth()->user()->isNutricionista() ? 'nutricionista.dashboard' 
                                            : 'paciente.dashboard');
                                    @endphp

                                    {{-- Dashboard --}}
                                    <a href="{{ route($dashboardRoute) }}" 
                                       class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 transition hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700">
                                        <span class="material-symbols-outlined text-lg">dashboard</span>
                                        Dashboard
                                    </a>

                                    {{-- Configuración --}}
                                    <a href="{{ route('profile.edit') }}" 
                                       class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 transition hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700">
                                        <span class="material-symbols-outlined text-lg">settings</span>
                                        Configuración
                                    </a>
                                </div>

                                {{-- Cerrar sesión --}}
                                <div class="border-t border-gray-200 py-1 dark:border-gray-700">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" 
                                                class="flex w-full items-center gap-3 px-4 py-2 text-left text-sm text-red-600 transition hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-900/20">
                                            <span class="material-symbols-outlined text-lg">logout</span>
                                            Cerrar sesión
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @else
                        @if (!request()->routeIs('login'))
                            <a href="{{ route('login') }}" 
                               class="flex items-center gap-2 rounded-lg px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800">
                                <span class="material-symbols-outlined text-lg">login</span>
                                Iniciar sesión
                            </a>
                        @endif
                        @if (Route::has('register') && !request()->routeIs('register'))
                            <a href="{{ route('register') }}" 
                               class="flex items-center gap-2 rounded-lg bg-gradient-to-r from-green-600 to-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow-md transition hover:from-green-700 hover:to-emerald-700 hover:shadow-lg">
                                <span class="material-symbols-outlined text-lg">person_add</span>
                                Registrarse
                            </a>
                        @endif
                    @endauth
                @endif
            </div>

            {{-- Mobile Menu Button --}}
            <button id="mobile-menu-btn" class="rounded-lg p-2 text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800 md:hidden">
                <span class="material-symbols-outlined text-2xl">menu</span>
            </button>
        </div>

        {{-- Mobile Navigation --}}
        <div id="mobile-menu" class="hidden border-t border-gray-200 py-4 dark:border-gray-800 md:hidden">
            <nav class="flex flex-col gap-2">
                <a href="{{ route('home') }}" 
                   class="flex items-center gap-2 rounded-lg px-4 py-3 text-sm font-medium text-gray-700 transition hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800 {{ request()->routeIs('home') ? 'bg-green-50 text-green-600 dark:bg-green-900/20' : '' }}">
                    <span class="material-symbols-outlined text-lg">home</span>
                    Inicio
                </a>
                <a href="{{ route('about') }}" 
                   class="flex items-center gap-2 rounded-lg px-4 py-3 text-sm font-medium text-gray-700 transition hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800 {{ request()->routeIs('about') ? 'bg-green-50 text-green-600 dark:bg-green-900/20' : '' }}">
                    <span class="material-symbols-outlined text-lg">group</span>
                    Sobre Nosotros
                </a>
                <a href="{{ route('contact') }}" 
                   class="flex items-center gap-2 rounded-lg px-4 py-3 text-sm font-medium text-gray-700 transition hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800 {{ request()->routeIs('contact') ? 'bg-green-50 text-green-600 dark:bg-green-900/20' : '' }}">
                    <span class="material-symbols-outlined text-lg">mail</span>
                    Contacto
                </a>
                
                @if (Route::has('login'))
                    @auth
                        {{-- Usuario info --}}
                        <div class="mt-4 rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-800">
                            <div class="mb-3 flex items-center gap-3">
                                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-r from-green-600 to-emerald-600 text-white">
                                    <span class="text-sm font-bold">{{ auth()->user()->initials() }}</span>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ auth()->user()->name }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ auth()->user()->email }}</p>
                                </div>
                            </div>

                            @php
                                $dashboardRoute = auth()->user()->isAdmin() ? 'admin.dashboard' 
                                    : (auth()->user()->isNutricionista() ? 'nutricionista.dashboard' 
                                    : 'paciente.dashboard');
                            @endphp

                            <a href="{{ route($dashboardRoute) }}" 
                               class="mb-2 flex items-center gap-2 rounded-lg bg-white px-4 py-3 text-sm font-medium text-gray-700 transition hover:bg-gray-100 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                                <span class="material-symbols-outlined text-lg">dashboard</span>
                                Dashboard
                            </a>

                            <a href="{{ route('profile.edit') }}" 
                               class="mb-2 flex items-center gap-2 rounded-lg bg-white px-4 py-3 text-sm font-medium text-gray-700 transition hover:bg-gray-100 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                                <span class="material-symbols-outlined text-lg">settings</span>
                                Configuración
                            </a>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" 
                                        class="flex w-full items-center gap-2 rounded-lg bg-red-50 px-4 py-3 text-sm font-medium text-red-600 transition hover:bg-red-100 dark:bg-red-900/20 dark:text-red-400 dark:hover:bg-red-900/30">
                                    <span class="material-symbols-outlined text-lg">logout</span>
                                    Cerrar sesión
                                </button>
                            </form>
                        </div>
                    @else
                        @if (!request()->routeIs('login'))
                            <a href="{{ route('login') }}" 
                               class="mt-2 flex items-center justify-center gap-2 rounded-lg border border-gray-300 px-4 py-3 text-sm font-medium text-gray-700 dark:border-gray-700 dark:text-gray-300">
                                <span class="material-symbols-outlined text-lg">login</span>
                                Iniciar sesión
                            </a>
                        @endif
                        @if (Route::has('register') && !request()->routeIs('register'))
                            <a href="{{ route('register') }}" 
                               class="flex items-center justify-center gap-2 rounded-lg bg-gradient-to-r from-green-600 to-emerald-600 px-4 py-3 text-sm font-semibold text-white shadow-md">
                                <span class="material-symbols-outlined text-lg">person_add</span>
                                Registrarse
                            </a>
                        @endif
                    @endauth
                @endif
            </nav>
        </div>
    </div>
</header>

<script>
    // Mobile menu toggle
    document.addEventListener('DOMContentLoaded', function() {
        const menuBtn = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');
        
        if (menuBtn && mobileMenu) {
            menuBtn.addEventListener('click', function() {
                mobileMenu.classList.toggle('hidden');
            });
        }
    });
</script>
