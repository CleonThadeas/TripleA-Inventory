<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'code'];

    protected static function booted()
    {
        static::saving(function ($category) {
            $category->code = strtoupper($category->code);
        });
    }

    public function assets()
    {
        return $this->hasMany(Asset::class);
    }

    public function packageItems()
    {
        return $this->hasMany(AssetPackageItem::class);
    }
}
