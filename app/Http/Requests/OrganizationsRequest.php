<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrganizationsRequest extends FormRequest
{
    /**
     * Authorization
     */
    public function authorize(): bool
    {
        // 🔥 IMPORTANT: must be true or policy will block
        return true;
    }

    /**
     * Validation rules
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:organizations,name',
        ];
    }

    /**
     * Custom messages (optional)
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Organization name is required',
            'name.unique'   => 'Organization name already exists',
        ];
    }
}
