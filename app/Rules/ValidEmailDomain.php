<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidEmailDomain implements ValidationRule
{
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Extraer el dominio del email
        $domain = substr(strrchr($value, "@"), 1);
        
        // Verificar si el dominio tiene registros MX (servidores de correo)
        if (!checkdnsrr($domain, 'MX')) {
            $fail('El dominio del correo electrónico no es válido o no puede recibir correos.');
        }
    }
}
