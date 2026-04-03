<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TicketsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * ✅ Handle both CREATE and UPDATE
     */
    protected function prepareForValidation()
    {
        $user = auth()->user();

        // ✅ ONLY for CREATE
        if ($this->isMethod('post')) {
            $this->merge([
                'created_by' => $user?->id,
                'client_profile_id' => optional($user?->clientProfile)->id,

                // default values
                'assigned_to' => null,
                'assigned_by' => null,
            ]);
        }

        // ✅ ONLY for UPDATE (assignment)
        if ($this->isMethod('put') || $this->isMethod('patch')) {
            // 🔥 auto set assigned_by if assigned_to exists
            if ($this->has('assigned_to')) {
                $this->merge([
                    'assigned_by' => $user?->id
                ]);
            }
        }
    }

    /**
     * ✅ Dynamic validation rules
     */
    public function rules(): array
    {
        // ✅ CREATE rules
        if ($this->isMethod('post')) {
            return [
                'client_profile_id' => 'required|exists:client_profiles,id',
                'created_by'        => 'required|exists:users,id',

                'assigned_to'       => 'nullable|exists:users,id',
                'assigned_by'       => 'nullable|exists:users,id',

                'title'             => 'required|string|max:255',
                'description'       => 'required|string',

                'status_id'         => 'required|exists:ticket_statuses,id',
                'priority_id'       => 'required|exists:ticket_priorities,id',

                'sla_deadline'      => 'nullable|date',
            ];
        }

        // ✅ UPDATE rules (only what you update)
        if ($this->isMethod('put') || $this->isMethod('patch')) {
            return [
                'assigned_to' => 'nullable|exists:users,id',
                'assigned_by' => 'nullable|exists:users,id',
            ];
        }

        return [];
    }

    public function messages(): array
    {
        return [
            'assigned_to.exists' => 'Assigned user not found',
            'assigned_by.exists' => 'Assigning user not found',
        ];
    }
}
