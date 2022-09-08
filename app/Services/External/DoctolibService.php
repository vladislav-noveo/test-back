<?php

namespace App\Services\External;

use App\DTO\AvailabilityDto;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Exception\ClientException;

class DoctolibService implements ExternalServiceInterface
{
    private string $doctolibURL;

    public function __construct()
    {
        $this->doctolibURL = env('DOCTOLIB_URL');
    }

    public function getAvailabilities(string $doctorId): array
    {
        try {
            $url = str_replace(':doctorId', $doctorId, $this->doctolibURL);
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
