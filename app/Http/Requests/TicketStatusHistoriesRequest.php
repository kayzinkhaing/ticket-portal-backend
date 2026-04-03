<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TicketStatusHistoriesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'ticket_id'      => 'required|exists:tickets,id',
            'old_status_id'  => 'required|exists:ticket_statuses,id',
            'new_status_id'  => 'required|exists:ticket_statuses,id',
            'changed_by'     => 'required|exists:users,id',
        ];
    }

    public function messages(): array
    {
        return [
            'ticket_id.required'     => 'Ticket is required',
            'ticket_id.exists'       => 'Ticket not found',
            'old_status_id.required' => 'Old status is required',
            'old_status_id.exists'   => 'Old status not found',
            'new_status_id.required' => 'New status is required',
            'new_status_id.exists'   => 'New status not found',
            'changed_by.required'    => 'User changing status is required',
            'changed_by.exists'      => 'User not found',
        ];
    }
}
