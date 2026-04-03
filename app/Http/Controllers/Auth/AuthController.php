<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Services\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    // public function __construct(AuthService $service)
    // {
    //     parent::__construct($service);
    // }

    public function register(RegisterRequest $request)
    {
        $user = $this->service->register($request->validated());

        return response()->json([
            'message' => 'User successfully registered',
            'user' => $user,
        ], 201);
    }

    public function login(LoginRequest $request)
    {
        $tokens = $this->service->login($request->validated());

        return response()->json($tokens);
    }

    public function refresh(Request $request)
    {
        $request->validate([
            'refresh_token' => 'required|string',
        ]);

        return response()->json(
            $this->service->refreshToken($request->refresh_token)
        );
    }
}
