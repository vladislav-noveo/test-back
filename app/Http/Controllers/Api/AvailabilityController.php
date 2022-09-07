<?php

namespace App\Http\Controllers\Api;

use App\Models\Doctor;
use App\Http\Controllers\Controller;
use App\Services\AvailabilityService;
use App\Http\Resources\AvailabilityDtoResource;

class AvailabilityController extends Controller
{
    public function getForDoctor(Doctor $doctor, AvailabilityService $service)
    {
        $availabilities = $service->getDoctorAvailabilities($doctor);

        return AvailabilityDtoResource::collection($availabilities);
    }
}
