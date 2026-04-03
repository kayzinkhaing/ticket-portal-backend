<?php

namespace App\Contracts;

use App\Contracts\baseInterface;
use Illuminate\Support\Collection;

interface permissionInterface extends baseInterface
{
    function getPermissionbyId($roleId);
}
