<?php

namespace App\Repositories;

use App\Contracts\CommonTableInterface;

class CommonTableRepository extends BaseRepository implements CommonTableInterface
{
    public function __construct()
    {
        parent::__construct('CommonTable');
    }
}
