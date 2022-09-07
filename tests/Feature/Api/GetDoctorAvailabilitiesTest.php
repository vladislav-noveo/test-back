<?php

namespace Tests\Feature\Api;

use App\DTO\AvailabilityDto;
use App\Models\Doctor;
use Mockery\MockInterface;
use App\Models\Availability;
use App\Models\Booking;
use Illuminate\Support\Carbon;
use App\Services\External\ClicRDVService;
use App\Services\External\DoctolibService;

class GetDoctorAvailabilitiesTest extends ApiTestCase
{
    public function testGetAvailabilitiesDatabase()
    {
        $fixedTime = Carbon::create(2022, 9, 7, 12);
        Carbon::setTestNow($fixedTime);
        $doctor = Doctor::factory(['agenda' => Doctor::AGENDA_DATABASE])->create();

        Availability::factory(['doctor_id' => $doctor->id, 'start' => Carbon::now()->addDay()])->create();
        Availability::factory(['doctor_id' => $doctor->id, 'start' => Carbon::now()->addDays(2)])->create();
        $response = $this->getJson(route('availabilities.get', ['doctor' => $doctor->id]));

        $response->assertSuccessful();
        $response->assertJsonCount(2, 'data');
        $response->assertJsonStructure([
            'data' => [
                [
                    'start',
                ]
            ],
        ]);
        $response->assertJsonFragment(['start' => Carbon::now()->addDay()]);
        $response->assertJsonFragment(['start' => Carbon::now()->addDays(2)]);
    }

    public function testGetAvailabilitiesDoctolib()
    {
        $fixedTime = Carbon::create(2022, 9, 7, 12);
        Carbon::setTestNow($fixedTime);
        $mockedResponse = [
            new AvailabilityDto(Carbon::now()->addDay()),
            new AvailabilityDto(Carbon::now()->addHours(2)),
        ];
        $this->partialMock(DoctolibService::class, function (MockInterface $mock) use ($mockedResponse) {
            $mock->shouldReceive('getAvailabilities')->andReturn($mockedResponse);
        });
        $doctor = Doctor::factory(['agenda' => Doctor::AGENDA_DOCTOLIB, 'external_agenda_id' => '1234'])->create();
        $response = $this->getJson(route('availabilities.get', ['doctor' => $doctor->id]));

        $response->assertSuccessful();
        $response->assertJsonCount(2, 'data');
        $response->assertJsonStructure([
            'data' => [
                [
                    'start',
                ]
            ],
        ]);
        $response->assertJsonFragment(['start' => Carbon::now()->addDay()]);
        $response->assertJsonFragment(['start' => Carbon::now()->addHours(2)]);
    }

    public function testGetAvailabilitiesClicrdv()
    {
        $fixedTime = Carbon::create(2022, 9, 7, 12);
        Carbon::setTestNow($fixedTime);
        $mockedResponse = [
            new AvailabilityDto(Carbon::now()->addDay()),
            new AvailabilityDto(Carbon::now()->addHours(2)),
        ];
        $this->partialMock(ClicRDVService::class, function (MockInterface $mock) use ($mockedResponse) {
            $mock->shouldReceive('getAvailabilities')->andReturn($mockedResponse);
        });
        $doctor = Doctor::factory(['agenda' => Doctor::AGENDA_CLICRDV, 'external_agenda_id' => '1234'])->create();
        $response = $this->getJson(route('availabilities.get', ['doctor' => $doctor->id]));

        $response->assertSuccessful();
        $response->assertJsonCount(2, 'data');
        $response->assertJsonStructure([
            'data' => [
                [
                    'start',
                ]
            ],
        ]);
        $response->assertJsonFragment(['start' => Carbon::now()->addDay()]);
        $response->assertJsonFragment(['start' => Carbon::now()->addHours(2)]);
    }

    public function testAvailabilitiesWithSameDateWithBookingNotShown()
    {
        $fixedTime = Carbon::create(2022, 9, 7, 12);
        Carbon::setTestNow($fixedTime);
        $doctor = Doctor::factory(['agenda' => Doctor::AGENDA_DATABASE])->create();

        Booking::factory(['doctor_id' => $doctor->id, 'date' => Carbon::now()->addDay()])->create();
        Availability::factory(['doctor_id' => $doctor->id, 'start' => Carbon::now()->addDay()])->create();
        Availability::factory(['doctor_id' => $doctor->id, 'start' => Carbon::now()->addDays(2)])->create();
        $response = $this->getJson(route('availabilities.get', ['doctor' => $doctor->id]));

        $response->assertSuccessful();
        $response->assertJsonCount(1, 'data');
        $response->assertJsonStructure([
            'data' => [
                [
                    'start',
                ]
            ],
        ]);
        $response->assertJsonFragment(['start' => Carbon::now()->addDays(2)]);
    }

    public function testAnotherDoctorBookingsDontAffect()
    {
        $fixedTime = Carbon::create(2022, 9, 7, 12);
        Carbon::setTestNow($fixedTime);
        $doctor = Doctor::factory(['agenda' => Doctor::AGENDA_DATABASE])->create();

        Booking::factory(['date' => Carbon::now()->addDay()])->create();
        Availability::factory(['doctor_id' => $doctor->id, 'start' => Carbon::now()->addDay()])->create();
        Availability::factory(['doctor_id' => $doctor->id, 'start' => Carbon::now()->addDays(2)])->create();
        $response = $this->getJson(route('availabilities.get', ['doctor' => $doctor->id]));

        $response->assertSuccessful();
        $response->assertJsonCount(2, 'data');
        $response->assertJsonStructure([
            'data' => [
                [
                    'start',
                ]
            ],
        ]);
        $response->assertJsonFragment(['start' => Carbon::now()->addDay()]);
        $response->assertJsonFragment(['start' => Carbon::now()->addDays(2)]);
    }
}
