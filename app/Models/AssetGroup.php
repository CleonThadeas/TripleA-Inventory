<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssetGroup extends Model
{
    protected $fillable = [
        'name',
        'description',
        'employee_name',
        'created_by'
        
    ];

    public function assets()
    {
        return $this->belongsToMany(Asset::class, 'asset_group_asset');
        return $this->belongsToMany(Asset::class);
    }

public function createdBy()
{
    return $this->belongsTo(User::class, 'created_by');
}

public function approvedBy()
{
    return $this->belongsTo(User::class, 'approved_by');
}

// 🔥 CREATOR (INI YANG HILANG)
public function creator()
{
    return $this->belongsTo(User::class, 'created_by');
}

}
