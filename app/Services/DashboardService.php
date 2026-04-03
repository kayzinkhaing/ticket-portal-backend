<?php

namespace App\Services;

use App\Models\Ticket;
use App\Models\Comment;
use Carbon\Carbon;

class DashboardService
{
    /**
     * Get ticket metrics for dashboard
     */
    public function getTicketMetrics()
    {
        // Count tickets by status
        $ticketsByStatus = Ticket::with('status')
            ->select('status_id')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('status_id')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->status->name => $item->total];
            });

        // Count tickets by priority
        $ticketsByPriority = Ticket::with('priority')
            ->select('priority_id')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('priority_id')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->priority->name => $item->total];
            });

        // Overdue tickets (not resolved)
        $overdueTickets = Ticket::where('status_id', '!=', 3)
            ->where('sla_deadline', '<', now())
            ->count();

        // Average resolution time in hours
        $resolvedTickets = Ticket::where('status_id', 3)->get();
        $averageResolution = $resolvedTickets->map(function($ticket) {
            return $ticket->updated_at->diffInHours($ticket->created_at);
        })->avg();

        // Agent workload
        $agentTickets = Ticket::with('assignee')
            ->select('assigned_to')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('assigned_to')
            ->get()
            ->mapWithKeys(function($item) {
                $name = $item->assignee?->first_name ?? 'Unassigned';
                return [$name => $item->total];
            });

        return [
            'tickets_by_status' => $ticketsByStatus,
            'tickets_by_priority' => $ticketsByPriority,
            'overdue_tickets' => $overdueTickets,
            'average_resolution_hours' => round($averageResolution, 2),
            'agent_workload' => $agentTickets,
        ];
    }

    /**
     * Get comment metrics
     */
    public function getCommentMetrics()
    {
        $internalComments = Comment::where('is_internal', 1)->count();
        $externalComments = Comment::where('is_internal', 0)->count();

        return [
            'internal_comments' => $internalComments,
            'external_comments' => $externalComments,
        ];
    }

    /**
     * Get SLA remaining time per ticket
     */
    public function getTicketSLA()
    {
        $tickets = Ticket::where('status_id', '!=', 3)->get();

        return $tickets->map(function ($ticket) {
            $remainingHours = Carbon::parse($ticket->sla_deadline)
                ->diffInHours(now(), false); // false = allows negative if overdue

            return [
                'ticket_id' => $ticket->id,
                'title' => $ticket->title,
                'remaining_hours' => $remainingHours,
                'is_overdue' => $remainingHours < 0,
            ];
        });
    }

    /**
     * Full dashboard data
     */
    public function getDashboardData()
    {
        return [
            'ticket_metrics' => $this->getTicketMetrics(),
            'comment_metrics' => $this->getCommentMetrics(),
            'ticket_sla' => $this->getTicketSLA(),
        ];
    }
}
