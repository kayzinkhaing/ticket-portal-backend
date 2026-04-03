<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\{
    AuthenticatedSessionController,
    ConfirmablePasswordController,
    EmailVerificationNotificationController,
    EmailVerificationPromptController,
    NewPasswordController,
    PasswordController,
    PasswordResetLinkController,
    RegisteredUserController,
    VerifyEmailController,
    OtpVerificationController,
    OAuthController
};
use App\Http\Controllers\TwoFactorController;

/*
|--------------------------------------------------------------------------
| GUEST ROUTES
|--------------------------------------------------------------------------
| Routes that only guests (not logged-in users) can access
*/
Route::middleware('guest')->group(function () {

    // 🔑 Register / Login
    Route::get('register', [RegisteredUserController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'register']);

    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    // 🔐 Forgot Password
    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');

    // 🔑 OTP Email Verification
    Route::get('verify-email-form', [RegisteredUserController::class, 'showVerificationForm'])->name('verify.email.form');
    Route::post('verify-email', [OtpVerificationController::class, 'verify'])->name('verify.email.submit');
    Route::post('resend-code', [RegisteredUserController::class, 'resendCode'])->name('resend.code');

    // 🔑 Password reset with OTP
    Route::get('verify-password-code-form', [NewPasswordController::class, 'showForget'])->name('password.reset');
    Route::post('reset-password', [NewPasswordController::class, 'verifyForgotcode'])->name('password.code.submit');
    Route::post('resend-code-password', [NewPasswordController::class, 'resendCodePassword'])->name('resend.code.password');
    Route::get('change-password-form', [ConfirmablePasswordController::class, 'showConfirm'])->name('change.password.form');
    Route::post('change-password', [ConfirmablePasswordController::class, 'passwordchange'])->name('password.change');

    // 🔑 Social Login
    Route::get('auth/google', [OAuthController::class, 'redirectToGoogle'])->name('google');
    // Route::get('auth/google/callback', [OAuthController::class, 'handleGoogleCallback'])->name('google.callback');

    Route::get('auth/github', [OAuthController::class, 'redirectToGithub'])->name('github');
    // Route::get('auth/github/callback', [OAuthController::class, 'handleGithubCallback'])->name('github.callback');
});


/*
|--------------------------------------------------------------------------
| AUTHENTICATED ROUTES
|--------------------------------------------------------------------------
| Routes that only logged-in users can access
*/
Route::middleware('auth')->group(function () {

    // 🔐 Email verification
    Route::get('verify-email', EmailVerificationPromptController::class)->name('verification.notice');
    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');
    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    // 🔑 Confirm password
    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])->name('password.confirm');
    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);
    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    // 🔐 Two-Factor Authentication
    Route::get('auth/verify', [TwoFactorController::class, 'showVerifyForm'])->name('twofactor.verify');
    Route::post('auth/verify', [TwoFactorController::class, 'verifyCode'])->name('twofactor.verify.post');
    Route::post('two-factor/enable', [TwoFactorController::class, 'enable'])->name('twofactor.enable');
    Route::post('two-factor/disable', [TwoFactorController::class, 'disable'])->name('twofactor.disable');

    // 🔓 Logout
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    // 🔓 Optional direct logout
    Route::get('logout-direct', function () {
        Auth::logout();
        return redirect()->route('login');
    })->name('logout.direct');
});
