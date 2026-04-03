<?php

namespace App\Http\Controllers\Api;

use App\Filters\TicketFilter;
use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Services\Tickets;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    protected Tickets $tickets;

    public function __construct(Tickets $tickets)
    {
        $this->tickets = $tickets;
        parent::__construct($this->tickets);
    }

    /**
     * Get tickets filtered by status
     */
    public function byStatus(Request $request)
    {
        $user = auth()->user();
        $role = $user->currentRole() ?? 'guest';
        $status = $request->query('status', 'all');

        $tickets = $this->tickets->allByRoleAndStatus($role, $status);

        return response()->json([
            'success' => true,
            'data' => ['tickets' => $tickets]
        ]);
    }

    /**
     * Status counts for dashboard
     */
    public function statusCounts()
    {
        $role = auth()->user()->currentRole() ?? 'guest';
        $counts = $this->tickets->getStatusCounts($role);

        return response()->json(['counts' => $counts]);
    }

    /**
     * Tickets for all organizations the user belongs to
     */
    public function organizationTickets()
    {
        $tickets = $this->tickets->getOrganizationTickets();

        return response()->json([
            'success' => true,
            'data' => ['tickets' => $tickets]
        ]);
    }

    /**
     * Advanced search with filters and pagination
     */
    public function advancedSearch(Request $request)
    {
        $query = Ticket::with(['priority', 'status', 'clientProfile.user']);

        // Apply TicketFilter (handles status, priority, keyword, dates, organization)
        $ticketsQuery = (new TicketFilter($request))->apply($query);

        // Optional: restrict to organizations user belongs to
        $userOrgIds = auth()->user()->clientProfiles->pluck('organization_id')->toArray();
        if (!empty($userOrgIds)) {
            $ticketsQuery->whereHas('clientProfile', fn($q) => $q->whereIn('organization_id', $userOrgIds));
        }

        // Pagination
        $tickets = $ticketsQuery->orderByDesc('created_at')->paginate(10);

        return response()->json([
            'success' => true,
            'data' => ['tickets' => $tickets]
        ]);
    }
}

