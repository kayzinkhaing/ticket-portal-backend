<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TicketPrioritiesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'      => 'required|string|max:255|unique:ticket_priorities,name',
            'sla_hours' => 'required|integer|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'      => 'Priority name is required',
            'name.unique'        => 'Priority name already exists',
            'sla_hours.required' => 'SLA hours is required',
            'sla_hours.integer'  => 'SLA hours must be an integer',
        ];
    }
}
