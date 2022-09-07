<?php

namespace App\Services\External;

interface ExternalServiceInterface
{
    public function getAvailabilities(string $doctorId): array;
}
