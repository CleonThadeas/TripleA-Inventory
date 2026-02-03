<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ActivityLogService
{
    /**
     * CREATE | UPDATE | DELETE | APPROVE | REJECT
     */
    public static function log(
        string $event,
        Model $model,
        ?array $before = null,
        ?array $after = null,
        ?array $changes = null,
        ?string $approvalStatus = null,
        array $meta = []
    
    ): void {
        try {
            $event = strtoupper($event);

            // AUTO CHANGES
            if ($event === 'UPDATE' && $before && $after && $changes === null) {
                $changes = self::calculateChanges($before, $after);
            }

            ActivityLog::create([
                'loggable_type' => get_class($model),
                'loggable_id'   => $model->getKey(),
                'action'        => $event,
                'before_data'   => $before,
                'after_data'    => $after,
                'changes'       => $changes,
                'user_id'       => Auth::id(),
                'ip_address'    => request()?->ip(),
                'meta'          => $meta,
            ]);

        } catch (\Throwable $e) {
            // ⛔ LOG ERROR SAJA, JANGAN GAGALKAN PROSES UTAMA
            Log::error('ActivityLog failed', [
                'error' => $e->getMessage()
            ]);
        }
    }

    protected static function calculateChanges(array $before, array $after): array
    {
        $changes = [];

        foreach ($after as $key => $value) {
            if (!array_key_exists($key, $before)) {
                continue;
            }

            if ($before[$key] != $value) {
                $changes[$key] = [
                    'before' => $before[$key],
                    'after'  => $value,
                ];
            }
        }

        return $changes;
    }
}
