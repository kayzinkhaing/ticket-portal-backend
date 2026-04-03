<?php

namespace App\Repositories;

use App\Contracts\TicketStatusInterface;

class TicketStatusRepository extends BaseRepository implements TicketStatusInterface
{
    public function __construct()
    {
        parent::__construct('TicketStatus');
    }
}
