<?php

namespace App\Models;

use App\Traits\RequiresVerification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes, RequiresVerification;
    
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

    /**
     * Get product name by ID, including soft-deleted products
     * so historical references still show the correct name.
     */
    public static function getProductName($id)
    {
        $product = self::withTrashed()->find($id);
        return $product ? $product->name : null;
    }
}
