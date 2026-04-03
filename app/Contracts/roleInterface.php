<?php

namespace App\Contracts;

use App\Contracts\baseInterface;
use Illuminate\Support\Collection;

interface RoleInterface extends baseInterface
{
    function getRolebyId($roleId);
}
