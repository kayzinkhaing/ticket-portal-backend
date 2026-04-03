<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PragmaRX\Google2FAQRCode\Google2FA;
use App\Models\User;

class TwoFactorController extends Controller
{
   public function index()
{
    $user = Auth::user();
    $google2fa = new \PragmaRX\Google2FAQRCode\Google2FA();

    // 1. Always ensure a secret exists
    if (!$user->google2fa_secret) {
        $user->google2fa_secret = $google2fa->generateSecretKey();
        $user->save();
    }

    // 2. Initialize variable to prevent "Undefined variable" error
    $qrCode = '';

    // 3. Only generate QR if 2FA is not yet enabled
    if (!$user->two_factor_enabled) {
        $qrCode = $google2fa->getQRCodeInline(
            config('app.name'),
            $user->email,
            $user->google2fa_secret
        );
    }

    // Pass it to the index view
    return view('profile.index', compact('user', 'qrCode'));
}

    public function enable(Request $request)
    {
        $user = Auth::user();
        $google2fa = new Google2FA();

        $valid = $google2fa->verifyKey($user->google2fa_secret, $request->code);

        if ($valid) {
            $user->two_factor_enabled = true;
            $user->save();
            return back()->with('success', 'Two-factor authentication enabled.');
        }

        return back()->with('error', 'Invalid verification code.');
    }

    public function disable(Request $request)
    {
        $user = Auth::user();
        $user->two_factor_enabled = false;
        $user->save();

        return back()->with('success', 'Two-factor authentication disabled.');
    }

    public function showVerifyForm()
    {
        return view('auth.verify'); // create this Blade
    }

    public function verifyCode(Request $request)
    {
        $request->validate([
            'code' => 'required|digits:6'
        ]);

        $user = User::find(session('2fa:user:id'));

        if (!$user) {
            return redirect()->route('login')->withErrors(['code' => 'Session expired, please login again.']);
        }

        $google2fa = app('pragmarx.google2fa');

        // Validate entered code
        $isValid = $google2fa->verifyKey($user->google2fa_secret, $request->code);

        if (!$isValid) {
            return back()->withErrors(['code' => 'Invalid verification code.']); // <-- ERROR MESSAGE
        }

        // If valid → complete login
        session()->forget('2fa:user:id');
        Auth::login($user);

        return redirect()->route('home');
    }


}
