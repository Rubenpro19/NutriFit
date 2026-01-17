<?php

namespace App\Livewire\Admin;

use App\Models\SystemSetting;
use Livewire\Component;

class SystemSettings extends Component
{
    public string $telefono = '';
    public string $email_contacto = '';
    public string $direccion = '';
    public ?float $latitud = null;
    public ?float $longitud = null;

    protected $rules = [
        'telefono' => 'nullable|string|max:20',
        'email_contacto' => 'nullable|email|max:255',
        'direccion' => 'nullable|string|max:255',
        'latitud' => 'nullable|numeric|between:-90,90',
        'longitud' => 'nullable|numeric|between:-180,180',
    ];

    protected $messages = [
        'telefono.max' => 'El teléfono no debe exceder 20 caracteres.',
        'email_contacto.email' => 'El correo debe ser una dirección válida.',
        'email_contacto.max' => 'El correo no debe exceder 255 caracteres.',
        'direccion.max' => 'La dirección no debe exceder 255 caracteres.',
        'latitud.numeric' => 'La latitud debe ser un número.',
        'latitud.between' => 'La latitud debe estar entre -90 y 90.',
        'longitud.numeric' => 'La longitud debe ser un número.',
        'longitud.between' => 'La longitud debe estar entre -180 y 180.',
    ];

    public function mount(): void
    {
        $settings = SystemSetting::first();

        if ($settings) {
            $this->telefono = $settings->telefono ?? '';
            $this->email_contacto = $settings->email_contacto ?? '';
            $this->direccion = $settings->direccion ?? '';
            $this->latitud = $settings->latitud;
            $this->longitud = $settings->longitud;
        }
    }

    public function save(): void
    {
        $this->validate();

        SystemSetting::updateSettings([
            'telefono' => $this->telefono ?: null,
            'email_contacto' => $this->email_contacto ?: null,
            'direccion' => $this->direccion ?: null,
            'latitud' => $this->latitud,
            'longitud' => $this->longitud,
        ]);

        session()->flash('success', 'Configuración actualizada correctamente.');
    }

    public function render()
    {
        return view('livewire.admin.system-settings');
    }
}
