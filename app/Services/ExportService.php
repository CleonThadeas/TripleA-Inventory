<?php

namespace App\Services;

use App\Exports\AssetsExport;
use Maatwebsite\Excel\Facades\Excel;

class ExportService
{
    public function exportAssets(array $filters, string $format)
    {
        $filename = 'assets_export_' . now()->format('Ymd_His');

        return match ($format) {
            'csv'   => Excel::download(
                new AssetsExport($filters),
                "{$filename}.csv"
            ),
            'xlsx'  => Excel::download(
                new AssetsExport($filters),
                "{$filename}.xlsx"
            ),
            default => abort(400, 'Invalid export format'),
        };
    }
}
