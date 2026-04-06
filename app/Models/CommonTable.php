<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommonTable extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'type',
        'key',
        'value',
        'label',
        'description',
        'sort_order',
    ];
}
