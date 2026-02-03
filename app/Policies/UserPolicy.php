<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function viewAny(User $auth): bool
    {
        return $auth->isAdmin();
    }

    public function create(User $auth): bool
    {
        return $auth->isAdmin();
    }

    public function update(User $auth, User $user): bool
    {
        if ($user->is_super_admin) {
            return $auth->is_super_admin;
        }

        return $auth->isAdmin();
    }

    public function delete(User $auth, User $user): bool
    {
        if ($user->is_super_admin) {
            return false;
        }

        return $auth->isAdmin();
    }
}
