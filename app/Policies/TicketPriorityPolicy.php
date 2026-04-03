<?php

namespace App\Policies;

use App\Models\User;

class TicketPriorityPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function update(User $user): bool
    {
        return $user->hasRole('Agent');
    }
}
