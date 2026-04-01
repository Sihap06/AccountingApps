<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VerificationLog extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'snapshot_before' => 'array',
        'snapshot_after' => 'array',
    ];

    public function pendingChange()
    {
        return $this->belongsTo(PendingChange::class);
    }

    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function verifiable()
    {
        return $this->morphTo();
    }

    public static function logVerification(PendingChange $pendingChange, $action, $userId, $notes = null)
    {
        return static::create([
            'pending_change_id' => $pendingChange->id,
            'verifiable_type' => $pendingChange->changeable_type,
            'verifiable_id' => $pendingChange->changeable_id,
            'action' => $action,
            'verified_by' => $userId,
            'notes' => $notes,
            'snapshot_before' => $pendingChange->old_data,
            'snapshot_after' => $pendingChange->new_data,
        ]);
    }
}
