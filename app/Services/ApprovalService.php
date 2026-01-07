<?php

namespace App\Services;

use App\Models\Asset;
use App\Models\AssetPackage;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ApprovalService
{

    
    /**
     * Approve asset or package
     */
    public function approve($entity, User $admin): void
    {
        if (!$admin->isAdmin()) {
            throw ValidationException::withMessages([
                'authorization' => 'Only admin can approve assets.'
            ]);
        }

        if ($entity->approved_at !== null) {
            throw ValidationException::withMessages([
                'state' => 'Entity already approved.'
            ]);
        }

        DB::transaction(function () use ($entity, $admin) {
            $entity->status = 'active';
            $entity->approved_by = $admin->id;
            $entity->approved_at = now();
            $entity->save();
        });
        $before = $entity->fresh()->toArray();
// ... approve / reject logic
$after  = $entity->fresh()->toArray();

app(\App\Services\ActivityLogService::class)->log(
    $entity,
    'approve', // atau 'reject'
    $before,
    $after,
    request()
);

    }

    /**
     * Reject asset or package
     */
    public function reject($entity, User $admin): void
    {
        if (!$admin->isAdmin()) {
            throw ValidationException::withMessages([
                'authorization' => 'Only admin can reject assets.'
            ]);
        }

        if ($entity->approved_at !== null) {
            throw ValidationException::withMessages([
                'state' => 'Approved entity cannot be rejected.'
            ]);
        }

        DB::transaction(function () use ($entity) {
            $entity->status = 'lost';
            $entity->save();
        });
        $before = $entity->fresh()->toArray();
        // ... approve / reject logic
        $after  = $entity->fresh()->toArray();
        
        app(\App\Services\ActivityLogService::class)->log(
            $entity,
            'approve', // atau 'reject'
            $before,
            $after,
            request()
        );
        
    }
}
