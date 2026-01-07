<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Services\QrCodeService;
use Illuminate\Http\JsonResponse;

class QrCodeController extends Controller
{
    public function __construct(
        protected QrCodeService $qrCodeService
    ) {
        $this->middleware(['auth', 'admin']);
    }

    /**
     * Generate QR code for an asset (manual trigger)
     */
    public function generate(Asset $asset): JsonResponse
    {
        $url = $this->qrCodeService->generateForAsset($asset);

        $asset->update([
            'qr_code_path' => $url,
        ]);

        return response()->json([
            'message' => 'QR code generated successfully',
            'qr_url'  => $url,
        ]);
    }
}
