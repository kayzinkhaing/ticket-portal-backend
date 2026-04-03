<?php
namespace App\Repositories;

use App\Contracts\UserInterface;
use App\Models\User;

class UserRepository extends BaseRepository implements UserInterface
{
    public function __construct()
    {
        parent::__construct('User');
    }
    public function getByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    public function getRoleIds(User $user): array
    {
        return $user->roles()->pluck('id')->toArray();
    }
}
