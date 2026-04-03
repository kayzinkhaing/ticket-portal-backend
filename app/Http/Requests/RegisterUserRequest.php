<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // allow all users to register
    }

    public function rules(): array
    {
        return [
            // 'name' => ['required', 'string', 'max:255'],
            'first_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                // This only blocks them if they exist in the USERS table.
                // If they only exist in REDIS, this will pass, allowing a "retry".
                'unique:users,email'
            ],
            'password' => ['required', 'confirmed'],
            'recaptcha_token' => ['nullable', 'string'],
            'role' => ['required', 'in:client,agent'], // client or agent
            // 'organization_id' => ['required_if:role,client', 'exists:organizations,id'],
            'organization_id' => ['nullable', 'required_if:role,client', 'exists:organizations,id'],
            'image' => [
                'nullable',
                'file',           // ensures it's an uploaded file
                'image',          // must be an image
                'mimes:jpg,jpeg,png,gif',
                'max:2048',       // 2MB
            ],
        ];
    }
}
