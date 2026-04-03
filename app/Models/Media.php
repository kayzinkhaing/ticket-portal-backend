<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Media extends BaseModel
{
    use HasFactory;

    protected $table = 'media';

    public $timestamps = false;
    protected $fillable = [
        'url',
        'original_filename',
        'mime_type',
        'size',
        'uploaded_at',
        'mediable_type',
        'mediable_id',
    ];
    /**
     * Polymorphic relationship
     */
    public function mediable()
    {
        return $this->morphTo();
    }
}
