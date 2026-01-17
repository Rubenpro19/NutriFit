<div>
    {{-- Mensaje de éxito --}}
    @if(session('success'))
        <div class="mb-6 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-800 dark:text-green-200 px-4 py-3 rounded-xl flex items-center gap-3">
            <span class="material-symbols-outlined">check_circle</span>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    {{-- Formulario de Configuración --}}
    <form wire:submit="save" class="space-y-6">
        {{-- Información de Contacto --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center gap-3 mb-6">
                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-green-100 dark:bg-green-900/40">
                    <span class="material-symbols-outlined text-xl text-green-600 dark:text-green-400">contact_phone</span>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Información de Contacto</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Datos de contacto que aparecerán en el sitio</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Teléfono --}}
                <div>
                    <label for="telefono" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        <span class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-base">phone</span>
                            Teléfono / WhatsApp
                        </span>
                    </label>
                    <input type="text" 
                           wire:model="telefono" 
                           id="telefono"
                           placeholder="593984668389"
                           class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-green-500 focus:border-transparent transition">
                    @error('telefono')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Ingresa solo números, sin espacios ni símbolos (ej: 593984668389)</p>
                </div>

                {{-- Email --}}
                <div>
                    <label for="email_contacto" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        <span class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-base">mail</span>
                            Correo de Contacto
                        </span>
                    </label>
                    <input type="email" 
                           wire:model="email_contacto" 
                           id="email_contacto"
                           placeholder="contacto@nutrifit.com"
                           class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-green-500 focus:border-transparent transition">
                    @error('email_contacto')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Ubicación --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center gap-3 mb-6">
                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-purple-100 dark:bg-purple-900/40">
                    <span class="material-symbols-outlined text-xl text-purple-600 dark:text-purple-400">location_on</span>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Ubicación</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Dirección y coordenadas para el mapa</p>
                </div>
            </div>

            <div class="space-y-6">
                {{-- Dirección --}}
                <div>
                    <label for="direccion" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        <span class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-base">home</span>
                            Dirección
                        </span>
                    </label>
                    <input type="text" 
                           wire:model="direccion" 
                           id="direccion"
                           placeholder="Santa Ana, Manabí"
                           class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-green-500 focus:border-transparent transition">
                    @error('direccion')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Coordenadas --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="latitud" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <span class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-base">explore</span>
                                Latitud
                            </span>
                        </label>
                        <input type="number" 
                               wire:model="latitud" 
                               id="latitud"
                               step="0.000001"
                               placeholder="-1.205192"
                               class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-green-500 focus:border-transparent transition">
                        @error('latitud')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="longitud" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <span class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-base">explore</span>
                                Longitud
                            </span>
                        </label>
                        <input type="number" 
                               wire:model="longitud" 
                               id="longitud"
                               step="0.000001"
                               placeholder="-80.372294"
                               class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-green-500 focus:border-transparent transition">
                        @error('longitud')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Ayuda para obtener coordenadas --}}
                <div class="p-4 rounded-xl bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800">
                    <div class="flex items-start gap-3">
                        <span class="material-symbols-outlined text-blue-600 dark:text-blue-400">info</span>
                        <div class="text-sm text-blue-800 dark:text-blue-200">
                            <p class="font-medium mb-1">¿Cómo obtener las coordenadas?</p>
                            <ol class="list-decimal list-inside space-y-1 text-blue-700 dark:text-blue-300">
                                <li>Abre <a href="https://www.google.com/maps" target="_blank" class="underline hover:text-blue-900 dark:hover:text-blue-100">Google Maps</a></li>
                                <li>Busca la ubicación deseada</li>
                                <li>Haz clic derecho en el punto exacto</li>
                                <li>Copia las coordenadas que aparecen (latitud, longitud)</li>
                            </ol>
                        </div>
                    </div>
                </div>

                {{-- Vista previa del mapa --}}
                @if($latitud && $longitud)
                    <div class="mt-4">
                        <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Vista previa de ubicación:</p>
                        <div class="rounded-xl overflow-hidden border border-gray-200 dark:border-gray-600">
                            <iframe 
                                src="https://www.google.com/maps/embed?pb=!1m17!1m12!1m3!1d3987.268689!2d{{ $longitud }}!3d{{ $latitud }}!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m2!1m1!2zMcKwMTInMTguNyJTIDgwwrAyMicyMC4zIlc!5e0!3m2!1ses!2sec"
                                width="100%" 
                                height="200" 
                                style="border:0;" 
                                allowfullscreen="" 
                                loading="lazy"
                                class="w-full">
                            </iframe>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- Botón Guardar --}}
        <div class="flex justify-end">
            <button type="submit" 
                    class="flex items-center gap-2 px-8 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white font-semibold rounded-xl hover:from-green-700 hover:to-emerald-700 focus:outline-none focus:ring-4 focus:ring-green-300 dark:focus:ring-green-800 transition-all shadow-lg hover:shadow-xl">
                <span class="material-symbols-outlined" wire:loading.remove wire:target="save">save</span>
                <span class="material-symbols-outlined animate-spin" wire:loading wire:target="save">refresh</span>
                <span wire:loading.remove wire:target="save">Guardar Cambios</span>
                <span wire:loading wire:target="save">Guardando...</span>
            </button>
        </div>
    </form>
</div>
