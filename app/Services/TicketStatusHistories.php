<?php
// app/Services/TicketStatusHistories.php
namespace App\Services;

use App\Contracts\TicketStatusHistoryInterface;

class TicketStatusHistories extends Common
{
    protected $ticketStatusHistory;

    public function __construct(TicketStatusHistoryInterface $ticketStatusHistory)
    {
        parent::__construct($ticketStatusHistory);

        $this->ticketStatusHistory = $ticketStatusHistory;
    }

    // Add TicketStatusHistory-specific business logic if needed
}
