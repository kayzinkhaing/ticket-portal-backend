<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MessagesRequest extends FormRequest
{
    use authorizesRequests;
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        // Define basic rules for the fields
        $rules = [
            'message' => 'required|string', // Only validate message
        ];

        if ($this->isMethod('put')) {
            $rules['name'] = 'nullable';
        } else {
            $rules['name'] = 'required|string|unique:messages,name';
        }

        return $rules;
    }

    /**
     * Get the error messages for the request validation.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => 'The name value is required.',
            'name.unique' => 'This name value already exists.',
            'message.required' => 'The message value is required.',
            'message.unique' => 'This message value already exists.',
        ];
    }
}
