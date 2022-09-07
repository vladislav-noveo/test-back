<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    public const STATUS_CONFIRMED = 'STATUS_CONFIRMED';
    public const STATUS_CANCELED = 'STATUS_CANCELED';

    protected $casts = [
        'date' => 'datetime',
    ];

    protected $fillable = [
        'user_id',
        'doctor_id',
        'date',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
}
