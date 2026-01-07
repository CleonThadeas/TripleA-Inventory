<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /* =====================
     | ROLE HELPERS
     ===================== */
     public function isAdmin(): bool
     {
         return $this->role === 'admin';
     }
     
 
     public function isStaff(): bool
     {
         return $this->role === 'staff';
     }

    /* =====================
     | RELATIONSHIPS
     ===================== */
    public function createdAssets()
    {
        return $this->hasMany(Asset::class, 'created_by');
    }

    public function approvedAssets()
    {
        return $this->hasMany(Asset::class, 'approved_by');
    }

    public function assetPackages()
    {
        return $this->hasMany(AssetPackage::class, 'created_by');
    }
}
