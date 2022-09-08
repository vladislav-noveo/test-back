<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Api\Auth\LoginRequest;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class AuthService
{
    public function generateUserToken(LoginRequest $request): string
    {
        $user = User::where('email', $request->email)->first();

        // dd($user);
        if (!$user || !Hash::check($request->password, $user->password)) {
            throw new UnauthorizedHttpException('Wronf credentials');
        }

        return $user->createToken($request->device_name)->plainTextToken;
    }
}
