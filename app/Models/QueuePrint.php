<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QueuePrint extends Model
{
    use HasFactory;
    protected $table = 'queue_print';
    protected $guarded = [];
}
