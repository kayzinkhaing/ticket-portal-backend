<?php

namespace App\Policies;

use App\Models\Organization;
use App\Models\User;

class OrganizationPolicy
{
    /**
     * View all organizations
     */
    public function viewAny(User $user): bool
    {
        // Only Agent can view all organizations
        return true;
    }

    /**
     * View single organization
     */
    public function view(User $user, Organization $organization): bool
    {
        // Agent → full access
        // if ($user->hasRole('Agent')) {
        //     return true;
        // }

        // // Client → only their organization
        // return $user->organizations()
        //     ->where('organizations.id', $organization->id)
        //     ->exists();
        return true;
    }

    /**
     * Create organization
     */
    public function create(User $user): bool
    {
        return $user->hasRole('Agent');
    }

    /**
     * Update organization
     */
    public function update(User $user, Organization $organization): bool
    {
        return $user->hasRole('Agent');
    }

    /**
     * Delete organization
     */
    public function delete(User $user, Organization $organization): bool
    {
        return $user->hasRole('Agent');
    }

    /**
     * Restore organization
     */
    public function restore(User $user, Organization $organization): bool
    {
        return $user->hasRole('Agent');
    }

    /**
     * Force delete organization
     */
    public function forceDelete(User $user, Organization $organization): bool
    {
        return $user->hasRole('Agent');
    }
}
