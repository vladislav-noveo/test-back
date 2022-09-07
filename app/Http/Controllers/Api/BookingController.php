<?php

namespace App\Http\Controllers\Api;

use App\Services\BookingService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CreateBookingRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\BookingResource;
use App\Models\Booking;

class BookingController extends Controller
{
    public function getForUser(BookingService $service)
    {
        $bookings = $service->getUserBookings(Auth::user());

        return BookingResource::collection($bookings);
    }

    public function create(CreateBookingRequest $request, BookingService $service)
    {
        $booking = $service->createBooking($request, Auth::user());

        return BookingResource::make($booking);
    }

    public function cancel(Booking $booking, BookingService $service)
    {
        $this->authorize('cancel', $booking);
        $booking = $service->cancelBooking($booking);

        return BookingResource::make($booking);
    }
}
