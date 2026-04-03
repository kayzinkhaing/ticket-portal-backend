<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterUserRequest;
use App\Services\RegistrationService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class RegisteredUserController extends Controller
{
    protected RegistrationService $registrationService;
    protected int $defaultRoleId;

    public function __construct(RegistrationService $registrationService)
    {
        $this->registrationService = $registrationService;
        $this->defaultRoleId = 5;
    }

    /**
     * ✅ Register user (API)
     */
    public function register(RegisterUserRequest $request): JsonResponse
    {
        $validated = $request->validated();

        // hash password
        $validated['password'] = bcrypt($validated['password']);

        // store temp user + send OTP
        $this->registrationService->storeTempUser(
            $validated,
            $request->file('image')
        );

        return response()->json([
            'success' => true,
            'message' => 'OTP sent to email',
            'email' => $validated['email']
        ], 200);
    }

    /**
     * ✅ Resend OTP (API)
     */
    public function resendCode(Request $request): JsonResponse
    {
        $request->validate([
            'email' => ['required', 'email']
        ]);

        $code = $this->registrationService->resendCode($request->email);

        if (!$code) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot resend OTP now. Try later.'
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'OTP resent successfully'
        ], 200);
    }
}
