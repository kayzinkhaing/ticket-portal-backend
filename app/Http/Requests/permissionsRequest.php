<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;


class permissionsRequest extends FormRequest
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
            'name' => 'required|string|unique:permissions,name,' . $this->route('permission'),
        ];
    }
}
