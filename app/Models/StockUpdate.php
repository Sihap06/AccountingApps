<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockUpdate extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nota_image',
        'notes',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(StockUpdateItem::class);
    }
}
