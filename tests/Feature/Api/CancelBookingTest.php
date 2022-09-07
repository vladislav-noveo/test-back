<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Booking;

class CancelBookingTest extends ApiTestCase
{
    public function testCanCancelOwnBooking()
    {
        $actingUser = User::factory()->create();
        $booking = Booking::factory([
            'status' => Booking::STATUS_CONFIRMED,
            'user_id' => $actingUser->id,
        ])->create();

        $this->actingAs($actingUser);
        $response = $this->getJson(route('bookings.cancel', ['booking' => $booking->id]));
        $response->assertSuccessful();

        $response->assertJsonFragment(['status' => Booking::STATUS_CANCELED]);

        $this->assertDatabaseHas('bookings', ['id' => $booking->id, 'status' => Booking::STATUS_CANCELED]);
    }

    public function testCannotCancelAnotherUserBooking()
    {
        $actingUser = User::factory()->create();
        $booking = Booking::factory([
            'status' => Booking::STATUS_CONFIRMED,
        ])->create();

        $this->actingAs($actingUser);
        $response = $this->getJson(route('bookings.cancel', ['booking' => $booking->id]));
        $response->assertForbidden();
    }
}
