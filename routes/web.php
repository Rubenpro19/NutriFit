<?php

use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Livewire\Settings\TwoFactor;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\NutricionistaController;
use App\Http\Controllers\PacienteController;
use App\Http\Controllers\SocialiteController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');
    Route::get('settings/profile', Profile::class)->name('profile.edit');
    Route::get('settings/password', Password::class)->name('user-password.edit');
    Route::get('settings/appearance', Appearance::class)->name('appearance.edit');

    Route::get('settings/two-factor', TwoFactor::class)
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');
});

// Panel administrador
Route::middleware(['auth', 'role:administrador'])->prefix('administrador')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
    
    // Gestión de usuarios
    Route::get('/users', [AdminController::class, 'users'])->name('users.index');
    Route::get('/users/create', [AdminController::class, 'createUser'])->name('users.create');
    Route::post('/users', [AdminController::class, 'storeUser'])->name('users.store');
    Route::get('/users/{user}/edit', [AdminController::class, 'editUser'])->name('users.edit');
    Route::put('/users/{user}', [AdminController::class, 'updateUser'])->name('users.update');
    Route::post('/users/{user}/toggle-status', [AdminController::class, 'toggleUserStatus'])->name('users.toggle-status');
    
    // Gestión de nutricionistas
    Route::get('/nutricionistas', [AdminController::class, 'nutricionistas'])->name('nutricionistas.index');
    Route::get('/nutricionistas/{nutricionista}', [AdminController::class, 'showNutricionista'])->name('nutricionistas.show');
    
    // Gestión de pacientes
    Route::get('/pacientes', [AdminController::class, 'pacientes'])->name('pacientes.index');
    Route::get('/pacientes/{paciente}', [AdminController::class, 'showPaciente'])->name('pacientes.show');
    
    // Gestión de citas
    Route::get('/appointments', [AdminController::class, 'appointments'])->name('appointments.index');
    Route::get('/appointments/{appointment}', [AdminController::class, 'showAppointment'])->name('appointments.show');
    Route::post('/appointments/{appointment}/cancel', [AdminController::class, 'cancelAppointment'])->name('appointments.cancel');
    
    // Reportes y configuración
    Route::get('/reports', [AdminController::class, 'reports'])->name('reports.index');
    Route::get('/settings', [AdminController::class, 'settings'])->name('settings.index');
});


// Panel nutricionista
Route::middleware(['auth', 'role:nutricionista'])->prefix('nutricionista')->group(function () {
    Route::get('/dashboard', [NutricionistaController::class, 'index'])->name('nutricionista.dashboard');
});

// Panel paciente
Route::middleware(['auth', 'role:paciente'])->prefix('paciente')->group(function () {
    Route::get('/dashboard', [PacienteController::class, 'index'])->name('paciente.dashboard');
});

//Rutas para iniciar sesión con Google
Route::get('auth/google', [SocialiteController::class, 'redirectToGoogle'])->name('google.login');
Route::get('auth/google/callback', [SocialiteController::class, 'handleGoogleCallback']);