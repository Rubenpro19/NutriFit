{{-- HEADER --}}
    <header class="bg-[#8be28b] flex justify-between items-center px-8 py-3 shadow">
        <div class="flex items-center gap-2">
            <span class="material-symbols-outlined text-white text-2xl">eco</span>
            <a href="{{ route('home') }}" class="text-white font-semibold text-lg">NutriFit</a>
        </div>

        <nav class="flex items-center gap-6">
            <a href="#" class="flex items-center gap-1 text-white hover:underline">
                <span class="material-symbols-outlined text-sm">group</span>
                Sobre Nosotros
            </a>
            <a href="#" class="flex items-center gap-1 text-white hover:underline">
                <span class="material-symbols-outlined text-sm">mail</span>
                Contacto
            </a>
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}" class="flex items-center gap-1 text-white hover:underline">
                        <span class="material-symbols-outlined text-sm">dashboard</span>
                        Dashboard
                    </a>
                @else
                    @if (request()->routeIs('login'))
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="flex items-center gap-1 text-white hover:underline">
                                <span class="material-symbols-outlined text-sm">person_add</span>
                                Registrarse
                            </a>
                        @endif
                    @elseif (request()->routeIs('register'))
                        <a href="{{ route('login') }}" class="flex items-center gap-1 text-white hover:underline">
                            <span class="material-symbols-outlined text-sm">login</span>
                            Iniciar sesión
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="flex items-center gap-1 text-white hover:underline">
                            <span class="material-symbols-outlined text-sm">login</span>
                            Iniciar sesión
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="flex items-center gap-1 text-white hover:underline">
                                <span class="material-symbols-outlined text-sm">person_add</span>
                                Registrarse
                            </a>
                        @endif
                    @endif
                @endauth
            @endif
        </nav>
    </header>
