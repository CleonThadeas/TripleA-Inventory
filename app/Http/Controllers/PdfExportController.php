<?php
namespace App\Http\Controllers;

use App\Models\Asset;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\AssetPackage;
class PdfExportController extends Controller
{
    public function asset(Asset $asset)
    {
        $asset->load([
            'category',
            'location',
            'department',
            'employee',
            'creator',
            'approver',
            'components',
            'activities.user'
        ]);

        $pdf = Pdf::loadView('pdf.asset', [
            'asset' => $asset
        ]);

        return $pdf->download(
            'asset-' . $asset->asset_code . '.pdf'
        );
    }

    public function package(AssetPackage $package)
    {
        $package->load([
            'department',
            'location',
            'employee',
            'creator',
            'approver',
            'items.category',
            'items.components',
            'activities.user'
        ]);
    
        $pdf = Pdf::loadView('pdf.package', [
            'package' => $package
        ]);
    
        return $pdf->download(
            'package-' . $package->id . '.pdf'
        );
    }
    
}
