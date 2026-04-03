<?php

namespace App\Repositories;

use App\Contracts\MediaInterface;

class MediaRepository extends BaseRepository implements MediaInterface
{
    public function __construct()
    {
        parent::__construct('Media');
    }
}
