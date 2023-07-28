<?php

namespace App\Services;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegistrationRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class AuthService
{
    public function user()
    {
        return Auth::user();
    }

    public function register(RegistrationRequest $request)
    {
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        if (!$token = auth()->attempt($credentials)) {
            throw new HttpException(Response::HTTP_UNAUTHORIZED, 'Invalid credentials');
        }

        return [
            'user' => Auth::user(),
            'access_token' => $token
        ];
    }

    public function logout()
    {
        Auth::logout();
    }

    public function refresh()
    {
        return $this->respondWithToken(Auth::refresh());
    }

    protected function respondWithToken($token)
    {
        return [
            'token_type' => 'bearer',
            'access_token' => $token,
            'expires_in' => Auth::factory()->getTTL() * 60
        ];
    }
}
