<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AssetPackage;


class ApprovalController extends Controller
{
    public function approve(Asset $asset)
    {
        // Hanya asset pending
        $before = $asset->fresh()->toArray();

        $asset->update([
            'status'      => 'active',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);
        
        $after = $asset->fresh()->toArray();
        
        app(\App\Services\ActivityLogService::class)->log(
            $asset,
            'approve',
            $before,
            $after,
            request()
        );
        
        return redirect()
            ->route('assets.view.show', $asset->id)
            ->with('success', 'Asset approved successfully.');
    }

    public function reject(Asset $asset)
    {
        $before = $asset->fresh()->toArray();

        $asset->update([
            'status'      => 'rejected',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);
        
        $after = $asset->fresh()->toArray();
        
        app(\App\Services\ActivityLogService::class)->log(
            $asset,
            'reject',
            $before,
            $after,
            request()
        );
        

        return redirect()
            ->route('assets.view.show', $asset->id)
            ->with('success', 'Asset rejected.');
    }

    public function packageIndex()
{
    $this->authorize('approve', AssetPackage::class);

    $packages = AssetPackage::where('status', 'pending')
        ->with(['department', 'location', 'employee', 'creator'])
        ->orderBy('created_at')
        ->get();

    return view('approval.packages', [
        'packages' => $packages
    ]);
}

}
