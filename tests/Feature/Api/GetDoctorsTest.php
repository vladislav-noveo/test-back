<?php

namespace Tests\Feature\Api;

use App\Models\Doctor;
use Tests\Feature\Api\ApiTestCase;

class GetDoctorsTest extends ApiTestCase
{
    public function testGetDoctors()
    {
        Doctor::factory(['name' => 'Test name'])->count(5)->create();
        $response = $this->getJson(route('doctors.get'));

        $response->assertSuccessful();

        $response->assertJsonCount(5, 'data');
        $response->assertJsonStructure([
            'data' => [
                [
                    'id',
                    'name',
                ],
            ],
        ]);
    }
}
