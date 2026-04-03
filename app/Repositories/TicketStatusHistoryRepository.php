<?php

namespace App\Repositories;

use App\Contracts\TicketStatusHistoryInterface;

class TicketStatusHistoryRepository extends BaseRepository implements TicketStatusHistoryInterface
{
    public function __construct()
    {
        parent::__construct('TicketStatusHistory');
    }
}
