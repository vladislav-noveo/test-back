<?php

namespace Database\Factories;

use App\Models\Availability;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class AvailabilityFactory extends Factory
{

    protected $model = Availability::class;

    public function definition()
    {
        $start = $this->faker->dateTimeBetween('now', '+2 month');

        return [
            'start' => $start,
            'end' => Carbon::parse($start)->addMinutes(30)
        ];
    }
}
