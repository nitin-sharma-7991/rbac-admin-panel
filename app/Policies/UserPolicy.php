<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function assignRole(User $user, User $targetUser): bool
    {
        return $user->hasRole('Admin');
    }

    public function viewRoles(User $user, User $targetUser): bool
    {
        return $user->hasRole('Admin');
    }
}
