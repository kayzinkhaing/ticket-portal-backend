<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TicketStatusesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:ticket_statuses,name',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Ticket status name is required',
            'name.unique'   => 'Ticket status name already exists',
        ];
    }
}
