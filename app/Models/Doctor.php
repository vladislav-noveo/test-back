<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;

    const AGENDA_DATABASE = 'database';
    const AGENDA_DOCTOLIB = 'doctolib';
    const AGENDA_CLICRDV = 'clicrdv';

    public function availabilities()
    {
        return $this->hasMany(Availability::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
