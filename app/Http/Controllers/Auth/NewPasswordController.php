<?php

namespace App\Http\Controllers\Auth;

use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\Services\PasswordResetService;
use App\Http\Requests\VerifyOtpRequest;

class NewPasswordController extends Controller
{
    protected $passwordResetService;

    public function __construct(PasswordResetService $passwordResetService)
    {
        $this->passwordResetService = $passwordResetService;
    }

    /**
     * Display the password reset view.
     */
    public function showForget(Request $request): View
    {
        $email = $request->email;
        $masked = $request->masked;

        return view('auth.reset-password', compact('email', 'masked'));
    }

    /**
     * Handle OTP verification.
     */
    public function verifyForgotcode(VerifyOtpRequest $request): RedirectResponse
    {
        $verificationCode = implode('', $request->otp);

        if (!$this->passwordResetService->verifyCode($request->email, $verificationCode)) {
            return back()->with('error', 'Invalid verification code. Please try again.');
        }

        return redirect()->route('change.password.form', [
            'email' => $request->email,
        ]);
    }

    /**
     * Resend OTP code.
     */
    public function resendCodePassword(Request $request): RedirectResponse
    {
        $request->validate(['email' => 'required|email']);

        try {
            $this->passwordResetService->resendCode($request->email);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['email' => $e->getMessage()]);
        }

        return back()->with('success', 'A new OTP has been sent to your email.');
    }
}
