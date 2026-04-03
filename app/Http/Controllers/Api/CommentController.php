<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Comments;

class CommentController extends Controller
{
    protected $comments;

    public function __construct(Comments $comments)
    {
        $this->comments = $comments;

        parent::__construct(
            $this->comments
        );
    }
}
