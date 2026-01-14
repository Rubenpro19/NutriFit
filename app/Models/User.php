<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, TwoFactorAuthenticatable;

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = ['personalData'];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'email_verified_at',
        'password',
        'role_id',
        'user_state_id',
    ];

    protected $hidden = [
        'password',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    public function profilePhotoUrl(): ?string
    {
        if ($this->personalData && $this->personalData->profile_photo) {
            return asset('storage/' . $this->personalData->profile_photo);
        }
        return null;
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function userState(): BelongsTo
    {
        return $this->belongsTo(UserState::class, 'user_state_id');
    }

    public function personalData(): HasOne
    {
        return $this->hasOne(PersonalData::class);
    }

    public function nutricionistaSettings(): HasOne
    {
        return $this->hasOne(NutricionistaSettings::class);
    }

    public function appointmentsAsPaciente(): HasMany
    {
        return $this->hasMany(Appointment::class, 'paciente_id');
    }

    public function appointmentsAsNutricionista(): HasMany
    {
        return $this->hasMany(Appointment::class, 'nutricionista_id');
    }

    public function attentionsAsPaciente(): HasMany
    {
        return $this->hasMany(Attention::class, 'paciente_id');
    }

    public function attentionsAsNutricionista(): HasMany
    {
        return $this->hasMany(Attention::class, 'nutricionista_id');
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(NutricionistaSchedule::class, 'nutricionista_id');
    }

    // 🔹 Helpers para roles
    public function isAdmin(): bool
    {
        return $this->role?->name === 'administrador';
    }

    public function isNutricionista(): bool
    {
        return $this->role?->name === 'nutricionista';
    }

    public function isPaciente(): bool
    {
        return $this->role?->name === 'paciente';
    }

    // 🔹 Helpers para estado
    public function isActive(): bool
    {
        return $this->user_state_id === 1; // 1 = activo
    }

    public function isInactive(): bool
    {
        return $this->user_state_id === 2; // 2 = inactivo
    }

    /**
     * Send the email verification notification.
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new \App\Notifications\VerifyEmailNotification);
    }
}
