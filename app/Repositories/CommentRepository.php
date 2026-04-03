<?php

namespace App\Repositories;

use App\Contracts\CommentInterface;

class CommentRepository extends BaseRepository implements CommentInterface
{
    public function __construct()
    {
        parent::__construct('Comment');
    }
}
