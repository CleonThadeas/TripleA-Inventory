<?php

namespace App\Services;

use App\Models\Asset;
use App\Models\User;
use App\Models\Category;
use App\Models\Location;
use Illuminate\Support\Facades\DB;

class AssetCreationService
{
    public function __construct(
        protected AssetCodeService $assetCodeService
    ) {}

    public function createSingleAsset(array $data, User $creator): Asset
    {
        return DB::transaction(function () use ($data, $creator) {

            // ✅ FIX: resolve variables FIRST
            $category = Category::findOrFail($data['category_id']);
            $location = Location::findOrFail($data['location_id']);

            // SERIAL CODE → ALWAYS UPPERCASE
            $serialCode = strtoupper($data['serial_code']);

            // Generate full asset code
            $assetCode = $this->assetCodeService->generateUnique(
                $category,
                $location,
                $serialCode,
                (int) $data['purchase_year']
            );

            $asset = new Asset([
                'name'          => $data['name'],
                'category_id'   => $data['category_id'],
                'location_id'   => $data['location_id'],
                'department_id' => $data['department_id'],
                'employee_name' => $data['employee_name'] ?? null,
                'serial_code'   => $serialCode,
                'purchase_year' => $data['purchase_year'],
                'brand'         => $data['brand'],
                'model'         => $data['model'],
                'photo_path'    => $data['photo_path'] ?? null,
            ]);

            $asset->asset_code = $assetCode;
            $asset->created_by = $creator->id;

            if ($creator->isAdmin()) {
                $asset->status = 'active';
                $asset->approved_by = $creator->id;
                $asset->approved_at = now();
            } else {
                $asset->status = 'pending';
            }

            $asset->save();

            return $asset;
        });
    }
}
