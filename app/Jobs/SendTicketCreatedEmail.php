<?php

namespace App\Jobs;

use App\Mail\TicketCreatedMail;
use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendTicketCreatedEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $ticket;

    /**
     * Create a new job instance.
     *
     * @param int|\App\Models\Ticket|\stdClass $ticket
     */
    public function __construct($ticket)
    {
        if (is_numeric($ticket)) {
            // If ID is passed, fetch Ticket model with client relationship
            $this->ticket = Ticket::with('client')->find($ticket);
        } elseif ($ticket instanceof Ticket) {
            // If Ticket model is passed, ensure client relationship is loaded
            $this->ticket = $ticket->loadMissing('client');
        } else {
            // If stdClass (serialized), convert to object safely
            $this->ticket = (object) $ticket;
        }
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        $recipient = $this->ticket->assigned_to_email ?? 'mimikhainglin70@gmail.com';

        Mail::to($recipient)
            ->send(new TicketCreatedMail($this->ticket));
    }
}