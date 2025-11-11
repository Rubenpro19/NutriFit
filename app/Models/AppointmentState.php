<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppointmentState extends Model
{
    protected $table = 'appointment_states';

    protected $fillable = [
        'name',
        'description',
    ];
}
