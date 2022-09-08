<?php

namespace Tests\Feature\Api\Auth;

use App\Models\User;
use Tests\Feature\Api\ApiTestCase;
use Illuminate\Support\Facades\Hash;

class GetTokenTest extends ApiTestCase
{
    public function testUserCanGetToken()
    {
        $user = User::factory([
            'email' => 'test@email.com',
            'password' => Hash::make('testPass'),
        ])->create();

        $response = $this->post(route('token.get'), [
            'email' => $user->email,
            'password' => 'testPass',
            'device_name' => 'test',
        ]);

        $response->assertSuccessful();
        $token = $response->getContent();

        $response = $this->getJson(route('bookings.get'), ['Authorization' => 'Bearer ' . $token]);
        $response->assertSuccessful();
    }

    /**
     * @dataProvider invalidCredentialsProvider
     */
    public function testInvalidCredentials(string $email, string $password)
    {
        $user = User::factory([
            'email' => 'test@email.com',
            'password' => Hash::make('testPass'),
        ])->create();

        $response = $this->post(route('token.get'), [
            'email' => $email,
            'password' => $password,
            'device_name' => 'test',
        ]);

        $response->assertUnauthorized();
    }

    public function testDeviceNameIsRequired()
    {
        $user = User::factory([
            'email' => 'test@email.com',
            'password' => Hash::make('testPass'),
        ])->create();

        $response = $this->postJson(route('token.get'), [
            'email' => $user->email,
            'password' => 'testPass',
        ]);

        $response->assertJsonValidationErrors(['device_name' => 'The device name field is required']);
    }

    public function invalidCredentialsProvider()
    {
        return [
            [
                'email' => 'wrong@email.com',
                'password' => 'testPass',
            ],
            [
                'email' => 'test@email.com',
                'password' => 'wrongPass',
            ]
        ];
    }
}
