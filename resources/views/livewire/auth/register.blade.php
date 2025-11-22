<x-layouts.auth>
    <div class="flex flex-col gap-6">
        <x-auth-header :title="__('Crear una cuenta')" :description="__('Ingresa tus datos a continuación para crear tu cuenta')" />

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('register.store') }}" class="flex flex-col gap-6">
            @csrf

            <!-- Name -->
            <flux:input
                name="name"
                :label="__('Nombre')"
                type="text"
                required
                autofocus
                autocomplete="name"
                :placeholder="__('Nombre completo')"
                :value="old('name')"
            />

            <!-- Email Address -->
            <flux:input
                name="email"
                :label="__('Correo electrónico')"
                type="email"
                required
                autocomplete="email"
                placeholder="correo@ejemplo.com"
                :value="old('email')"
            />

            <!-- Password -->
            <flux:input
                name="password"
                :label="__('Contraseña')"
                type="password"
                required
                autocomplete="new-password"
                :placeholder="__('Contraseña')"
                viewable
            />

            <!-- Confirm Password -->
            <flux:input
                name="password_confirmation"
                :label="__('Confirmar contraseña')"
                type="password"
                required
                autocomplete="new-password"
                :placeholder="__('Confirmar contraseña')"
                viewable
            />

            <div class="flex items-center justify-end">
                <flux:button type="submit" variant="primary" class="w-full">
                    {{ __('Crear cuenta') }}
                </flux:button>
            </div>
        </form>

        <a href="{{ route('google.login') }}"
           class="flex items-center justify-center w-full gap-3 px-4 py-2 rounded-md border border-zinc-200 bg-white text-zinc-700 hover:bg-zinc-50 dark:bg-[#0a0a0a] dark:border-zinc-800 dark:text-zinc-200 dark:hover:bg-zinc-900 transition"
           aria-label="Iniciar sesión con Google">
            <svg class="w-5 h-5" viewBox="0 0 533.5 544.3" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
                <path fill="#4285F4" d="M533.5 278.4c0-17.4-1.4-34.1-3.9-50.4H272v95.3h146.9c-6.4 34.6-25 63.9-53.3 83.4v69.2h86.1c50.3-46.4 81.8-114.6 81.8-197.5z"/>
                <path fill="#34A853" d="M272 544.3c72.9 0 134.1-24.1 178.8-65.3l-86.1-69.2c-24 16.1-54.7 25.6-92.7 25.6-71 0-131.2-47.8-152.6-112.1H32.6v70.5C77.3 492.3 167 544.3 272 544.3z"/>
                <path fill="#FBBC05" d="M119.4 322.3c-5.9-17.7-9.3-36.6-9.3-56.3s3.4-38.6 9.3-56.3V139.2H32.6C11.7 177.9 0 220.5 0 266s11.7 88.1 32.6 126.8l86.8-70.5z"/>
                <path fill="#EA4335" d="M272 107.7c39.7 0 75.2 13.6 103.2 40.3l77.4-77.4C403.7 24.6 341.9 0 272 0 167 0 77.3 52 32.6 139.2l86.8 70.5C140.8 155.6 201 107.7 272 107.7z"/>
            </svg>

            <span class="text-sm font-medium">Continuar con Google</span>
        </a>

        <div class="space-x-1 rtl:space-x-reverse text-center text-sm text-zinc-600 dark:text-zinc-400">
            <span>{{ __('¿Ya tienes una cuenta?') }}</span>
            <flux:link :href="route('login')" wire:navigate>{{ __('Iniciar sesión') }}</flux:link>
        </div>
    </div>
</x-layouts.auth>
