<?php

namespace App\Repositories;

use App\Contracts\OrganizationInterface;

class OrganizationRepository extends BaseRepository implements OrganizationInterface
{
    public function __construct()
    {
        parent::__construct('Organization');
    }
}
