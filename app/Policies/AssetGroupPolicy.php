<?php

namespace App\Policies;

use App\Models\AssetGroup;
use App\Models\User;

class AssetGroupPolicy
{
    /**
     * CREATE
     * - Admin & Staff boleh
     */
    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'staff']);
    }

    /**
     * VIEW
     */
    public function view(User $user, AssetGroup $group): bool
    {
        return true;
    }

    /**
     * UPDATE
     * - Admin  : bebas
     * - Staff  : hanya group yang dia buat
     */
    public function update(User $user, AssetGroup $group): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->isStaff()
            && $group->created_by === $user->id;
    }

    /**
     * DELETE
     * - Admin saja
     */
    public function delete(User $user, AssetGroup $group): bool
    {
        return $user->isAdmin();
    }

    /**
     * APPROVE
     * - Admin saja
     */
    public function approve(User $user, AssetGroup $group): bool
    {
        return $user->isAdmin()
            && $group->approval_status === 'pending';
    }
}
