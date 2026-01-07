<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AssetPackageItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'asset_package_id',
        'category_id',
        'name',
    ];

    public function package()
    {
        return $this->belongsTo(AssetPackage::class, 'asset_package_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function components()
    {
        return $this->hasMany(AssetComponent::class)
            ->where('parent_type', 'package_item');
    }
}
