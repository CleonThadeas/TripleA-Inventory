<?php

namespace App\Services;

use App\Models\AssetComponent;
use Illuminate\Support\Facades\DB;

class ComponentService
{
    /**
     * Replace all components for a parent
     */
    public function sync(
        string $parentType,
        int $parentId,
        array $components
    ): void {
        DB::transaction(function () use (
            $parentType,
            $parentId,
            $components
        ) {
            AssetComponent::where('parent_type', $parentType)
                ->where('parent_id', $parentId)
                ->delete();

            foreach ($components as $key => $value) {
                AssetComponent::create([
                    'parent_type' => $parentType,
                    'parent_id' => $parentId,
                    'component_key' => $key,
                    'component_value' => $value,
                ]);
            }
        });
    }
}
