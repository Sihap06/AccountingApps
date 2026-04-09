<?php

namespace App\Models;

use App\Traits\RequiresVerification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use HasFactory, SoftDeletes, RequiresVerification;

    protected $guarded = [];
    
    // Flag to bypass verification for transaction creation
    public $bypassVerification = false;

    public function products()
    {
        return $this->belongsToMany(Product::class)->withTrashed();
    }

    public function product()
    {
        return $this->belongsTo(Product::class)->withTrashed();
    }
    
    public function transactionItems()
    {
        return $this->hasMany(TransactionItem::class);
    }

    public function items()
    {
        return $this->hasMany(TransactionItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Generate the next order_transaction ID safely.
     *
     * MUST be called inside a DB::transaction() so that lockForUpdate()
     * can hold the row and prevent concurrent requests from generating
     * the same ID. Sorting is numeric (CAST) so values past INV9999
     * still increment correctly. Soft-deleted records are included so
     * a deleted ID is never reused.
     */
    public static function generateOrderId(): string
    {
        $prefix = 'INV';
        $prefixLen = strlen($prefix);

        $lastOrder = self::withTrashed()
            ->where('order_transaction', 'like', $prefix . '%')
            ->orderByRaw('CAST(SUBSTRING(order_transaction, ' . ($prefixLen + 1) . ') AS UNSIGNED) DESC')
            ->lockForUpdate()
            ->first();

        $next = $lastOrder
            ? ((int) substr($lastOrder->order_transaction, $prefixLen)) + 1
            : 1;

        return $prefix . str_pad((string) $next, 4, '0', STR_PAD_LEFT);
    }
}
