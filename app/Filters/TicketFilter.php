<?php

namespace App\Filters;

use Illuminate\Http\Request;

class TicketFilter extends BaseFilter
{
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    // protected function organization_id($orgId)
    // {
    //     $this->builder->whereHas('clientProfile', fn($q) => $q->where('organization_id', $orgId));
    // }

    protected function organization_id($orgId)
    {
        // Try to filter directly on the ticket first if the column exists
        // Otherwise, keep the relation filter but ensure it handles arrays or single IDs
        $this->builder->where(function ($query) use ($orgId) {
            $query->whereHas('clientProfile', function ($q) use ($orgId) {
                $q->where('organization_id', $orgId);
            });
        });
    }

    protected function client_profile_id($value)
    {
        $this->builder->where('client_profile_id', $value);
    }

    protected function status_id($value)
    {
        if (is_array($value)) {
            $this->builder->whereIn('status_id', $value);
        } else {
            $this->builder->where('status_id', $value);
        }
    }

    protected function priority_id($value)
    {
        if (is_array($value)) {
            $this->builder->whereIn('priority_id', $value);
        } else {
            $this->builder->where('priority_id', $value);
        }
    }

    protected function date_from($value)
    {
        $this->builder->whereDate('created_at', '>=', $value);
    }

    protected function date_to($value)
    {
        $this->builder->whereDate('created_at', '<=', $value);
    }

    protected function keyword($value)
    {
        $this->builder->where(function ($q) use ($value) {
            $q->where('title', 'LIKE', "%{$value}%")
                ->orWhere('description', 'LIKE', "%{$value}%");
            if (is_numeric($value)) {
                $q->orWhere('id', $value);
            }
        });
    }
}
