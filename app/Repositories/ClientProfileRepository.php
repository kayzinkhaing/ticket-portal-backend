<?php

namespace App\Repositories;

use App\Contracts\ClientProfileInterface;


class ClientProfileRepository extends BaseRepository implements ClientProfileInterface
{
    public function __construct()
    {
        parent::__construct('ClientProfile');
    }
}
