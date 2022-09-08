<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Services\AuthService;

class AuthController extends Controller
{
    public function store(LoginRequest $request, AuthService $authService)
    {
        return $authService->generateUserToken($request);
    }

}
