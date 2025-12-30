<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class PersonalData extends Model
{
    protected $table = 'personal_data';

    protected $fillable = [
        'user_id',
        'phone',
        'address',
        'birth_date',
        'gender',
        'profile_photo',
    ];

    protected $casts = [
        'birth_date' => 'date',
    ];

    protected $appends = [
        'age',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getAgeAttribute(): ?int
    {
        if (! $this->birth_date) {
            return null;
        }

        return Carbon::parse($this->birth_date)->age;
    }
}
