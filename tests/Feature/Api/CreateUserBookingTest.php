<?php

namespace Feature\Api;

use App\Models\User;
use App\Models\Doctor;
use Illuminate\Support\Carbon;
use Tests\Feature\Api\ApiTestCase;

class CreateUserBookingTest extends ApiTestCase
{
    private User $actingUser;

    public function __construct($name = null, array $data = array(), $dataName = '') {
        parent::__construct($name, $data, $dataName);

        $this->createApplication();
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingUser = User::factory()->create();
    }

    public function testCreateSuccessful()
    {
        $bookingDate = Carbon::now()->addHour();
        $this->actingAs($this->actingUser);
        $doctor = Doctor::factory()->create();
        $response = $this->postJson(route('bookings.create'), [
            'doctor_id' => $doctor->id,
            'user_id' => $this->actingUser->id,
            'date' => $bookingDate
        ]);

        $response->assertSuccessful();
        $response->assertJsonStructure([
            'data' => [
                'id',
                'doctor_id',
                'user_id',
                'date',
                'status',
            ]
        ]);

        $this->assertDatabaseHas('bookings', ['date' => $bookingDate, 'doctor_id' => $doctor->id, 'user_id' => $this->actingUser->id]);
    }

    /**
     * @dataProvider invalidDataProvider
     */
    public function testValidationFailed(array $data, string $errorField, string $errorText)
    {
        $this->actingAs($this->actingUser);
        $response = $this->postJson(route('bookings.create'), $data);

        $response->assertJsonValidationErrors([$errorField => $errorText]);
    }

    public function invalidDataProvider(): array
    {
        $doctorId = Doctor::all()->max('id') + 5;
        return [
            [
                'data' => [
                    'doctor_id' => $doctorId,
                ],
                'errorField' => 'doctor_id',
                'errorText' => 'The selected doctor id is invalid.',
            ],
            [
                'data' => [
                    'doctor_id' => null,
                ],
                'errorField' => 'doctor_id',
                'errorText' => 'The doctor id field is required',
            ],
            [
                'data' => [
                    'doctor_id' => 'String is not valid',
                ],
                'errorField' => 'doctor_id',
                'errorText' => 'The doctor id must be a number',
            ],
            [
                'data' => [
                    'date' => null,
                ],
                'errorField' => 'date',
                'errorText' => 'The date field is required',
            ],

            [
                'data' => [
                    'date' => 'Not date string',
                ],
                'errorField' => 'date',
                'errorText' => 'The date is not a valid date',
            ],
            [
                'data' => [
                    'date' => Carbon::now()->subDay(),
                ],
                'errorField' => 'date',
                'errorText' => 'The date must be a date after now',
            ],
        ];
    }
}
