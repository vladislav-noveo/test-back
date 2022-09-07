<?php

namespace Tests\Unit\ExternalServices;

use Tests\TestCase;
use App\Models\Doctor;
use App\DTO\AvailabilityDto;
use App\Services\External\ClicRDVService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;

class ClicRDVServiceTest extends TestCase
{
    public function testReturnsCorrectFormat()
    {
        $fixedTime = Carbon::create(2022, 9, 7, 12);
        Carbon::setTestNow($fixedTime);

        $doctor = Doctor::factory(['external_agenda_id' => '1234'])->create();

        $fakedData = [
            [
                'start' => Carbon::now()->addDay(),
                'end' => Carbon::now()->addDay()->addHours(2),
            ],
            [
                'start' => Carbon::now()->addHours(2),
                'end' => Carbon::now()->addHours(4),
            ],
        ];
        Http::fake([
            'https://tech-test.joovence.dev/api/clic-rdv/availabilities?proId=1234' => Http::response(json_encode($fakedData)),
        ]);

        $service = app(ClicRDVService::class);
        $availabilities = $service->getAvailabilities($doctor->external_agenda_id);

        $expected = [
            new AvailabilityDto(Carbon::now()->addDay()->toISOString()),
            new AvailabilityDto(Carbon::now()->addHours(2)->toISOString()),
        ];

        $this->assertEqualsCanonicalizing($expected, $availabilities);
    }
}
