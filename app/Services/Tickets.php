<?php

namespace App\Services;

use App\Contracts\TicketInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Carbon;

class Tickets extends Common
{
    protected TicketInterface $ticketRepo;

    public function __construct(TicketInterface $ticketRepo)
    {
        parent::__construct($ticketRepo);
        $this->ticketRepo = $ticketRepo;
    }

    /**
     * Get tickets by role and optional status
     */
    public function allByRoleAndStatus(string $role, ?string $status = null)
    {
        return $this->ticketRepo->getByRoleAndStatus($role, $status);
    }

    /**
     * Get counts by status for dashboard
     */
    public function getStatusCounts(string $role): array
    {
        return $this->ticketRepo->getStatusCounts($role);
    }

    /**
     * Calculate SLA for a ticket (use when storing or reopening)
     */
    public function calculateSla(int $priorityId, ?Carbon $reopenAt = null): Carbon
    {
        $priority = \App\Models\TicketPriority::find($priorityId);

        if (!$priority?->sla_hours) {
            return now();
        }

        $start = $reopenAt ?? now();
        return $start->copy()->addHours($priority->sla_hours);
    }

    /**
     * Get tickets for all organizations the user belongs to
     */
    public function getOrganizationTickets()
    {
        $user = auth()->user();

        $orgIds = $user->clientProfiles->pluck('organization_id')->unique();

        if ($orgIds->isEmpty()) {
            return collect(); // empty collection if no orgs
        }

        // ✅ Use repository method instead of accessing protected property
        return $this->ticketRepo->getTicketsByOrganization($orgIds->toArray());
    }
}
