<?php

namespace App\Repositories;

use App\Contracts\TicketPriorityInterface;

class TicketPriorityRepository extends BaseRepository implements TicketPriorityInterface
{
    public function __construct()
    {
        parent::__construct('TicketPriority');
    }
}
