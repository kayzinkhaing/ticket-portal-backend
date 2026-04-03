<?php

namespace App\Policies;

use App\Models\ClientProfile;
use App\Models\User;

class ClientProfilePolicy
{
    public function view(User $user, ClientProfile $profile): bool
    {
        return true;

        return $profile->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->hasRole('Agent');
    }

    public function update(User $user, ClientProfile $profile): bool
    {
        return $user->hasRole('Agent');
    }

    public function delete(User $user, ClientProfile $profile): bool
    {
        return $user->hasRole('Agent');
    }
}
