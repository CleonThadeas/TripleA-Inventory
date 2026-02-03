<?php

namespace App\Policies;

use App\Models\Asset;
use App\Models\User;

class AssetPolicy
{
    /**
     * CREATE
     * - Admin  : langsung boleh (auto approve)
     * - Staff  : boleh create tapi status pending
     */
    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'staff']);
    }

    /**
     * VIEW
     * - Semua user login boleh lihat
     */
    public function view(User $user, Asset $asset): bool
    {
        return true;
    }

    /**
     * UPDATE
     * - Admin  : bebas edit kapan saja
     * - Staff  :
     *   - boleh edit asset miliknya
     *   - jika asset sudah approved → perubahan masuk pending approval
     */
    public function update(User $user, Asset $asset): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->isStaff()
            && $asset->created_by === $user->id;
    }

    /**
     * DELETE
     * - Admin saja
     */
    public function delete(User $user, Asset $asset): bool
    {
        return $user->isAdmin();
    }

    /**
     * APPROVE
     * - Admin saja
     */
    public function approve(User $user, Asset $asset): bool
    {
        return $user->isAdmin()
            && $asset->approval_status === 'pending';
    }
}
