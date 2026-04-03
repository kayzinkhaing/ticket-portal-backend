<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    // Define the inverse of the relationship
    // Many-to-many relationship with roles through permission_role table
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'permission_role');
    }
}
