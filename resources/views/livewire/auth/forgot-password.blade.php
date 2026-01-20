<x-layouts.auth>
    <div class="flex flex-col gap-6">
        <x-auth-header :title="__('¿Olvidaste tu contraseña?')" :description="__('Ingresa tu correo electrónico para recibir un enlace de recuperación')" />

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('password.email') }}" class="flex flex-col gap-6" x-data="{ submitting: false }" @submit="submitting = true">
            @csrf

            <!-- Email Address -->
            <flux:input
                name="email"
                :label="__('Correo electrónico')"
                type="email"
                required
                autofocus
                placeholder="correo@ejemplo.com"
                :value="old('email')"
            />

            <flux:button variant="primary" type="submit" class="w-full" data-test="email-password-reset-link-button" x-bind:disabled="submitting">
                <span x-show="!submitting">{{ __('Enviar enlace de recuperación') }}</span>
                <svg x-show="submitting" x-cloak class="animate-spin h-5 w-5 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </flux:button>
        </form>

        <div class="space-x-1 rtl:space-x-reverse text-center text-sm text-zinc-400">
            <span>{{ __('O, volver a') }}</span>
            <flux:link :href="route('login')" wire:navigate>{{ __('iniciar sesión') }}</flux:link>
        </div>
    </div>
</x-layouts.auth>
