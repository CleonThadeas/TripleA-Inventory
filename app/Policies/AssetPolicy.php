<?php

namespace App\Policies;

use App\Models\Asset;
use App\Models\User;

class AssetPolicy
{
    /**
     * View asset
     */
    public function view(User $user, Asset $asset): bool
    {
        return true; // Semua role boleh melihat
    }

    /**
     * Create asset
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Update asset
     */
    public function update(User $user, Asset $asset): bool
    {
        // Admin bisa edit semua
        if ($user->isAdmin()) {
            return true;
        }

        // Staff hanya boleh edit sebelum approve
        return $asset->created_by === $user->id
            && $asset->approved_at === null;
    }
    public function delete(User $user, Asset $asset): bool
    {
        return $user->isAdmin();
    }
    /**
     * Approve asset
     */
    public function approve(User $user, Asset $asset): bool
    {
        return $user->isAdmin() && $asset->status === 'pending';
    }
    

    /**
     * Change status
     */
    public function changeStatus(User $user, Asset $asset): bool
    {
        return $user->isAdmin();
    }
    // AppServiceProvider
protected $policies = [
    Asset::class => AssetPolicy::class,
];

}
