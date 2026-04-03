<?php

namespace App\Models\Pivots;

class RoleUser extends BasePivot
{
    // Specify pivot table
    protected $table = 'role_user';

    // Fillable fields
    protected $fillable = [
        'user_id',
        'role_id',
    ];
}
