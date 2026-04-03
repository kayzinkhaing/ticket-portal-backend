<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\TicketStatuses;

class TicketStatusController extends Controller
{
    protected $ticketStatuses;

    public function __construct(TicketStatuses $ticketStatuses)
    {
        $this->ticketStatuses = $ticketStatuses;

        parent::__construct(
            $this->ticketStatuses
        );
    }
}
