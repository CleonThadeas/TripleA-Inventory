<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Services\QrCodeService;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ApprovalController extends Controller
{
    protected QrCodeService $qrCodeService;
    protected ActivityLogService $activityLogService;

    public function __construct(
        QrCodeService $qrCodeService,
        ActivityLogService $activityLogService
    ) {
        $this->middleware(['auth', 'admin']);
        $this->qrCodeService = $qrCodeService;
        $this->activityLogService = $activityLogService;
    }

    /**
     * Approve Asset + Auto Generate QR Code
     */
    public function approve(Request $request, Asset $asset)
    {
        $this->authorize('approve', $asset);

        DB::transaction(function () use ($asset, $request) {

            // 1. Approve asset
            $asset->status = 'active';
            $asset->approved_by = Auth::id();
            $asset->approved_at = now();
            $asset->save();

            // 2. AUTO GENERATE QR CODE (jika belum ada)
            if (!$asset->qr_code_path) {
                $qrPath = $this->qrCodeService->generateForAsset($asset);
                $asset->qr_code_path = $qrPath;
                $asset->save();
            }

            // 3. Activity log
            $this->activityLogService->log(
                model: $asset,
                action: 'approve',
                before: null,
                after: $asset->fresh()->toArray(),
                request: $request
            );
        });

        return redirect()
            ->route('assets.view.show', $asset->asset_code)
            ->with('success', 'Asset berhasil disetujui dan QR Code dibuat.');
    }
}
