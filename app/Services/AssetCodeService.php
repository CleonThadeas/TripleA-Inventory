<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Location;
use App\Models\Asset;

class AssetCodeService
{
    /**
     * Generate asset code:
     * CATEGORY-LOCATION-SERIAL-YEAR
     */
    public function generate(
        Category $category,
        Location $location,
        string $serialCode,
        int $purchaseYear
    ): string {
        return strtoupper(sprintf(
            '%s-%s-%s-%d',
            $category->code,
            $location->code,
            $serialCode,
            $purchaseYear
        ));
    }

    /**
     * Ensure uniqueness
     */
    public function generateUnique(
        Category $category,
        Location $location,
        string $serialCode,
        int $purchaseYear
    ): string {
        $code = $this->generate(
            $category,
            $location,
            $serialCode,
            $purchaseYear
        );

        if (Asset::where('asset_code', $code)->exists()) {
            throw new \RuntimeException(
                "Asset code already exists: {$code}"
            );
        }

        return $code;
    }
}
