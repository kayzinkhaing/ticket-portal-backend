<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommentsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'ticket_id'    => 'required|exists:tickets,id',
            'parent_id'    => 'nullable|exists:comments,id',
            'user_id'      => 'required|exists:users,id',
            'content'      => 'required|string',
            'is_internal'  => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'ticket_id.required' => 'Ticket is required',
            'ticket_id.exists'   => 'Ticket not found',
            'user_id.required'   => 'User is required',
            'user_id.exists'     => 'User not found',
            'content.required'   => 'Comment content is required',
        ];
    }
}
