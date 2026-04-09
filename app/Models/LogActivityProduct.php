<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogActivityProduct extends Model
{
    use HasFactory;

    protected $table = 'log_activity_products';

    protected $fillable = [
        'user',
        'activity',
        'product',
        'old_name',
        'new_name',
        'old_price',
        'new_price',
        'old_stok',
        'new_stok',
    ];
}
