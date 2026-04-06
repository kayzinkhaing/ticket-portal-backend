<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketStatus extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    // Relationships
    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'status_id');
    }

    public function oldStatusHistories()
    {
        return $this->hasMany(TicketStatusHistory::class, 'old_status_id');
    }

    public function newStatusHistories()
    {
        return $this->hasMany(TicketStatusHistory::class, 'new_status_id');
    }
}
