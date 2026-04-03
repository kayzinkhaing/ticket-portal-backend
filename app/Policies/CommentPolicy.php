<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;

class CommentPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }
    public function view(User $user, Comment $comment): bool
    {
        if ($user->hasRole('Agent')) {
            return true;
        }

        if ($comment->is_internal) {
            return false;
        }

        return $user->clientProfiles
            ->pluck('id')
            ->contains($comment->ticket->client_profile_id);
    }

    public function create(User $user): bool
    {
        return true;
    }
}
