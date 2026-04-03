<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\AuthService;
use Illuminate\Http\Request;

class AuthenticatedSessionController extends Controller
{
    public function __construct(protected AuthService $authService) {}

    // API login
    public function apiLogin(LoginRequest $request)
    {
        $credentials = $request->validated();
        return $this->authService->apiLogin($credentials, $request);
    }

    // API logout
    public function apiLogout(Request $request)
    {
        return $this->authService->apiLogout($request);
    }
}
