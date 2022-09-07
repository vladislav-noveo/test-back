<?php

namespace App\Services;

use App\Models\Doctor;
use App\Models\Booking;
use App\DTO\AvailabilityDto;
use App\Models\Availability;
use Illuminate\Support\Carbon;
use JetBrains\PhpStorm\ArrayShape;
use App\Services\External\ClicRDVService;
use App\Services\External\DoctolibService;

class AvailabilityService
{
    public function __construct(private DoctolibService $doctolibService, private ClicRDVService $clicRDVService)
    {}

    public function getDoctorAvailabilities(Doctor $doctor)
    {
        $availabilities = match($doctor->agenda) {
            Doctor::AGENDA_DATABASE => $this->getDatabaseAvailabilities($doctor),
            Doctor::AGENDA_DOCTOLIB => $this->getDoctolibAvailabilities($doctor),
            Doctor::AGENDA_CLICRDV => $this->getClicrdvAvailabilities($doctor),
        };

        return $this->filterAvailabilities($availabilities, $doctor);
    }

    #[ArrayShape(AvailabilityDto::class)]
    private function getDatabaseAvailabilities(Doctor $doctor): array
    {
        return Availability::where('doctor_id', $doctor->id)->get()
            ->map(fn(Availability $item) => new AvailabilityDto($item->start))->all();
    }

    #[ArrayShape(AvailabilityDto::class)]
    private function getDoctolibAvailabilities(Doctor $doctor): array
    {
        return $this->doctolibService->getAvailabilities($doctor->external_agenda_id);
    }

    #[ArrayShape(AvailabilityDto::class)]
    private function getClicrdvAvailabilities(Doctor $doctor): array
    {
        return $this->clicRDVService->getAvailabilities($doctor->external_agenda_id);
    }

    private function filterAvailabilities(array $availabilities, Doctor $doctor)
    {
        $bookingDates = Booking::where('doctor_id', $doctor->id)->where('date', '>' ,Carbon::now())->pluck('date');

        return array_filter($availabilities, fn (AvailabilityDto $item) => !$bookingDates->contains($item->start));
    }
}
