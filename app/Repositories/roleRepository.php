<?php

namespace App\Repositories;

use App\Contracts\roleInterface;
use App\Models\Role;
use Illuminate\Support\Collection;

class roleRepository extends baseRepository implements roleInterface
{
    public function __construct()
    {
        parent::__construct(class_basename("Role"));
    }
    public function getRolebyId($roleId)
    {
        return $this->currentModel->find($roleId);
    }
}
