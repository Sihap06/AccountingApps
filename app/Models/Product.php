<?php

namespace App\Models;

use App\Traits\RequiresVerification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory, RequiresVerification;
    
    protected $fillable = ['name', 'kode', 'harga', 'harga_jual', 'stok'];
    
    // Flag to bypass verification for transaction stock updates
    public $bypassVerification = false;

    public function transactions()
    {
        return $this->belongsToMany(Transaction::class);
    }

    public function returns()
    {
        return $this->hasMany(ProductReturn::class);
    }

    public static function getProductName($id)
    {
        $product = self::find($id);
        return $product ? $product->name : null;
    }
}
