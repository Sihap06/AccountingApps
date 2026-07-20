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
    
    // Create inventory does not need pending changes, but updates and deletes do.
    public $verifyCreate = false;
    public $verifyUpdate = true;
    public $verifyDelete = true;

    protected static function booted()
    {
        // Keep active stock opnames in sync with real stock movements
        // (sales, returns, restock) that happen while an opname is running.
        static::updated(function (Product $product) {
            if ($product->wasChanged('stok')) {
                StockOpname::syncProductStockChange($product);
            }
        });

        static::created(function (Product $product) {
            StockOpname::addProductToActive($product);
        });
    }

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
