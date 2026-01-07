<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssetPackage extends Model
{
    protected $fillable = [
        'name',
        'department_id',
        'location_id',
        'employee_id',
        'status',
        'created_by',
        'approved_by',
        'approved_at',
    ];

    public function items()
    {
        return $this->hasMany(AssetPackageItem::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function activities()
    {
        return $this->morphMany(ActivityLog::class, 'loggable')
            ->latest();
    }
}
