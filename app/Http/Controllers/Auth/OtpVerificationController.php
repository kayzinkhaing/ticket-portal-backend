<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\AuthResource;
use App\Services\RegistrationService;
use Illuminate\Http\Request;

class OtpVerificationController extends Controller
{
    public function verify(Request $request, RegistrationService $service)
    {
        $request->validate([
            'email' => 'required|email',
            'otp'   => 'required|string|size:6',
        ]);

        $data = $service->verifyCode($request->email, $request->otp);

        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired OTP code',
            ], 422);
        }

        $user = $data['user'];

        // Mark email verified
        $user->email_verified_at = now();
        $user->save();

        // Create token
        $token = $user->createToken('API Token')->accessToken;

        // ✅ Get role
        $role = $data['role']; // OR $user->getRoleNames()->first();

        // ✅ Get organization (only if client)
        $organization = null;

        if ($role === 'client') {
            $organization = \App\Models\ClientProfile::with('organization')
                ->where('user_id', $user->id)
                ->first()?->organization;
        }

        return new AuthResource(
            $user,
            $role,
            $token,
            $organization
        );
    }
}
