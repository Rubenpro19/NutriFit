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
use App\Http\Controllers\AttentionController;
use App\Http\Controllers\SocialiteController;
use App\Http\Controllers\ContactController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/sobre-nosotros', function () {
    return view('about');
})->name('about');

Route::get('/contacto', function () {
    return view('contact');
})->name('contact');

Route::post('/contacto/enviar', [ContactController::class, 'send'])->name('contact.send');

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
Route::middleware(['auth', 'role:nutricionista'])->prefix('nutricionista')->name('nutricionista.')->group(function () {
    Route::get('/dashboard', [NutricionistaController::class, 'index'])->name('dashboard');
    
    // Gestión de horarios
    Route::get('/horarios', [NutricionistaController::class, 'schedules'])->name('schedules.index');
    Route::post('/horarios', [NutricionistaController::class, 'saveSchedules'])->name('schedules.save');
    
    // Gestión de pacientes
    Route::get('/pacientes', [NutricionistaController::class, 'patients'])->name('patients.index');
    Route::get('/pacientes/{patient}', [NutricionistaController::class, 'showPatient'])->name('patients.show');
    Route::get('/pacientes/{patient}/datos', [NutricionistaController::class, 'patientData'])->name('patients.data');
    
    // Asignar citas a pacientes
    Route::get('/citas/asignar', [NutricionistaController::class, 'createAppointment'])->name('appointments.create');
    Route::get('/citas/asignar/{paciente}/horarios', [NutricionistaController::class, 'getAvailableSchedules'])->name('appointments.schedules');
    Route::post('/citas/asignar', [NutricionistaController::class, 'storeAppointment'])->name('appointments.store');
    
    // Gestión de citas
    Route::get('/citas', [NutricionistaController::class, 'appointments'])->name('appointments.index');
    Route::get('/appointments/{appointment}', [NutricionistaController::class, 'showAppointment'])->name('appointments.show');
    Route::post('/appointments/{appointment}/cancel', [NutricionistaController::class, 'cancelAppointment'])->name('appointments.cancel');
    Route::get('/appointments/{appointment}/reschedule', [NutricionistaController::class, 'rescheduleForm'])->name('appointments.reschedule');
    Route::post('/appointments/{appointment}/reschedule', [NutricionistaController::class, 'rescheduleAppointment'])->name('appointments.reschedule.store');
    
    // Gestión de atenciones
    Route::get('/citas/{appointment}/atender', [AttentionController::class, 'create'])->name('attentions.create');
    Route::post('/citas/{appointment}/atender', [AttentionController::class, 'store'])->name('attentions.store');
    
    // Perfil
    Route::get('/perfil', function() {
        return view('nutricionista.profile');
    })->name('profile');
});

// Panel paciente - Ruta para cambiar contraseña por defecto (sin middleware password.changed)
Route::middleware(['auth', 'role:paciente'])->prefix('paciente')->name('paciente.')->group(function () {
    Route::get('/cambiar-contrasena', [App\Http\Controllers\PasswordController::class, 'showChangePassword'])->name('change-default-password');
    Route::post('/cambiar-contrasena', [App\Http\Controllers\PasswordController::class, 'updatePassword'])->name('change-default-password.update');
});

// Panel paciente - Rutas protegidas (con middleware password.changed)
Route::middleware(['auth', 'role:paciente', 'password.changed'])->prefix('paciente')->name('paciente.')->group(function () {
    Route::get('/dashboard', [PacienteController::class, 'index'])->name('dashboard');
    
    // Agendar citas
    Route::get('/agendar', [PacienteController::class, 'showBooking'])->name('booking.index');
    Route::get('/agendar/{nutricionista}', [PacienteController::class, 'selectSchedule'])->name('booking.schedule');
    Route::post('/agendar/{nutricionista}', [PacienteController::class, 'storeAppointment'])->name('booking.store');
    
    // Gestión de citas
    Route::get('/citas', [PacienteController::class, 'appointments'])->name('appointments.index');
    Route::get('/citas/{appointment}', [PacienteController::class, 'showAppointment'])->name('appointments.show');
    Route::post('/citas/{appointment}/cancelar', [PacienteController::class, 'cancelAppointment'])->name('appointments.cancel');
    
    // Perfil de usuario
    Route::get('/perfil', [PacienteController::class, 'profile'])->name('profile');
});

//Rutas para iniciar sesión con Google
Route::get('auth/google', [SocialiteController::class, 'redirectToGoogle'])->name('google.login');
Route::get('auth/google/callback', [SocialiteController::class, 'handleGoogleCallback']);