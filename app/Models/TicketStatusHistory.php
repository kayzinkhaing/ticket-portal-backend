<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketStatusHistory extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'ticket_id',
        'old_status_id',
        'new_status_id',
        'changed_by',
        'created_at',
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function oldStatus()
    {
        return $this->belongsTo(TicketStatus::class, 'old_status_id');
    }

    public function newStatus()
    {
        return $this->belongsTo(TicketStatus::class, 'new_status_id');
    }

    public function changedBy()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
