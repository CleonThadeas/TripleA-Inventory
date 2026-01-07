<?php

namespace App\Services;

use App\Models\Asset;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrCodeService
{
    /**
     * Generate or regenerate QR code for an asset
     */
    public function generateForAsset(Asset $asset): string
    {
        if (empty($asset->asset_code)) {
            throw new \RuntimeException('Asset code not found.');
        }

        $directory = 'public/qrcodes/assets';
        $filename  = $asset->asset_code . '.png';
        $path      = $directory . '/' . $filename;

        // Pastikan direktori ada
        Storage::makeDirectory($directory);

        // Payload QR (audit-friendly)
        $payload = $asset->full_asset_code;

        // Generate QR code (PNG binary)
        $qrPng = QrCode::format('png')
            ->size(300)
            ->margin(2)
            ->generate($payload);

        // Simpan ke storage
        Storage::put($path, $qrPng);

        // Return public URL
        return Storage::url($path); // /storage/qrcodes/assets/XXX.png
    }
}
