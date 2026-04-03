<?php

namespace App\Console\Commands;

use App\Jobs\SendTicketClosedEmail;
use App\Jobs\SendTicketCreatedEmail;
use App\Jobs\SendTicketDueSoonEmail;
use App\Jobs\SendTicketOverdueEmail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log; // add this at the top

class NotifyTickets extends Command
{
    protected $signature = 'notify:tickets';
    protected $description = 'Handle all ticket notifications';

    public function handle()
    {
        $this->info("Checking tickets...");

        // 1. Ticket Created → Agent
        $tickets = \App\Models\Ticket::where('created_notified', false)->get();

        foreach ($tickets as $ticket) {
            SendTicketCreatedEmail::dispatch($ticket);
            DB::table('tickets')->where('id', $ticket->id)
                ->update(['created_notified' => true]);

            // Logging
            $this->info("Ticket ID {$ticket->id} created notification sent.");
            Log::info("Ticket ID {$ticket->id} created notification sent."); // writes to laravel.log
        }

        // 2. Ticket Closed → Client
        $tickets = DB::table('tickets')
            ->join('ticket_statuses', 'tickets.status_id', '=', 'ticket_statuses.id')
            ->where('ticket_statuses.name', 'closed')
            ->where('closed_notified', false)
            ->select('tickets.*')
            ->get();

        foreach ($tickets as $ticket) {
            SendTicketClosedEmail::dispatch($ticket);
            DB::table('tickets')->where('id', $ticket->id)
                ->update(['closed_notified' => true]);

            $this->info("Ticket ID {$ticket->id} closed notification sent.");
            Log::info("Ticket ID {$ticket->id} closed notification sent.");
        }

        // 3. Overdue → Client
        $tickets = DB::table('tickets')
            ->join('ticket_statuses', 'tickets.status_id', '=', 'ticket_statuses.id')
            ->where('ticket_statuses.name', 'open')
            ->where('sla_deadline', '<', now())
            ->where('overdue_notified', false)
            ->select('tickets.*')
            ->get();

        foreach ($tickets as $ticket) {
            SendTicketOverdueEmail::dispatch($ticket);
            DB::table('tickets')->where('id', $ticket->id)
                ->update(['overdue_notified' => true]);

            $this->info("Ticket ID {$ticket->id} overdue notification sent.");
            Log::info("Ticket ID {$ticket->id} overdue notification sent.");
        }

        // 4. Due Soon → Client
        $tickets = DB::table('tickets')
            ->join('ticket_statuses', 'tickets.status_id', '=', 'ticket_statuses.id')
            ->where('ticket_statuses.name', 'open')
            ->whereBetween('sla_deadline', [now(), now()->addDay()])
            ->where('duesoon_notified', false)
            ->select('tickets.*')
            ->get();

        foreach ($tickets as $ticket) {
            SendTicketDueSoonEmail::dispatch($ticket);
            DB::table('tickets')->where('id', $ticket->id)
                ->update(['duesoon_notified' => true]);

            $this->info("Ticket ID {$ticket->id} due soon notification sent.");
            Log::info("Ticket ID {$ticket->id} due soon notification sent.");
        }

        $this->info("Done.");
        Log::info("NotifyTickets command finished successfully.");
    }
}