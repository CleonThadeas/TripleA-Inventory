<?php

namespace App\Services;

use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;

class QrCodeService
{
    public function generateForAsset($asset): ?string
    {
        // VALIDASI WAJIB
        if (!$asset || empty($asset->asset_code)) {
            return null;
        }

        /**
         * ===============================
         * PENTING:
         * QR HANYA MENYIMPAN KODE ASSET
         * ===============================
         */
        $qrContent = $asset->asset_code;

        // PATH FILE QR
        $path = 'qr_codes/asset_' . $asset->asset_code . '.svg';

        // GENERATE QR (SVG TANPA IMAGICK)
        $qrSvg = QrCode::format('svg')
            ->size(300)
            ->margin(2)
            ->generate($qrContent);

        // SIMPAN KE STORAGE
        Storage::disk('public')->put($path, $qrSvg);

        // UPDATE DATABASE
        $asset->update([
            'qr_code_path' => $path,
        ]);

        return $path;
    }
}
