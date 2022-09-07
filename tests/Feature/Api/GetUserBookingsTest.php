<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Doctor;
use App\Models\Booking;

class GetUserBookingsTest extends ApiTestCase
{
    public function testGetUserBookings()
    {
        $loginUser = User::factory()->create();
        $this->actingAs($loginUser);

        $doctorOne = Doctor::factory(['name' => 'Test one'])->create();
        $doctorTwo = Doctor::factory(['name' => 'Test two'])->create();


        $bookingOne = Booking::factory(['doctor_id' => $doctorOne->id, 'user_id' => $loginUser->id])->create();
        $bookingTwo = Booking::factory(['doctor_id' => $doctorTwo->id, 'user_id' => $loginUser->id])->create();
        $otherUsersBookingsOne = Booking::factory(5, ['doctor_id' => $doctorOne->id])->create();
        $otherUsersBookingsTwo = Booking::factory(5, ['doctor_id' => $doctorTwo->id])->create();
        $response = $this->getJson(route('bookings.get'));

        $response->assertSuccessful();

        $response->assertJsonCount(2, 'data');
        $response->assertJsonStructure([
            'data' => [
                [
                    'id',
                    'doctor_id',
                    'user_id',
                    'date',
                    'status',
                ],
            ],
        ]);
    }
}
