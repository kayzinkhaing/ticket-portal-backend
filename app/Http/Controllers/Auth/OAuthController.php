<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OAuthController extends Controller
{
    // -------------------
    // Google OAuth
    // -------------------
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        $providerUser = Socialite::driver('google')->user();
        return $this->loginOrRegister($providerUser, 'google');
    }

    // -------------------
    // GitHub OAuth
    // -------------------
    public function redirectToGithub()
    {
        return Socialite::driver('github')->redirect();
    }

    public function handleGithubCallback()
    {
        $providerUser = Socialite::driver('github')->user();
        return $this->loginOrRegister($providerUser, 'github');
    }

    // -------------------
    // Login or register user
    // -------------------
    private function loginOrRegister($providerUser, $provider)
    {
        // Check if user exists by email
        $user = User::where('email', $providerUser->email)->first();

        if ($user) {
            // Existing user → login
            Auth::login($user);
            $action = 'login';

            // Update provider info if needed
            $user->update([
                'provider' => $provider,
                'provider_id' => $providerUser->id,
            ]);
        } else {
            // New user → register
            $user = User::create([
                'name' => $providerUser->name ?? $providerUser->nickname,
                'email' => $providerUser->email,
                'password' => null, // No password needed
                // 'provider' => $provider,
                // 'provider_id' => $providerUser->id,
                'email_verified_at' => now(),
                'is_verified' => true,
            ]);

            Auth::login($user);
            $action = 'register';

            $defaultRoleId = 5; // Change this to your default role
            $user->roles()->attach($defaultRoleId);
        }
        // ------------------------------

        // -------------------------------
        // Store login type and action in session
        // -------------------------------
        session([
            'login_method' => $provider, // google, github
            'login_action' => $action,   // login or register
        ]);

        // -------------------------------
        // Store token in oauth_access_tokens table (optional)
        // -------------------------------
        // $clientId = DB::table('oauth_clients')
        //     ->where('name', 'like', '%' . $provider . '%')
        //     ->value('id') ?? 1; // default client id

        // $lastId = DB::table('oauth_access_tokens')->max('id') ?? 0;
        // $newId = $lastId + 1;

        // DB::table('oauth_access_tokens')->insert(
        //     [
        //         'id' => $newId,
        //         'user_id' => $user->id,
        //         'client_id' => $clientId,
        //         'name' => $provider,
        //         'revoked' => 0,
        //         'scopes' => null,
        //         'expires_at' => Carbon::now()->addHours(1),
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ]
        // );
        // 2FA check
        // -------------------------------
        if ($user->two_factor_enabled) {
            // Temporarily store user id in session for 2FA verification
            session(['2fa:user:id' => $user->id]);
            return redirect()->route('twofactor.verify');
        }
        // -------------------------------
        // Redirect to home/dashboard
        // -------------------------------
        return $this->redirectByRole($user);
    }

    protected function redirectByRole(User $user)
    {
        $role = strtolower(
            $user->roles()->pluck('name')->first() ?? 'student'
        );

        // frontend users
        if ($role === 'student' || $role === 'guest') {
            return redirect()->route('home');
        }

        // backend users (admin, staff, instructor, editor)
        return redirect()->route('dashboard', [
            'role' => $role
        ]);
    }
}