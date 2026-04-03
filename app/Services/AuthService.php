<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use App\Contracts\UserInterface;
use App\Http\Resources\AuthResource;
use App\Models\ClientProfile;

class AuthService
{
    public function __construct(protected UserInterface $users) {}

    /**
     * API Login for frontend UI
     */
    public function apiLogin(array $credentials, Request $request)
    {
        // 🔐 Optional reCAPTCHA
        $recaptchaToken = $credentials['recaptcha_token'] ?? null;

        if ($recaptchaToken) {
            $recaptcha = Http::asForm()->post(
                'https://www.google.com/recaptcha/api/siteverify',
                [
                    'secret'   => config('services.recaptcha.secret_key'),
                    'response' => $recaptchaToken,
                ]
            )->json();

            if (!($recaptcha['success'] ?? false) || ($recaptcha['score'] ?? 0) < 0.5) {
                return response()->json([
                    'success' => false,
                    'message' => 'Security verification failed'
                ], 422);
            }
        }

        // 🔑 Validate credentials
        $user = $this->users->getByEmail($credentials['email']);

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials'
            ], 401);
        }

        // 🔒 Optional 2FA
        if ($user->two_factor_enabled) {
            session(['2fa:user:id' => $user->id]);

            return response()->json([
                'success' => false,
                'two_factor' => true,
                'message' => '2FA required'
            ]);
        }

        // 🎟 Create token
        $token = $user->createToken('API Token')->accessToken;

        // ✅ GET ROLE
        // If using Spatie:
        $role = $user->roles()->first()?->name;

        // If NOT using Spatie, use this instead:
        // $role = $user->role;

        // ✅ GET ORGANIZATION (ONLY FOR CLIENT)
        $organization = null;

        if (strtolower($role) === 'client') {
            $organization = ClientProfile::with('organization')
                ->where('user_id', $user->id)
                ->first()?->organization;
        }

        // ✅ RETURN USING AuthResource (same as verify-email)
        return new AuthResource(
            $user,
            $role,
            $token,
            $organization
        );
    }

    /**
     * API Logout
     */
    // public function apiLogout(Request $request)
    // {
    //     $user = $request->user();

    //     if ($user && $request->user()->token()) {
    //         // Revoke the current access token
    //         $request->user()->token()->revoke(); // Or ->delete()
    //     }

    //     // No need to call Auth::logout() for API tokens

    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Logged out successfully'
    //     ]);
    // }

    public function apiLogout(Request $request)
    {
        $user = $request->user();

        if ($user) {
            // Revoke all tokens for this user (optional: or only revoke current token)
            $user->tokens()->delete(); // Deletes all tokens
            // Or only revoke current token if you want:
            // $request->user()->currentAccessToken()->delete();
        }

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully'
        ]);
    }
}
