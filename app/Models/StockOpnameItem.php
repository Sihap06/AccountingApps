<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockOpnameItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'stock_opname_id',
        'product_id',
        'product_name',
        'system_stock',
        'actual_stock',
        'difference',
        'unit_cost',
        'notes',
        'checked',
        'needs_recheck',
        'checked_at',
    ];

    protected $casts = [
        'checked' => 'boolean',
        'needs_recheck' => 'boolean',
        'checked_at' => 'datetime',
    ];

    public function stockOpname()
    {
        return $this->belongsTo(StockOpname::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class)->withTrashed();
    }

    /**
     * Monetary value of the counted difference (difference x cost price).
     * Falls back to the product's current cost for items counted before
     * unit_cost snapshotting existed. Negative = loss, positive = surplus.
     */
    public function getDifferenceValueAttribute()
    {
        if ($this->difference === null) {
            return null;
        }

        $cost = $this->unit_cost ?? $this->product?->harga;
        if ($cost === null) {
            return null;
        }

        return $this->difference * $cost;
    }
}
