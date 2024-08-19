<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }

    public static function generateOrderId()
    {
        $prefix = 'INV';
        $lastOrder = self::where('order_transaction', 'like', $prefix . '%')
            ->orderBy('order_transaction', 'desc')
            ->first();

        if (!$lastOrder) {
            $newOrderId = $prefix . '0001';
        } else {
            $lastIncrement = (int) substr($lastOrder->order_transaction, strlen($prefix));
            $newIncrement = str_pad($lastIncrement + 1, 4, '0', STR_PAD_LEFT);
            $newOrderId = $prefix . $newIncrement;
        }

        return $newOrderId;
    }
}
