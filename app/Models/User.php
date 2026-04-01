<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
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

    /**
     * Dapatkan role user.
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Check if user has a specific permission by key.
     */
    public function hasPermission(string $key): bool
    {
        if ($this->isOwner()) {
            return true;
        }

        return $this->role && $this->role->permissions()->where('key', $key)->exists();
    }

    /**
     * Check if user is owner role.
     */
    public function isOwner(): bool
    {
        return $this->role && strtolower($this->role->name) === 'owner';
    }

    /**
     * Check if user is manajer role.
     */
    public function isManajer(): bool
    {
        return $this->role && strtolower($this->role->name) === 'manajer';
    }

    /**
     * Check if user is kasir role.
     */
    public function isKasir(): bool
    {
        return $this->role && strtolower($this->role->name) === 'kasir';
    }

    /**
     * Check if user role requires verification for data changes.
     */
    public function requiresVerification(): bool
    {
        return $this->role && in_array(strtolower($this->role->name), ['kasir', 'manajer']);
    }

    /**
     * Get transactions created by this user.
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'created_by');
    }

    /**
     * Get stock updates by this user.
     */
    public function stockUpdates()
    {
        return $this->hasMany(StockUpdate::class);
    }

    /**
     * Check if user has related data that prevents deletion.
     * Returns array of relation names that have data, or empty array if safe to delete.
     */
    public function getRelatedDataInfo(): array
    {
        $relations = [];

        if ($this->transactions()->exists()) {
            $relations[] = 'Transaksi (' . $this->transactions()->count() . ')';
        }

        if ($this->stockUpdates()->exists()) {
            $relations[] = 'Stock Update (' . $this->stockUpdates()->count() . ')';
        }

        return $relations;
    }

    /**
     * Check if user has any related data.
     */
    public function hasRelatedData(): bool
    {
        return !empty($this->getRelatedDataInfo());
    }
}
