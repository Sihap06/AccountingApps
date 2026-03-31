<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'name',
        'group',
        'sort_order',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_permissions');
    }
}
