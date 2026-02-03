<?php

namespace App\Services;

use App\Exports\AssetsExport;
use App\Exports\ActivityLogsExport;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ExportService
{
    /* =========================================================
     | EXPORT ASSETS
     | Single / Group / Both
     | 1 File = 1 Sheet
     ========================================================= */
    public function exportAssets(
        array $filters,
        string $exportType,   // single | group | both
        string $sheetMode,    // single (FIXED by decision)
        string $format        // xlsx | csv
    ): BinaryFileResponse {

        // 🔒 SAFETY GUARD
        if ($sheetMode !== 'single') {
            abort(422, 'Sheet mode tidak didukung.');
        }

        $fileName = 'asset_export_' . now()->format('Ymd_His');

        return Excel::download(
            new AssetsExport(
                filters: $filters,
                exportType: $exportType
            ),
            $fileName . '.' . $format
        );
    }

    /* =========================================================
     | EXPORT ACTIVITY LOG
     | Asset + Group
     | 1 File = 1 Sheet
     ========================================================= */
    public function exportActivities(
        array $filters,
        string $sheetMode,
        string $format
    ): BinaryFileResponse {

        // 🔒 SAFETY GUARD
        if ($sheetMode !== 'single') {
            abort(422, 'Sheet mode tidak didukung.');
        }

        $fileName = 'activity_log_' . now()->format('Ymd_His');

        return Excel::download(
            new ActivityLogsExport($filters),
            $fileName . '.' . $format
        );
    }
}
