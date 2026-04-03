<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClientProfilesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // You can add more complex authorization if needed
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'organization_id' => ['required', 'integer', 'exists:organizations,id'],
        ];
    }

    /**
     * Custom messages for validation
     */
    public function messages(): array
    {
        return [
            'user_id.required' => 'User is required.',
            'user_id.integer' => 'User ID must be a number.',
            'user_id.exists' => 'User not found in the system.',

            'organization_id.required' => 'Organization is required.',
            'organization_id.integer' => 'Organization ID must be a number.',
            'organization_id.exists' => 'Organization not found in the system.',
        ];
    }
}
