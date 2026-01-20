<?php

namespace App\Actions\Fortify;

use App\Models\User;
use App\Rules\ValidEmailDomain;
use App\Notifications\WelcomeNotification;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),
                new ValidEmailDomain(), // Validación DNS/MX
            ],
            'password' => $this->passwordRules(),
            'data_consent' => ['required', 'accepted'],
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'name.max' => 'El nombre no puede exceder los 255 caracteres.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El correo electrónico debe ser una dirección válida.',
            'email.unique' => 'Este correo electrónico ya está registrado.',
            'email.max' => 'El correo electrónico no puede exceder los 255 caracteres.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.confirmed' => 'La confirmación de la contraseña no coincide.',
            'password.min' => 'La contraseña debe tener al menos :min caracteres.',
            'data_consent.required' => 'Debes aceptar el tratamiento de datos personales para continuar.',
            'data_consent.accepted' => 'Debes aceptar el tratamiento de datos personales para continuar.',
        ], [
            'name' => 'nombre',
            'email' => 'correo electrónico',
            'password' => 'contraseña',
            'data_consent' => 'consentimiento de datos',
        ])->validate();

        $user = User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => $input['password'],
            'role_id' => 3,
            'data_consent' => true,
            'data_consent_at' => now(),
        ]);

        // El correo de bienvenida se enviará después de verificar el email
        // Ver: App\Listeners\SendWelcomeNotification

        return $user;
    }
}
