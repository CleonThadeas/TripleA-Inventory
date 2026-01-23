<?php

namespace App\Services;

use App\Exports\AssetsExport;
use App\Exports\ActivitiesExport;
use Maatwebsite\Excel\Facades\Excel;
class ExportService
{
    public function exportAssets(array $filters, string $format)
    {
        $filename = 'assets_export_' . now()->format('Ymd_His');

        return match ($format) {
            'csv'  => Excel::download(new AssetsExport($filters), "$filename.csv"),
            'xlsx' => Excel::download(new AssetsExport($filters), "$filename.xlsx"),
            default => abort(400, 'Invalid format'),
        };
    }


    public function exportActivities(array $filters, string $format)
    {
        $filename = 'activity_export_' . now()->format('Ymd_His');
    
        return match ($format) {
            'csv'  => Excel::download(new ActivitiesExport($filters), "$filename.csv"),
            'xlsx' => Excel::download(new ActivitiesExport($filters), "$filename.xlsx"),
            default => abort(400, 'Invalid export format'),
        };
    }
}
