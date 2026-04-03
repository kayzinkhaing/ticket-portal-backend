<?php

namespace App\Services;

use Illuminate\Support\Facades\Redis;
use App\Events\UserRegistered;
use App\Models\User;

class PasswordResetService
{
    /**
     * Generate and store OTP for an email, and send email.
     *
     * @param string $email
     * @param int $ttlSeconds
     * @return string
     *
     * @throws \Exception
     */
    public function generateOtp(string $email, int $ttlSeconds = 600): string
    {
        $user = User::where('email', $email)->first();
        if (!$user) {
            throw new \Exception('This email is not registered.');
        }

        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        $data = [
            'email' => $email,
            'code' => $otp,
        ];

        Redis::setex("register:{$email}", $ttlSeconds, json_encode($data));

        event(new UserRegistered($data['email'], $data['code']));

        return $otp;
    }

    /**
     * Verify the OTP code for a given email.
     *
     * @param string $email
     * @param string $otp
     * @return bool
     */
    public function verifyCode(string $email, string $otp): bool
    {
        $stored = Redis::get("register:{$email}");
        if (!$stored) {
            return false;
        }

        $data = json_decode($stored, true);

        return $data['code'] === $otp;
    }

    /**
     * Resend a new OTP code to the user's email.
     *
     * @param string $email
     * @return void
     *
     * @throws \Exception
     */
    public function resendCode(string $email): void
    {
        $stored = Redis::get("register:{$email}");
        if (!$stored) {
            throw new \Exception('Session expired.');
        }

        $data = json_decode($stored, true);

        $newOtp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $data['code'] = $newOtp;

        Redis::setex("register:{$email}", 180, json_encode($data));

        event(new UserRegistered($data['email'], $data['code']));
    }

    /**
     * Check if session exists in Redis
     *
     * @param string $email
     * @return bool
     */
    public function sessionExists(string $email): bool
    {
        return Redis::exists("register:{$email}");
    }

    /**
     * Mask an email for display
     *
     * @param string $email
     * @return string
     */
    public function maskEmail(string $email): string
    {
        [$name, $domain] = explode("@", $email);

        if (strlen($name) <= 4) {
            return substr($name, 0, 1) . str_repeat("*", strlen($name) - 2) . substr($name, -1) . "@" . $domain;
        }

        $first = substr($name, 0, 2);
        $last = substr($name, -2);
        $masked = $first . str_repeat("*", strlen($name) - 4) . $last;

        return $masked . "@" . $domain;
    }
}
