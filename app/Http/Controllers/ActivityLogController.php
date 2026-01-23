<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ActivityLog;
/**
 * @method $this middleware(array|string $middleware)
 * @method void authorize(string $ability, mixed $arguments = [])
 */

class ActivityLogController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function byAsset(string $type, int $id)
    {
        $map = [
            'asset'   => \App\Models\Asset::class,
            'package' => \App\Models\AssetPackage::class,
        ];

        abort_unless(isset($map[$type]), 400);

        $logs = ActivityLog::where('subject_type', $map[$type])
            ->where('subject_id', $id)
            ->latest()
            ->get();

        return response()->json($logs);
    }
    
public function exportForm()
{
    return view('export.activity', [
        'users' => User::all(),
    ]);
}
// App\Http\Controllers\ActivityLogController.php

public function recent()
{
    return view('activity.recent', [
        'activities' => ActivityLog::with(['user', 'loggable'])
            ->latest()
            ->limit(50)
            ->get()
    ]);
}

}
