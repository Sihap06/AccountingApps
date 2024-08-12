<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Technician extends Model
{
    use HasFactory;
    protected $guarded = [];

    public static function getTechnicalName($id)
    {
        $technical = self::find($id);
        return $technical ? $technical->name : null;
    }
}
