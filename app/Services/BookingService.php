<?php

namespace App\Services;

use App\Http\Requests\Api\CreateBookingRequest;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Support\Collection;

class BookingService
{
    public function getUserBookings(User $user): Collection
    {
        return Booking::where('user_id', $user->id)->get();
    }

    public function createBooking(CreateBookingRequest $request, User $user): Booking
    {
        $booking = new Booking();
        $booking->fill($request->all());
        $booking->user_id = $user->id;
        $booking->status = Booking::STATUS_CONFIRMED;
        $booking->save();

        return $booking;
    }

    public function cancelBooking(Booking $booking): Booking
    {
        $booking->update(['status' => Booking::STATUS_CANCELED]);

        return $booking;
    }
}
