<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Booking;
use Illuminate\Auth\Access\HandlesAuthorization;

class BookingPolicy
{
    use HandlesAuthorization;

    public function cancel(User $user, Booking $booking): bool
    {
        return $booking->user_id == $user->id;
    }
}
