<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;


class rolesRequest extends FormRequest
{
    use authorizesRequests;
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|unique:roles,name,' . $this->route('role'), // For update, allow the current role to retain its name
        ];
    }

    /**
     * Custom messages for validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => 'The role name is required.',
            'name.string' => 'The role name must be a valid string.',
            'name.unique' => 'This role name has already been taken.',
        ];
    }
}
