<?php

namespace App\Jobs;

use App\Mail\TicketClosedMail;
use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendTicketClosedEmail implements ShouldQueue
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
            $this->ticket = Ticket::with('client')->find($ticket);
        } elseif ($ticket instanceof Ticket) {
            $this->ticket = $ticket->loadMissing('client');
        } else {
            $this->ticket = (object) $ticket;
        }
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        $recipient = $this->ticket->client->email ?? 'mimikhainglin70@gmail.com';

        Mail::to($recipient)
            ->send(new TicketClosedMail($this->ticket));
    }
}