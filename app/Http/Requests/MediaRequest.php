<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MediaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'ticket_id'         => 'required|exists:tickets,id',
            'url'               => 'required|string',
            'original_filename' => 'required|string',
            'mime_type'         => 'required|string',
            'size'              => 'required|integer',
            'uploaded_by'       => 'required|exists:users,id',
        ];
    }

    public function messages(): array
    {
        return [
            'ticket_id.required' => 'Ticket is required',
            'ticket_id.exists'   => 'Ticket not found',
            'url.required'       => 'Media URL is required',
            'original_filename.required' => 'Original filename is required',
            'mime_type.required' => 'MIME type is required',
            'size.required'      => 'File size is required',
            'uploaded_by.required' => 'Uploader is required',
            'uploaded_by.exists'   => 'Uploader not found',
        ];
    }
}
