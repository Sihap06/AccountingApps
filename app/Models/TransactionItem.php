<?php

namespace App\Models;

use App\Traits\RequiresVerification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransactionItem extends Model
{
    use HasFactory, SoftDeletes, RequiresVerification;
    
    protected $table = 'transaction_items';
    protected $guarded = [];
    
    // Flag to bypass verification
    public $bypassVerification = false;
}
