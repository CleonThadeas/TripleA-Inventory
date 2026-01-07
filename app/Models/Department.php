<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function assets()
    {
        return $this->hasMany(Asset::class);
    }

    public function assetPackages()
    {
        return $this->hasMany(AssetPackage::class);
    }
}
