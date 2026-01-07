<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'loggable_type',
        'loggable_id',
        'action',
        'before_data',
        'after_data',
        'user_id',
        'ip_address',
    ];

    protected $casts = [
        'before_data' => 'array',
        'after_data'  => 'array',
    ];

    public function loggable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
