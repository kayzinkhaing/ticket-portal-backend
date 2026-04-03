<?php

namespace App\Services;

use App\Contracts\UserInterface;
use Illuminate\Support\Facades\Redis;
use Illuminate\Http\UploadedFile;
use App\Events\UserRegistered;

class RegistrationService extends Common
{
    protected $userRepository;
    protected int $verificationExpiry = 60; // minutes
    protected int $resendCooldown = 45; // seconds

    public function __construct(UserInterface $userRepository)
    {
        parent::__construct($userRepository);

        $this->userRepository = $userRepository;
    }

    /** Store OTP in Redis */
    public function storeTempUser(array $data, ?UploadedFile $image = null): void
    {
        if ($image) {
            $data['image'] = $image;
        }

        $data['code'] = $this->generateCode();
        Redis::setex("register:{$data['email']}", $this->verificationExpiry * 60, json_encode($data));

        event(new UserRegistered($data['email'], $data['code']));
    }

    /** Resend OTP */
    public function resendCode(string $email): ?string
    {
        $resendKey = "resend_limit:{$email}";
        if (Redis::exists($resendKey)) return null;

        $stored = Redis::get("register:{$email}");
        if (!$stored) return null;

        $data = json_decode($stored, true);
        $data['code'] = $this->generateCode();

        Redis::setex("register:{$email}", $this->verificationExpiry * 60, json_encode($data));
        Redis::setex($resendKey, $this->resendCooldown, true);

        event(new UserRegistered($data['email'], $data['code']));
        return $data['code'];
    }

    /** Verify OTP */
    /** Verify OTP */
    public function verifyCode(string $email, string $code): ?array
    {
        $stored = Redis::get("register:{$email}");
        if (!$stored) return null;

        $data = json_decode($stored, true);
        if (!$data || !isset($data['code'])) return null;

        if ((string)$data['code'] !== (string)$code) return null;

        // Delete temp OTP
        Redis::del("register:{$email}");

        // 1️⃣ Create user
        $user = $this->userRepository->create([
            'first_name' => $data['first_name'],
            'middle_name' => $data['middle_name'] ?? null,
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'password' => $data['password'],
        ]);

        // 2️⃣ Assign role
        $user->assignRole($data['role']);

        // 3️⃣ Client profile
        if ($data['role'] === 'client' && !empty($data['organization_id'])) {
            \App\Models\ClientProfile::create([
                'user_id' => $user->id,
                'organization_id' => $data['organization_id'],
            ]);
        }

        // 4️⃣ Handle image
        if (!empty($data['image'])) {
            $this->handleMedia($user, $data['image']);
        }

        return [
            'user' => $user,
            'role' => $data['role'],
            'message' => 'Registration successful',
        ];
    }

    /** Generate 6-digit OTP */
    protected function generateCode(): string
    {
        return str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    }

    /** Send OTP email */
    // protected function sendVerificationEmail(string $email, string $code): void
    // {
    //     Mail::to($email)->send(new \App\Mail\VerificationCodeMail($code));
    // }
}
