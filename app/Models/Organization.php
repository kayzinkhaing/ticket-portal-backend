<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Organization extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    /**
     * Get all client profiles belonging to this organization.
     */
    public function clientProfiles(): HasMany
    {
        return $this->hasMany(ClientProfile::class);
    }

    /**
     * Get all users that belong to this organization through client profiles.
     */
    public function users()
    {
        return $this->hasManyThrough(
            User::class,        // Final model
            ClientProfile::class, // Intermediate model
            'organization_id',   // Foreign key on ClientProfile table
            'id',               // Foreign key on User table (local key of final model)
            'id',               // Local key on Organization table
            'user_id'           // Local key on ClientProfile table
        );
    }
}
