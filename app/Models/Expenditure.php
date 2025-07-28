<?php

namespace App\Models;

use App\Traits\RequiresVerification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Expenditure extends Model
{
    use HasFactory, RequiresVerification;

    protected $guarded = [];

    /**
     * Get the full URL for the image
     */
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return Storage::url($this->image);
        }
        return null;
    }

    /**
     * Delete the image file when the model is deleted
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($expenditure) {
            if ($expenditure->image) {
                Storage::delete($expenditure->image);
            }
        });
    }
}
