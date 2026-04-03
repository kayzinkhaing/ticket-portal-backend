<?php

namespace App\Contracts;

use App\Models\User;

interface UserInterface extends BaseInterface
{
    public function getByEmail(string $email): ?User;
    public function getRoleIds(User $user): array;
}
