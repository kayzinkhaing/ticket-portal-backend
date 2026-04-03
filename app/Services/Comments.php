<?php
// app/Services/Comments.php
namespace App\Services;

use App\Contracts\CommentInterface;

class Comments extends Common
{
    protected $comment;

    public function __construct(CommentInterface $comment)
    {
        parent::__construct($comment);

        $this->comment = $comment;
    }

    // Add Comment-specific business logic if needed
}
