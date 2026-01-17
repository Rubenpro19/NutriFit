<?php

use App\Models\SystemSetting;

if (!function_exists('system_settings')) {
    /**
     * Obtiene la configuración del sistema cacheada.
     * 
     * @param string|null $key Clave específica a obtener (opcional)
     * @return mixed
     */
    function system_settings(?string $key = null): mixed
    {
        $settings = SystemSetting::getCached();

        if ($settings === null) {
            return null;
        }

        if ($key !== null) {
            return $settings->$key ?? null;
        }

        return $settings;
    }
}

if (!function_exists('contact_phone')) {
    /**
     * Obtiene el teléfono de contacto formateado.
     */
    function contact_phone(): ?string
    {
        return system_settings()?->telefono_formateado;
    }
}

if (!function_exists('contact_phone_raw')) {
    /**
     * Obtiene el teléfono de contacto sin formato.
     */
    function contact_phone_raw(): ?string
    {
        return system_settings('telefono');
    }
}

if (!function_exists('contact_email')) {
    /**
     * Obtiene el email de contacto.
     */
    function contact_email(): ?string
    {
        return system_settings('email_contacto');
    }
}

if (!function_exists('contact_address')) {
    /**
     * Obtiene la dirección.
     */
    function contact_address(): ?string
    {
        return system_settings('direccion');
    }
}

if (!function_exists('contact_whatsapp_url')) {
    /**
     * Obtiene la URL de WhatsApp.
     */
    function contact_whatsapp_url(): ?string
    {
        return system_settings()?->whatsapp_url;
    }
}

if (!function_exists('contact_maps_url')) {
    /**
     * Obtiene la URL de Google Maps.
     */
    function contact_maps_url(): ?string
    {
        return system_settings()?->google_maps_url;
    }
}

if (!function_exists('contact_coordinates')) {
    /**
     * Obtiene las coordenadas de ubicación.
     */
    function contact_coordinates(): array
    {
        $settings = system_settings();
        
        return [
            'lat' => $settings?->latitud,
            'lng' => $settings?->longitud,
        ];
    }
}
