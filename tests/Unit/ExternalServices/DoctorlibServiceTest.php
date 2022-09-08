<?php

namespace Tests\Unit\ExternalServices;

use Tests\TestCase;
use App\Models\Doctor;
use App\DTO\AvailabilityDto;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use App\Services\External\DoctolibService;

class DoctorlibServiceTest extends TestCase
{
    public function testReturnsCorrectFormat()
    {
        $fixedTime = Carbon::create(2022, 9, 7, 12);
        Carbon::setTestNow($fixedTime);

        $doctor = Doctor::factory(['external_agenda_id' => '1234'])->create();

        $fakedData = [
            [
                'start' => Carbon::now()->addDay(),
                'doctorId' => '1234'
            ],
            [
                'start' => Carbon::now()->addHours(2),
                'doctorId' => '1234'
            ],
        ];
        Http::fake([
            'https://tech-test.joovence.dev/api/doctolib/1234/availabilities' => Http::response(json_encode($fakedData)),
        ]);

        $service = app(DoctolibService::class);
        $availabilities = $service->getAvailabilities($doctor->external_agenda_id);

        $expected = [
            new AvailabilityDto(Carbon::now()->addDay()->toISOString()),
            new AvailabilityDto(Carbon::now()->addHours(2)->toISOString()),
        ];

        $this->assertEqualsCanonicalizing($expected, $availabilities);
    }
}
