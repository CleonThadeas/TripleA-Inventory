<?php

namespace App\Exports;

use App\Models\Asset;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AssetsExport implements FromCollection, WithHeadings
{
    public function __construct(
        protected array $filters
    ) {}

    public function collection()
    {
        $q = Asset::with([
            'category',
            'location',
            'department',
            'components'
        ]);

        if ($this->filters['status'] ?? false) {
            $q->where('status', $this->filters['status']);
        }

        if ($this->filters['category_id'] ?? false) {
            $q->where('category_id', $this->filters['category_id']);
        }

        if ($this->filters['location_id'] ?? false) {
            $q->where('location_id', $this->filters['location_id']);
        }

        if ($this->filters['department_id'] ?? false) {
            $q->where('department_id', $this->filters['department_id']);
        }

        if ($this->filters['year_from'] ?? false) {
            $q->where('purchase_year', '>=', $this->filters['year_from']);
        }

        if ($this->filters['year_to'] ?? false) {
            $q->where('purchase_year', '<=', $this->filters['year_to']);
        }

        return $q->get()->map(function ($a) {
            return [
                'Asset Code'   => $a->asset_code,
                'Name'         => $a->name,
                'Category'     => $a->category->name ?? '-',
                'Location'     => $a->location->name ?? '-',
                'Department'   => $a->department->name ?? '-',
                'Status'       => $a->status,
                'PurchaseYear' => $a->purchase_year,
                'Brand'        => $a->brand,
                'Model'        => $a->model,
                'Components'   => $a->components
                    ->map(fn($c) => "{$c->component_key}:{$c->component_value}")
                    ->implode(', ')
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
            'Status',
            'Purchase Year',
            'Brand',
            'Model',
            'Components',
        ];
    }
}
