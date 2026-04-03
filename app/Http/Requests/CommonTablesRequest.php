<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommonTablesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type'       => 'required|string|max:255',
            'key'        => 'required|string|max:255',
            'value'      => 'required|string|max:255',
            'label'      => 'nullable|string|max:255',
            'description'=> 'nullable|string',
            'sort_order' => 'nullable|integer',
        ];
    }

    public function messages(): array
    {
        return [
            'type.required'  => 'Type is required',
            'key.required'   => 'Key is required',
            'value.required' => 'Value is required',
        ];
    }
}
