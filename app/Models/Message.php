<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    // Define the table name if it's not the plural form of the model name
    // protected $table = 'messages';

    // Define the fillable attributes
    protected $fillable = ['name', 'message'];

    // Optionally, you can add validation rules or relationships if needed
}
