<?php
// app/Services/Media.php
namespace App\Services;

use App\Contracts\MediaInterface;

class Media extends Common
{
    protected $media;

    public function __construct(MediaInterface $media)
    {
        parent::__construct($media);

        $this->media = $media;
    }

    // Add Media-specific business logic if needed
}
