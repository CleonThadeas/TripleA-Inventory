<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Class User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $role
 *
 * @method bool isAdmin()
 * @method bool isStaff()
 * @method bool isSuperAdmin()
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /* =====================================================
     | MASS ASSIGNMENT
     ===================================================== */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /* =====================================================
     | HIDDEN ATTRIBUTES
     ===================================================== */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /* =====================================================
     | CASTS
     ===================================================== */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /* =====================================================
     | ROLE HELPERS (STATIC-ANALYSIS SAFE)
     ===================================================== */

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isStaff(): bool
    {
        return $this->role === 'staff';
    }

    public function isSuperAdmin(): bool
    {
        return $this->email === 'admin@inventory.local';
    }

    /* =====================================================
     | RELATIONSHIPS
     ===================================================== */

    public function createdAssets()
    {
        return $this->hasMany(Asset::class, 'created_by');
    }

    public function approvedAssets()
    {
        return $this->hasMany(Asset::class, 'approved_by');
    }
}
