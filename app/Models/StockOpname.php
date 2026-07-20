<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class StockOpname extends Model
{
    use HasFactory;

    protected $fillable = [
        'triggered_by',
        'assigned_to',
        'completed_by',
        'status',
        'notes',
        'completed_at',
        'is_applied',
        'applied_by',
        'applied_at',
    ];

    protected $casts = [
        'completed_at' => 'datetime',
        'applied_at' => 'datetime',
        'is_applied' => 'boolean',
    ];

    public function triggeredBy()
    {
        return $this->belongsTo(User::class, 'triggered_by');
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function completedBy()
    {
        return $this->belongsTo(User::class, 'completed_by');
    }

    public function appliedBy()
    {
        return $this->belongsTo(User::class, 'applied_by');
    }

    public function items()
    {
        return $this->hasMany(StockOpnameItem::class);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    public function getHasDifferenceAttribute()
    {
        return $this->items()->where('difference', '!=', 0)->exists();
    }

    /**
     * Total value of missing stock (positive rupiah amount).
     * Kept separate from surplus so they never cancel each other out.
     */
    public function getLossValueAttribute()
    {
        return $this->items
            ->filter(fn ($item) => ($item->difference ?? 0) < 0)
            ->sum(fn ($item) => abs($item->difference_value ?? 0));
    }

    /**
     * Total value of excess stock found (positive rupiah amount).
     */
    public function getSurplusValueAttribute()
    {
        return $this->items
            ->filter(fn ($item) => ($item->difference ?? 0) > 0)
            ->sum(fn ($item) => $item->difference_value ?? 0);
    }

    public function applyAdjustment($appliedBy)
    {
        if ($this->status !== 'completed') {
            throw new \Exception('Stock opname must be completed before applying adjustment.');
        }

        if ($this->is_applied) {
            throw new \Exception('Adjustment has already been applied.');
        }

        return DB::transaction(function () use ($appliedBy) {
            $adjustments = [];

            foreach ($this->items()->where('difference', '!=', 0)->get() as $item) {
                // Delta-based: adjust the CURRENT stock by the counted difference so
                // transactions that happened after counting are preserved. Skips
                // soft-deleted products; locks the row against concurrent sales.
                $product = Product::whereKey($item->product_id)->lockForUpdate()->first();
                if (!$product) {
                    continue;
                }

                $oldStock = $product->stok;
                $newStock = max(0, $oldStock + $item->difference);

                $product->bypassVerification = true;
                $product->stok = $newStock;
                $product->save();

                $adjustments[] = [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'old_stock' => $oldStock,
                    'new_stock' => $newStock,
                    'difference' => $item->difference,
                ];
            }

            $this->update([
                'is_applied' => true,
                'applied_by' => $appliedBy,
                'applied_at' => now(),
            ]);

            return $adjustments;
        });
    }

    /**
     * Sync active opname items when a product's stock changes outside the opname:
     * unchecked items follow the new system stock, checked items are flagged for re-count.
     */
    public static function syncProductStockChange(Product $product)
    {
        $activeIds = static::active()->pluck('id');
        if ($activeIds->isEmpty()) {
            return;
        }

        StockOpnameItem::whereIn('stock_opname_id', $activeIds)
            ->where('product_id', $product->id)
            ->where('checked', false)
            ->update(['system_stock' => $product->stok]);

        StockOpnameItem::whereIn('stock_opname_id', $activeIds)
            ->where('product_id', $product->id)
            ->where('checked', true)
            ->where('needs_recheck', false)
            ->update(['needs_recheck' => true]);
    }

    /**
     * Include a newly created product in any active opname so it doesn't
     * escape counting.
     */
    public static function addProductToActive(Product $product)
    {
        foreach (static::active()->get() as $opname) {
            StockOpnameItem::firstOrCreate(
                [
                    'stock_opname_id' => $opname->id,
                    'product_id' => $product->id,
                ],
                [
                    'product_name' => $product->name,
                    'system_stock' => $product->stok,
                ]
            );
        }
    }

    public function scopeActive($query)
    {
        return $query->whereIn('status', ['pending', 'in_progress']);
    }

    public function getProgressAttribute()
    {
        $total = $this->items()->count();
        if ($total === 0) return 0;
        $checked = $this->items()->where('checked', true)->count();
        return round(($checked / $total) * 100);
    }
}
