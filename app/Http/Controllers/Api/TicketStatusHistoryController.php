<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\TicketStatusHistories;

class TicketStatusHistoryController extends Controller
{
    protected $ticketStatusHistories;

    public function __construct(TicketStatusHistories $ticketStatusHistories)
    {
        $this->ticketStatusHistories = $ticketStatusHistories;

        parent::__construct(
            $this->ticketStatusHistories
        );
    }
}
