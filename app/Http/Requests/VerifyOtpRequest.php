<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VerifyOtpRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Allow all users to submit OTP verification
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => 'required|email',
            'otp'   => 'required|array|size:6',
            'otp.*' => 'required|string|size:1',
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'Email is required.',
            'email.email'    => 'Enter a valid email.',
            'otp.required'   => 'OTP is required.',
            'otp.array'      => 'OTP must be an array of 6 digits.',
            'otp.size'       => 'OTP must be exactly 6 digits.',
            'otp.*.required' => 'Each OTP digit is required.',
            'otp.*.size'     => 'Each OTP digit must be exactly 1 character.',
        ];
    }
}
