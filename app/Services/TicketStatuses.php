<?php
// app/Services/TicketStatuses.php
namespace App\Services;

use App\Contracts\TicketStatusInterface;

class TicketStatuses extends Common
{
    protected $ticketStatus;

    public function __construct(TicketStatusInterface $ticketStatus)
    {
        parent::__construct($ticketStatus);

        $this->ticketStatus = $ticketStatus;
    }

    // Add TicketStatus-specific business logic if needed
}
