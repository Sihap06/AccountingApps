<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public function applyAdjustment($appliedBy)
    {
        if ($this->status !== 'completed') {
            throw new \Exception('Stock opname must be completed before applying adjustment.');
        }

        if ($this->is_applied) {
            throw new \Exception('Adjustment has already been applied.');
        }

        $adjustments = [];

        foreach ($this->items()->where('difference', '!=', 0)->get() as $item) {
            $product = $item->product;
            if ($product) {
                $oldStock = $product->stok;
                $newStock = $item->actual_stock;

                // Update product stock
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
        }

        $this->update([
            'is_applied' => true,
            'applied_by' => $appliedBy,
            'applied_at' => now(),
        ]);

        return $adjustments;
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
