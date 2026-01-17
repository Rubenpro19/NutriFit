<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SystemSetting extends Model
{
    protected $fillable = [
        'telefono',
        'email_contacto',
        'direccion',
        'latitud',
        'longitud',
    ];

    protected $casts = [
        'latitud' => 'float',
        'longitud' => 'float',
    ];

    /**
     * Clave de cache para la configuración del sistema.
     */
    const CACHE_KEY = 'system_settings';

    /**
     * Tiempo de cache en segundos (24 horas).
     */
    const CACHE_TTL = 86400;

    /**
     * Obtiene la configuración del sistema desde cache o base de datos.
     */
    public static function getCached(): ?self
    {
        return Cache::remember(self::CACHE_KEY, self::CACHE_TTL, function () {
            return self::first();
        });
    }

    /**
     * Limpia el cache de configuración.
     */
    public static function clearCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }

    /**
     * Actualiza la configuración y limpia el cache.
     */
    public static function updateSettings(array $data): self
    {
        $settings = self::firstOrCreate([]);
        $settings->update($data);
        self::clearCache();
        
        return $settings;
    }

    /**
     * Obtiene la URL de Google Maps para la ubicación.
     */
    public function getGoogleMapsUrlAttribute(): ?string
    {
        if ($this->latitud && $this->longitud) {
            return "https://www.google.com/maps?q={$this->latitud},{$this->longitud}";
        }
        
        return null;
    }

    /**
     * Obtiene la URL de WhatsApp.
     */
    public function getWhatsappUrlAttribute(): ?string
    {
        if ($this->telefono) {
            // Limpiar el número de teléfono (solo dígitos)
            $phone = preg_replace('/[^0-9]/', '', $this->telefono);
            return "https://wa.me/{$phone}";
        }
        
        return null;
    }

    /**
     * Obtiene el número de teléfono formateado.
     */
    public function getTelefonoFormateadoAttribute(): ?string
    {
        if (!$this->telefono) {
            return null;
        }

        $phone = preg_replace('/[^0-9]/', '', $this->telefono);
        
        // Formato: +593 98 466 8389
        if (strlen($phone) === 12 && str_starts_with($phone, '593')) {
            return '+' . substr($phone, 0, 3) . ' ' . substr($phone, 3, 2) . ' ' . substr($phone, 5, 3) . ' ' . substr($phone, 8);
        }
        
        return $this->telefono;
    }
}
