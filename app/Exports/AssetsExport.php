<?php

namespace App\Exports;

use App\Models\Asset;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class AssetsExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    public function __construct(
        protected array $filters = []
    ) {}

    public function collection(): Collection
    {
        $query = Asset::query()
            ->with(['category', 'location', 'department', 'employee']);

        // 🔹 FILTERS
        if (!empty($this->filters['status'])) {
            $query->where('status', $this->filters['status']);
        }

        if (!empty($this->filters['category_id'])) {
            $query->where('category_id', $this->filters['category_id']);
        }

        if (!empty($this->filters['location_id'])) {
            $query->where('location_id', $this->filters['location_id']);
        }

        if (!empty($this->filters['department_id'])) {
            $query->where('department_id', $this->filters['department_id']);
        }

        if (!empty($this->filters['year_from'])) {
            $query->where('purchase_year', '>=', $this->filters['year_from']);
        }

        if (!empty($this->filters['year_to'])) {
            $query->where('purchase_year', '<=', $this->filters['year_to']);
        }

        return $query->get()->map(function (Asset $asset) {
            return [
                'Asset Code'     => $asset->asset_code,
                'Name'           => $asset->name,
                'Category'       => $asset->category->name ?? '-',
                'Location'       => $asset->location->name ?? '-',
                'Department'     => $asset->department->name ?? '-',
                'Employee'       => $asset->employee->name ?? '-',
                'Purchase Year'  => $asset->purchase_year,
                'Brand'          => $asset->brand,
                'Model'          => $asset->model,
                'Status'         => $asset->status,
                'Created At'     => $asset->created_at->format('Y-m-d'),
                'Approved At'    => optional($asset->approved_at)?->format('Y-m-d'),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Asset Code',
            'Name',
            'Category',
            'Location',
            'Department',
            'Employee',
            'Purchase Year',
            'Brand',
            'Model',
            'Status',
            'Created At',
            'Approved At',
        ];
    }
}
