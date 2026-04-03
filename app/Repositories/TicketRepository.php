<?php

namespace App\Repositories;

use App\Contracts\TicketInterface;
use App\Models\Ticket;
use App\Models\TicketStatus;
use Illuminate\Support\Facades\DB;

class TicketRepository extends BaseRepository implements TicketInterface
{
    public function __construct()
    {
        parent::__construct('Ticket');
    }

    /**
     * Calculate SLA dynamically for a ticket
     */
    public function addSlaStatus(Ticket $ticket): Ticket
    {
        if (!$ticket->priority?->sla_hours) {
            $ticket->sla_status = 'ON TRACK';
            $ticket->sla_deadline = $ticket->created_at;
            return $ticket;
        }

        $now = now();
        $slaHours = $ticket->priority->sla_hours;

        // If reopening, start from now; otherwise from created_at
        $startTime = $ticket->reopened_at ?? $ticket->created_at;

        $deadline = $startTime->copy()->addHours($slaHours);
        $diffHours = $deadline->diffInHours($now, false); // negative if overdue

        // Dynamic due soon threshold = 25% of SLA
        $dueSoonThreshold = ceil($slaHours * 0.25);

        if ($diffHours < 0) {
            $ticket->sla_status = 'BREACHED';
        } elseif ($diffHours <= $dueSoonThreshold) {
            $ticket->sla_status = 'DUE SOON';
        } else {
            $ticket->sla_status = 'ON TRACK';
        }

        $ticket->sla_deadline = $deadline;

        return $ticket;
    }

    /**
     * Get paginated tickets by role and status
     */
    public function getByRoleAndStatus(string $role, ?string $status = null)
    {
        $query = $this->model->newQuery()->with(['priority', 'status', 'clientProfile.user']);

        $user = auth()->user();

        // Role-based scoping
        if ($role === 'client') {
            $orgIds = $user->clientProfiles->pluck('organization_id')->unique();
            $query->whereHas('clientProfile', fn($q) => $q->whereIn('organization_id', $orgIds));
        } elseif ($role === 'agent') {
            $query->where('assigned_to', $user->id);
        }

        // Status filter
        if ($status && strtolower($status) !== 'all') {
            $status = strtolower($status);

            if ($status === 'overdue') {
                $query->where('sla_deadline', '<', now())
                    ->whereHas('status', fn($q) => $q->whereNotIn('name', ['Resolved', 'Closed']));
            } elseif ($status === 'unassigned') {
                $query->whereNull('assigned_to');
            } else {
                $query->whereHas('status', fn($q) => $q->whereRaw('LOWER(name) = ?', [$status]));
            }
        }

        $tickets = $query->latest()->paginate(10);

        // Add SLA status for each ticket dynamically
        $tickets->getCollection()->transform(fn($ticket) => $this->addSlaStatus($ticket));

        return $tickets;
    }

    /**
     * Status counts for dashboard
     */
    public function getStatusCounts(string $role): array
    {
        $user = auth()->user();
        $baseQuery = $this->model->newQuery()->with(['priority', 'status']);

        // Role scoping
        if ($role === 'client') {
            $orgIds = $user->clientProfiles->pluck('organization_id')->unique();
            $baseQuery->whereHas('clientProfile', fn($q) => $q->whereIn('organization_id', $orgIds));
        } elseif ($role === 'agent') {
            $baseQuery->where('assigned_to', $user->id);
        }

        $statuses = TicketStatus::pluck('id', 'name');
        $resolvedId = $statuses['Resolved'] ?? null;
        $closedId = $statuses['Closed'] ?? null;

        return [
            'all' => (clone $baseQuery)->count(),
            'open' => (clone $baseQuery)->where('status_id', $statuses['Open'] ?? 0)->count(),
            'in_progress' => (clone $baseQuery)->where('status_id', $statuses['In Progress'] ?? 0)->count(),
            'resolved' => (clone $baseQuery)->where('status_id', $resolvedId)->count(),
            'closed' => (clone $baseQuery)->where('status_id', $closedId)->count(),
            'unassigned' => (clone $baseQuery)->whereNull('assigned_to')->count(),
            'overdue' => (clone $baseQuery)
                ->where('sla_deadline', '<', now())
                ->whereNotIn('status_id', array_filter([$resolvedId, $closedId]))
                ->count(),
        ];
    }
    public function getTicketsByOrganization(array $orgIds)
    {
        return $this->model
            ->whereHas('clientProfile', fn($q) => $q->whereIn('organization_id', $orgIds))
            ->with(['clientProfile.user', 'priority', 'status'])
            ->orderByDesc('created_at')
            ->paginate(10);;
    }
}
