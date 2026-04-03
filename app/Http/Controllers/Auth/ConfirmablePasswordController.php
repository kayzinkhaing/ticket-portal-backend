<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use App\Models\User;
use Illuminate\Support\Facades\Hash;



class ConfirmablePasswordController
{
    /**
     * Show the confirm password view.
     */
    public function showConfirm(Request $request): View
    {
        $email = $request->email;

        // dd($email);
        return view('auth.confirm-password', compact('email'));
    }

    /**
     * Confirm the user's password.
     */
    public function passwordchange(Request $request): RedirectResponse
    {
        // $validated = $request->validate([
        //     'email' => 'required|email',
        //     'password' => ['required', 'confirmed'],
        // ]);

        // Find user by email
        $user = User::where('email', $request->email)->first();
        // dd($user);
        // if (!$user) {
        //     return back()->withErrors(['email' => 'Email not found.']);
        // }

        // Update password
        $user->password = Hash::make($request->password);
        $user->save();
        // dd('hi');

        // Redirect to login
        return redirect()->route('login')->with('success', 'Password updated successfully.');
    }
}
