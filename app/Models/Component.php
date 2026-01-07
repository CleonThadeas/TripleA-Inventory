<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Component extends Model
{
    protected $fillable = [
        'asset_id',
        'name',
        'specification',
        'status',
    ];

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }
}
