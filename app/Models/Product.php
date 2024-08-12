<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    public function transactions()
    {
        return $this->belongsToMany(Transaction::class);
    }

    public static function getProductName($id)
    {
        $product = self::find($id);
        return $product ? $product->name : null;
    }
}
