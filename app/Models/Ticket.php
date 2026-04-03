<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_profile_id',
        'created_by',
        'assigned_to',
        'assigned_by',
        'title',
        'description',
        'status_id',
        'priority_id',
        'sla_deadline',
    ];

    // Relationships
    public function clientProfile()
    {
        return $this->belongsTo(ClientProfile::class);
    }

    public function client()
{
    return $this->clientProfile();
}

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    public function status()
    {
        return $this->belongsTo(TicketStatus::class, 'status_id');
    }

    public function priority()
    {
        return $this->belongsTo(TicketPriority::class, 'priority_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function media()
    {
        return $this->morphMany(Media::class, 'mediable');
    }

    public function statusHistories()
    {
        return $this->hasMany(TicketStatusHistory::class);
    }
    public function scopeFilter($query, $filter)
    {
        return $filter->apply($query);
    }
}
