<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ClientProfile extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'organization_id',
    ];
    public function media()
    {
        return $this->morphMany(Media::class, 'mediable');
    }

    /**
     * ClientProfile belongs to User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * ClientProfile belongs to Organization
     */
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
}
