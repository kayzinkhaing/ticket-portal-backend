<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Services\PasswordResetService;

class PasswordResetLinkController extends Controller
{
    protected $passwordResetService;

    public function __construct(PasswordResetService $passwordResetService)
    {
        $this->passwordResetService = $passwordResetService;
    }

    /**
     * Show the password reset request form.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle sending a password reset OTP.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
        ]);

        try {
            $this->passwordResetService->generateOtp($validated['email']);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }

        $maskedEmail = $this->passwordResetService->maskEmail($validated['email']);

        return redirect()->route('password.reset', [
            'email'  => $validated['email'],
            'masked' => $maskedEmail,
        ]);
    }
}
