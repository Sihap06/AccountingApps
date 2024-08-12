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
        $date = date('Ymd');
        $lastOrder = self::where('order_transaction', 'like', $date . '%')->orderBy('order_transaction', 'desc')->first();

        if (!$lastOrder) {
            $newOrderId = $date . '00001';
        } else {
            $lastIncrement = (int) substr($lastOrder->order_transaction, -5);
            $newIncrement = str_pad($lastIncrement + 1, 5, '0', STR_PAD_LEFT);
            $newOrderId = $date . $newIncrement;
        }

        return $newOrderId;
    }
}
