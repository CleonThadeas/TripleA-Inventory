<?php

namespace App\Services;

use App\Models\Asset;
use App\Models\ActivityLog;
use Barryvdh\DomPDF\Facade\Pdf;

class PdfExportService
{
    public function exportAssetsWithActivity(array $filters = [])
    {
        $assetQuery = Asset::with(['category','location','department','employee']);

        if (!empty($filters['status'])) {
            $assetQuery->where('status', $filters['status']);
        }

        $assets = $assetQuery->get();

        $activities = ActivityLog::whereIn('subject_id', $assets->pluck('id'))
            ->where('subject_type', Asset::class)
            ->latest()
            ->get();

        return Pdf::loadView('pdf.assets', [
            'assets'     => $assets,
            'activities' => $activities,
        ])->download(
            'asset_activity_report_' . now()->format('Ymd_His') . '.pdf'
        );
    }
}
