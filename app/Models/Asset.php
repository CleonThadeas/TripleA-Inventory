<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\AssetComponent;

/**
 * @property int $id
 * @property string $asset_code
 * @property string $name
 * @property int $category_id
 * @property int $location_id
 * @property int $department_id
 * @property int|null $employee_id
 * @property int $purchase_year
 * @property string $brand
 * @property string $model
 * @property string|null $photo_path
 * @property string $status
 * @property int $created_by
 * @property int|null $approved_by
 * @property \Carbon\Carbon|null $approved_at
 */
class Asset extends Model
{
    use HasFactory;

    /* =====================
     | MASS ASSIGNMENT
     ===================== */

    /**
     * Gunakan SATU pendekatan saja: fillable
     * Jangan campur dengan guarded
     */
    protected $fillable = [
        'name',
        'category_id',
        'location_id',
        'department_id',
        'employee_name',
        'serial_code',
        'purchase_year',
        'brand',
        'model',
        'photo_path',
        'asset_code',
        'status',
        'created_by',
        'approved_by',
        'approved_at',
        'qr_code_path',
    ];
    
    

    /* =====================
     | CASTS
     ===================== */
    protected $casts = [
        'approved_at' => 'datetime',
    ];

    /* =====================
     | RELATIONSHIPS
     ===================== */

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }


    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Polymorphic components
     */
public function components()
{
    return $this->hasMany(AssetComponent::class, 'parent_id')
        ->where('parent_type', 'asset');
}

    /**
     * Activity log (audit trail)
     */
    public function activities()
    {
        return $this->morphMany(ActivityLog::class, 'loggable')
            ->latest();
    }

    /* =====================
     | HELPERS
     ===================== */

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isApproved(): bool
    {
        return $this->approved_at !== null;
    }
}
