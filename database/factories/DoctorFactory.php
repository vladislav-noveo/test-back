<?php

namespace Database\Factories;

use App\Models\Doctor;
use Illuminate\Database\Eloquent\Factories\Factory;

class DoctorFactory extends Factory
{

    protected $model = Doctor::class;

    public function definition()
    {
        return [
            'name' => "Dr. " . $this->faker->firstName() . " " . $this->faker->lastName(),
            'agenda' => Doctor::AGENDA_DATABASE,
        ];
    }

    public function withAgenda($agenda)
    {
        return $this->state(function () use ($agenda) {
            return [
                'agenda' => $agenda,
                'external_agenda_id' => $this->faker->uuid()
            ];
        });
    }
}
