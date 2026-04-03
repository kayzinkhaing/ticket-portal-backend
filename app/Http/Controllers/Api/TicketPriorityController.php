<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\TicketPriorities;

class TicketPriorityController extends Controller
{
    protected $ticketPriorities;

    public function __construct(TicketPriorities $ticketPriorities)
    {
        $this->ticketPriorities = $ticketPriorities;

        parent::__construct(
            $this->ticketPriorities
        );
    }
}
