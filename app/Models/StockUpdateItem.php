<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockUpdateItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'stock_update_id',
        'product_id',
        'product_name',
        'qty_added',
        'purchase_price',
        'stock_before',
        'stock_after',
        'price_before',
        'price_after',
    ];

    public function stockUpdate()
    {
        return $this->belongsTo(StockUpdate::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
