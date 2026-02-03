<?php

namespace App\Policies;

use App\Models\User;
use App\Models\ActivityLog;

class ActivityLogPolicy
{
    /**
     * Admin only
     */
    protected function isAdmin(User $user): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Apakah user boleh melihat daftar perubahan
     */
    public function viewAny(User $user): bool
    {
        return $this->isAdmin($user);
    }

    /**
     * Apakah user boleh melihat detail perubahan
     */
    public function view(User $user, ActivityLog $log): bool
    {
        return $this->isAdmin($user);
    }

    /**
     * Apakah user boleh approve perubahan
     */
    public function approve(User $user, ActivityLog $log): bool
    {
        return $this->isAdmin($user)
            && $log->event === 'UPDATE'
            && $log->approval_status === 'pending';
    }

    /**
     * Apakah user boleh reject perubahan
     */
    public function reject(User $user, ActivityLog $log): bool
    {
        return $this->approve($user, $log);
    }
}
