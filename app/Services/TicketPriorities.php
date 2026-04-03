<?php
// app/Services/TicketPriorities.php
namespace App\Services;

use App\Contracts\TicketPriorityInterface;

class TicketPriorities extends Common
{
    protected $ticketPriority;

    public function __construct(TicketPriorityInterface $ticketPriority)
    {
        parent::__construct($ticketPriority);

        $this->ticketPriority = $ticketPriority;
    }

    // Add TicketPriority-specific business logic if needed
}
