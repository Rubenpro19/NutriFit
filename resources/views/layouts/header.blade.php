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
                    @php
                        $user = Auth::user();
                        $roleSlug = 'user'; // fallback
                        if (!empty($user->role->name)) {
                            $roleSlug = strtolower(str_replace(' ', '-', $user->role->name));
                        } elseif (!empty($user->role_name)) {
                            $roleSlug = strtolower(str_replace(' ', '-', $user->role_name));
                        } elseif (!empty($user->role_id)) {
                            $map = [1 => 'admin', 2 => 'paciente', 3 => 'nutricionista']; // ajusta según tus ids
                            $roleSlug = $map[$user->role_id] ?? 'user';
                        }
                        $dashboardUrl = url($roleSlug . '/dashboard');
                    @endphp

                    <a href="{{ $dashboardUrl }}" class="flex items-center gap-1 text-white hover:underline">
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
