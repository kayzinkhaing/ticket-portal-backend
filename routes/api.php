<?php

use App\Http\Controllers\Api\{ UserController, OrganizationController, ClientProfileController, RoleController,  TicketStatusController, TicketPriorityController, TicketController, CommentController, TicketStatusHistoryController,  CommonTableController };
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Auth\{ RegisteredUserController, AuthenticatedSessionController, OtpVerificationController, PasswordResetLinkController, NewPasswordController, ConfirmablePasswordController, PasswordController, EmailVerificationNotificationController, EmailVerificationPromptController, VerifyEmailController, OAuthController };
use App\Http\Controllers\TwoFactorController;
use Illuminate\Support\Facades\Route;


// --------------------------------------
// 🔓 GUEST ROUTES (NO TOKEN)
// --------------------------------------
Route::middleware('guest')->prefix('auth')->group(function () {

    Route::post('register', [RegisteredUserController::class, 'register']);

    // ✅ FIXED (only one login)
    Route::post('login', [AuthenticatedSessionController::class, 'apiLogin']);

    Route::post('verify-email', [OtpVerificationController::class, 'verify']);
    Route::post('resend-code', [RegisteredUserController::class, 'resendCode']);

    Route::post('forgot-password', [PasswordResetLinkController::class, 'store']);
    Route::post('reset-password', [NewPasswordController::class, 'verifyForgotcode']);
    Route::post('resend-code-password', [NewPasswordController::class, 'resendCodePassword']);

    Route::get('google', [OAuthController::class, 'redirectToGoogle']);
    Route::get('github', [OAuthController::class, 'redirectToGithub']);
});


// --------------------------------------
// 🔐 AUTH ROUTES (TOKEN REQUIRED)
// --------------------------------------
Route::middleware(['auth:api', 'acl'])->prefix('v1')->group(function () {


    Route::get('verify-email', EmailVerificationPromptController::class);

    Route::get('verify-email/{id}/{hash}', [VerifyEmailController::class])
        ->middleware(['signed', 'throttle:6,1']);

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1');

    Route::post('confirm-password', [ConfirmablePasswordController::class, 'passwordConfirm']);
    Route::post('change-password', [ConfirmablePasswordController::class, 'passwordchange']);
    Route::put('password', [PasswordController::class, 'update']);

    Route::post('two-factor/enable', [TwoFactorController::class, 'enable']);
    Route::post('two-factor/disable', [TwoFactorController::class, 'disable']);
    Route::post('two-factor/verify', [TwoFactorController::class, 'verifyCode']);
});


Route::get('/v1/organizations', [OrganizationController::class, 'index']);
Route::get('/v1/organizations/{id}', [OrganizationController::class, 'show']);


// --------------------------------------
// 📦 PROTECTED API MODULE ROUTES
// --------------------------------------
Route::middleware('auth:api')->prefix('v1')->group(function () {
    Route::post('logout', [AuthenticatedSessionController::class, 'apiLogout']);

    Route::get('tickets/status', [TicketController::class, 'byStatus']);
    Route::get('tickets/organization', [TicketController::class, 'organizationTickets']);
    Route::get('tickets/status-counts', [TicketController::class, 'statusCounts']);
    Route::get('agents', [UserController::class, 'agents']);
    Route::get('/tickets/advanced-search', [TicketController::class, 'advancedSearch']);


    Route::apiResource('users', UserController::class);
    // Route::apiResource('organizations', OrganizationController::class);
    Route::apiResource('client-profiles', ClientProfileController::class);
    Route::apiResource('roles', RoleController::class);
    // Route::apiResource('role-users', RoleUserController::class);

    Route::apiResource('ticket-statuses', TicketStatusController::class);
    Route::apiResource('ticket-priorities', TicketPriorityController::class);
    Route::apiResource('tickets', TicketController::class);
    Route::apiResource('comments', CommentController::class);
    Route::apiResource('ticket-status-histories', TicketStatusHistoryController::class);
    Route::get('dashboard', [DashboardController::class, 'index']);

    Route::apiResource('common-tables', CommonTableController::class);
});
