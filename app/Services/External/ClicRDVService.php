<?php

namespace App\Services\External;

use App\DTO\AvailabilityDto;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Exception\ClientException;

class ClicRDVService implements ExternalServiceInterface
{
    private string $clicRDV;

    public function __construct()
    {
        $this->clicRDV = env('CLICRDV_URL');
    }

    public function getAvailabilities(string $doctorId): array
    {
        try {
            $url = str_replace(':doctorId', $doctorId, $this->clicRDV);
            $response = Http::get($url);

            return array_map(
                fn($item) => new AvailabilityDto($item['start']),
                $response->json(),
            );
        } catch (ClientException $e) {
            return [];
        }
    }
}
