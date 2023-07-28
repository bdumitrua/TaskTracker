<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegistrationRequest;
use App\Services\AuthService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ApiAuthController extends Controller
{
    private $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function user()
    {
        return $this->handleServiceCall(function () {
            return $this->authService->user();
        });
    }

    public function register(RegistrationRequest $request)
    {
        return $this->handleServiceCall(function () use ($request) {
            return $this->authService->register($request);
        });
    }

    public function login(LoginRequest $request)
    {
        return $this->handleServiceCall(function () use ($request) {
            return $this->authService->login($request);
        });
    }

    public function logout()
    {
        return $this->handleServiceCall(function () {
            return $this->authService->logout();
        });
    }
    public function refresh()
    {
        return $this->handleServiceCall(function () {
            return $this->authService->refresh();
        });
    }
}
