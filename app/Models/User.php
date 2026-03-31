<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'user_permissions');
    }

    /**
     * Check if user has a specific permission by key.
     * Owner role always has all permissions.
     */
    public function hasPermission(string $key): bool
    {
        if ($this->role === 'owner') {
            return true;
        }

        return $this->permissions()->where('key', $key)->exists();
    }

    /**
     * Check if user is owner role.
     */
    public function isOwner(): bool
    {
        return $this->role === 'owner';
    }

    /**
     * Check if user is manajer role.
     */
    public function isManajer(): bool
    {
        return $this->role === 'manajer';
    }

    /**
     * Check if user is kasir role.
     */
    public function isKasir(): bool
    {
        return $this->role === 'kasir';
    }

    /**
     * Check if user role requires verification for data changes.
     */
    public function requiresVerification(): bool
    {
        return in_array($this->role, ['kasir', 'manajer']);
    }

    public static function availableRoles(): array
    {
        return [
            'kasir' => 'Kasir',
            'manajer' => 'Manajer',
            'owner' => 'Owner',
        ];
    }
}
