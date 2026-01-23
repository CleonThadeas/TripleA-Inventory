<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Services\QrCodeService;

class QrCodeController extends Controller
{
    public function generate(
        Asset $asset,
        QrCodeService $qrCodeService
    ) {
        $this->authorize('update', $asset);

        $qrCodeService->generateForAsset($asset);

        return back()->with('success', 'QR Code berhasil dibuat');
    }
    public function scanView()
    {
        return view('assets.scan-qr');
    }
}
