{{-- FOOTER --}}
<footer class="mt-auto border-t border-gray-200 bg-gray-50 dark:border-gray-800 dark:bg-gray-900">
    <div class="container mx-auto px-4 py-4">
        <div class="flex flex-col items-center justify-between gap-4 md:flex-row">
            {{-- Logo y Copyright --}}
            <div class="flex items-center gap-3">
                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-gradient-to-br from-green-500 to-emerald-600">
                    <span class="material-symbols-outlined text-lg text-white">eco</span>
                </div>
                <div class="text-center md:text-left">
                    <span class="text-sm font-semibold text-gray-900 dark:text-white">
                        Nutri<span class="text-green-600">Fit</span>
                    </span>
                    <p class="text-xs text-gray-600 dark:text-gray-300">© {{ date('Y') }} Todos los derechos reservados</p>
                </div>
            </div>

            {{-- Información de Contacto --}}
            <div class="flex flex-wrap items-center justify-center gap-6 text-sm">
                <a href="mailto:contacto@nutrifit.ec" class="flex items-center gap-2 text-gray-600 transition hover:text-green-600 dark:text-gray-300 dark:hover:text-green-500">
                    <span class="material-symbols-outlined text-base">mail</span>
                    contacto@nutrifit.ec
                </a>
                <span class="hidden text-gray-300 dark:text-gray-700 md:inline">•</span>
                <a href="tel:+593987654321" class="flex items-center gap-2 text-gray-600 transition hover:text-green-600 dark:text-gray-300 dark:hover:text-green-500">
                    <span class="material-symbols-outlined text-base">phone</span>
                    +593 98 765 4321
                </a>
                <span class="hidden text-gray-300 dark:text-gray-700 md:inline">•</span>
                <span class="flex items-center gap-2 text-gray-600 dark:text-gray-300">
                    <span class="material-symbols-outlined text-base">location_on</span>
                    Santa Ana, Manabí
                </span>
            </div>
        </div>
    </div>
</footer>