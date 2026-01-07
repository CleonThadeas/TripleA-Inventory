<?php

namespace App\Policies;

use App\Models\AssetPackage;
use App\Models\User;

class AssetPackagePolicy
{
    public function view(User $user, AssetPackage $package): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'staff']);
    }

    public function update(User $user, AssetPackage $package): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $package->created_by === $user->id
            && $package->approved_at === null;
    }

    public function approve(User $user, AssetPackage $package): bool
    {
        return $user->isAdmin()
            && $package->approved_at === null;
    }

    public function changeStatus(User $user, AssetPackage $package): bool
    {
        return $user->isAdmin();
    }
}
