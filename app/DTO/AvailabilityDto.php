<?php

namespace App\DTO;

use Illuminate\Support\Carbon;

final class AvailabilityDto
{
    public function __construct(public string|Carbon $start)
    {}
}
