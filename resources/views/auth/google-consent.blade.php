<x-layouts.auth.simple>
    <div class="flex flex-col gap-6">
        <div class="text-center">
            <h1 class="text-xl font-semibold text-zinc-900 dark:text-white">
                {{ __('Completa tu registro') }}
            </h1>
            <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-400">
                {{ __('Para continuar con tu cuenta de Google, necesitamos tu consentimiento.') }}
            </p>
        </div>

        <!-- User Info from Google -->
        <div class="bg-zinc-50 dark:bg-zinc-800 rounded-lg p-4 border border-zinc-200 dark:border-zinc-700">
            <div class="flex items-center gap-3">
                <div class="flex items-center justify-center w-10 h-10 rounded-full bg-primary-100 dark:bg-primary-900 text-primary-600 dark:text-primary-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <div>
                    <p class="font-medium text-zinc-900 dark:text-white">{{ $googleUser['name'] }}</p>
                    <p class="text-sm text-zinc-500 dark:text-zinc-400">{{ $googleUser['email'] }}</p>
                </div>
            </div>
        </div>

        @if ($errors->any())
            <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
                <ul class="text-sm text-red-600 dark:text-red-400">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('google.consent.process') }}" class="flex flex-col gap-6" x-data="{ submitting: false }" @submit="submitting = true">
            @csrf

            <!-- Data Protection Consent (LOPD Ecuador) -->
            <div class="flex items-start gap-3">
                <input
                    type="checkbox"
                    name="data_consent"
                    id="data_consent"
                    value="1"
                    required
                    class="mt-1 h-4 w-4 rounded border-zinc-300 text-primary-600 focus:ring-primary-500 dark:border-zinc-600 dark:bg-zinc-800"
                    {{ old('data_consent') ? 'checked' : '' }}
                />
                <label for="data_consent" class="text-sm text-zinc-600 dark:text-zinc-400">
                    Acepto el tratamiento de mis datos personales conforme a la
                    <a href="{{ route('privacy') }}" target="_blank" class="text-primary-600 hover:text-primary-500 underline dark:text-primary-400">Ley Orgánica de Protección de Datos Personales del Ecuador</a>
                    y la
                    <a href="{{ route('privacy') }}" target="_blank" class="text-primary-600 hover:text-primary-500 underline dark:text-primary-400">Política de Privacidad</a>
                    de NutriFit.
                    <span class="text-red-500">*</span>
                </label>
            </div>

            <div class="flex flex-col gap-3">
                <flux:button type="submit" variant="primary" class="w-full" x-bind:disabled="submitting">
                    <span x-show="!submitting">{{ __('Crear cuenta') }}</span>
                    <svg x-show="submitting" x-cloak class="animate-spin h-5 w-5 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </flux:button>

                <a href="{{ route('login') }}" class="text-center text-sm text-zinc-600 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-zinc-200">
                    {{ __('Cancelar y volver al inicio') }}
                </a>
            </div>
        </form>
    </div>
</x-layouts.auth.simple>
