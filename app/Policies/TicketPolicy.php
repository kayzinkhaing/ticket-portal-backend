<?php

namespace App\Policies;

use App\Models\Ticket;
use App\Models\User;

class TicketPolicy
{
    /**
     * Determine if the user can view any tickets.
     */
    public function viewAny(User $user): bool
    {
        // Agents can see all tickets
        if ($user->hasRole('Agent')) {
            return true;
        }

        // Clients can only see their own tickets (enforced in query)
        if ($user->hasRole('Client')) {
            return true;
        }

        return false; // default deny
    }

    /**
     * Determine if the user can view a specific ticket.
     */
    public function view(User $user, Ticket $ticket): bool
    {
        // Agents can view any ticket
        if ($user->hasRole('Agent')) {
            return true;
        }

        // Clients can view only tickets belonging to their clientProfiles
        if ($user->hasRole('Client')) {
            return $user->clientProfiles
                        ->pluck('id')
                        ->contains($ticket->client_profile_id);
        }

        return false; // default deny
    }

    /**
     * Determine if the user can create a ticket.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('Client'); // only clients can create
    }

    /**
     * Determine if the user can update a ticket.
     */
    public function update(User $user, Ticket $ticket): bool
    {
        // Only agents can update tickets
        return $user->hasRole('Agent');
    }

    /**
     * Determine if the user can delete a ticket.
     */
    public function delete(User $user, Ticket $ticket): bool
    {
        return false; // nobody can delete
    }
}
