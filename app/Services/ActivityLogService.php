<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class ActivityLogService
{
    public function log(
        Model $model,
        string $action,
        ?array $before,
        ?array $after,
        Request $request,
        array $meta = []
    ): void {
        ActivityLog::create([
            'loggable_type' => get_class($model),
            'loggable_id'   => $model->id,
            'loggable_code' => $model->asset_code ?? null, // KUNCI
            'action'        => strtoupper($action),
            'before_data'   => $before,
            'after_data'    => $after,
            'user_id'       => Auth::id(),
            'ip_address'    => $request->ip(),
            'meta'          => $meta,
        ]);
    }
}

