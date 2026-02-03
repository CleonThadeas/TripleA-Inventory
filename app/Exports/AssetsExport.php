<?php

namespace App\Exports;

use App\Models\Asset;
use App\Models\AssetGroup;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AssetsExport implements FromCollection, WithHeadings
{
    public function __construct(
        protected array $filters,
        protected string $exportType // single | group | both
    ) {}

    public function collection(): Collection
    {
        $rows = collect();

        /*
        |--------------------------------------------------------------------------
        | SINGLE ASSET
        |--------------------------------------------------------------------------
        */
        if (in_array($this->exportType, ['single', 'both'])) {
            $assets = Asset::with([
                'category',
                'location',
                'department',
                'components',
                'groups'
            ])
            ->when($this->filters['status'] ?? null, fn($q, $v) => $q->where('status', $v))
            ->when($this->filters['category_id'] ?? null, fn($q, $v) => $q->where('category_id', $v))
            ->when($this->filters['location_id'] ?? null, fn($q, $v) => $q->where('location_id', $v))
            ->when($this->filters['department_id'] ?? null, fn($q, $v) => $q->where('department_id', $v))
            ->when($this->filters['year_from'] ?? null, fn($q, $v) => $q->where('purchase_year', '>=', $v))
            ->when($this->filters['year_to'] ?? null, fn($q, $v) => $q->where('purchase_year', '<=', $v))
            ->orderBy('created_at')
            ->get();

            foreach ($assets as $asset) {
                $rows->push($this->mapAssetRow(
                    asset: $asset,
                    groupName: null,
                    employee: null
                ));
            }
        }

        /*
        |--------------------------------------------------------------------------
        | ASSET GROUP
        |--------------------------------------------------------------------------
        */
        if (in_array($this->exportType, ['group', 'both'])) {
            $groups = AssetGroup::with([
                'assets.category',
                'assets.location',
                'assets.department',
                'assets.components'
            ])
            ->orderBy('created_at')
            ->get();

            foreach ($groups as $group) {
                foreach ($group->assets as $asset) {
                    $rows->push($this->mapAssetRow(
                        asset: $asset,
                        groupName: $group->name,
                        employee: $group->employee_name
                    ));
                }
            }
        }

        return $rows;
    }

    /*
    |--------------------------------------------------------------------------
    | MAP 1 ASSET → 1 ROW
    |--------------------------------------------------------------------------
    */
    protected function mapAssetRow(Asset $asset, ?string $groupName, ?string $employee): array
    {
        // Ambil semua component sebagai key => value
        $components = $asset->components
            ->pluck('component_value', 'component_key')
            ->toArray();

        return array_merge([
            // GROUP INFO
            'Group Name'        => $groupName ?? '-',
            'Group User'        => $employee ?? '-',

            // ASSET CORE
            'Asset Code'        => $asset->asset_code,
            'Asset Name'        => $asset->name,
            'Status'            => $asset->status,
            'Category'          => $asset->category->name ?? '-',
            'Location'          => $asset->location->name ?? '-',
            'Department'        => $asset->department->name ?? '-',

            // DETAIL
            'Brand'             => $asset->brand,
            'Model'             => $asset->model,
            'Serial Number'     => $asset->serial_number,
            'Purchase Year'     => $asset->purchase_year,
            'Purchase Price'    => $asset->purchase_price,
            'Condition'         => $asset->condition,
            'Notes'             => $asset->notes,

            // TIMESTAMP
            'Created At'        => $asset->created_at,
            'Updated At'        => $asset->updated_at,
        ], $components);
    }

    /*
    |--------------------------------------------------------------------------
    | HEADINGS
    |--------------------------------------------------------------------------
    */
    public function headings(): array
    {
        // HEADER STATIS + DINAMIS COMPONENT
        $componentKeys = Asset::with('components')
            ->get()
            ->pluck('components')
            ->flatten()
            ->pluck('component_key')
            ->unique()
            ->values()
            ->toArray();

        return array_merge([
            'Group Name',
            'Group User',
            'Asset Code',
            'Asset Name',
            'Status',
            'Category',
            'Location',
            'Department',
            'Brand',
            'Model',
            'Serial Number',
            'Purchase Year',
            'Purchase Price',
            'Condition',
            'Notes',
            'Created At',
            'Updated At',
        ], $componentKeys);
    }
}
