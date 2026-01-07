<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssetComponent extends Model
{
    protected $table = 'asset_components';

    protected $fillable = [
        'parent_type',
        'parent_id',
        'component_key',
        'component_value',
    ];
    

    public function asset()
    {
        return $this->belongsTo(Asset::class, 'parent_id')
            ->where('parent_type', 'asset');
    }
}
