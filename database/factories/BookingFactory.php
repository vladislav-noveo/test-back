<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Doctor;
use App\Models\Booking;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookingFactory extends Factory
{

    protected $model = Booking::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory()->create()->id,
            'doctor_id' => Doctor::factory()->create()->id,
            'date' => $this->faker->dateTime(),
            'status' => $this->faker->randomElement([Booking::STATUS_CONFIRMED, Booking::STATUS_CANCELED]),
        ];
    }
}
