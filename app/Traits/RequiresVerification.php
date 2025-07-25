<?php

namespace App\Traits;

use App\Models\PendingChange;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

trait RequiresVerification
{
    protected static function bootRequiresVerification()
    {
        static::creating(function ($model) {
            // Skip verification if bypass flag is set
            if (property_exists($model, 'bypassVerification') && $model->bypassVerification) {
                return true;
            }
            
            Log::info('RequiresVerification: Creating event triggered', [
                'model' => get_class($model),
                'user_role' => Auth::check() ? Auth::user()->role : 'not logged in',
                'user_id' => Auth::check() ? Auth::user()->id : null
            ]);
            
            if (Auth::check() && Auth::user()->role === 'sysadmin') {
                $model->interceptCreate();
                return false;
            }
        });

        static::updating(function ($model) {
            // Skip verification if bypass flag is set
            if (property_exists($model, 'bypassVerification') && $model->bypassVerification) {
                return true;
            }
            
            Log::info('RequiresVerification: Updating event triggered', [
                'model' => get_class($model),
                'user_role' => Auth::check() ? Auth::user()->role : 'not logged in',
                'user_id' => Auth::check() ? Auth::user()->id : null
            ]);
            
            if (Auth::check() && Auth::user()->role === 'sysadmin') {
                $model->interceptUpdate();
                return false;
            }
        });

        static::deleting(function ($model) {
            // Skip verification if bypass flag is set
            if (property_exists($model, 'bypassVerification') && $model->bypassVerification) {
                return true;
            }
            
            Log::info('RequiresVerification: Deleting event triggered', [
                'model' => get_class($model),
                'user_role' => Auth::check() ? Auth::user()->role : 'not logged in',
                'user_id' => Auth::check() ? Auth::user()->id : null
            ]);
            
            if (Auth::check() && Auth::user()->role === 'sysadmin') {
                $model->interceptDelete();
                return false;
            }
        });
    }

    protected function interceptCreate()
    {
        $pendingChange = PendingChange::create([
            'changeable_type' => get_class($this),
            'changeable_id' => null,
            'action' => 'create',
            'old_data' => null,
            'new_data' => $this->getAttributes(),
            'requested_by' => Auth::id(),
            'requested_at' => now(),
        ]);

        // Create model-specific activity log
        $this->createActivityLog('create', null, $this->getAttributes());

        Log::info('RequiresVerification: Pending change created for new record', [
            'model' => get_class($this),
            'pending_change_id' => $pendingChange->id,
            'user' => Auth::user()->name
        ]);
    }

    protected function interceptUpdate()
    {
        $original = $this->getOriginal();
        $changes = $this->getDirty();

        if (!empty($changes)) {
            $pendingChange = PendingChange::create([
                'changeable_type' => get_class($this),
                'changeable_id' => $this->getKey(),
                'action' => 'update',
                'old_data' => $original,
                'new_data' => array_merge($original, $changes),
                'requested_by' => Auth::id(),
                'requested_at' => now(),
            ]);

            // Create model-specific activity log
            $this->createActivityLog('update', $original, array_merge($original, $changes));

            Log::info('RequiresVerification: Pending change created for update', [
                'model' => get_class($this),
                'model_id' => $this->getKey(),
                'pending_change_id' => $pendingChange->id,
                'changes' => array_keys($changes),
                'user' => Auth::user()->name
            ]);
        }
    }

    protected function interceptDelete()
    {
        $pendingChange = PendingChange::create([
            'changeable_type' => get_class($this),
            'changeable_id' => $this->getKey(),
            'action' => 'delete',
            'old_data' => $this->getOriginal(),
            'new_data' => null,
            'requested_by' => Auth::id(),
            'requested_at' => now(),
        ]);

        // Create model-specific activity log
        $this->createActivityLog('delete', $this->getOriginal(), null);

        Log::info('RequiresVerification: Pending change created for delete', [
            'model' => get_class($this),
            'model_id' => $this->getKey(),
            'pending_change_id' => $pendingChange->id,
            'user' => Auth::user()->name
        ]);
    }

    public function pendingChanges()
    {
        return $this->morphMany(PendingChange::class, 'changeable');
    }

    public function hasPendingChanges()
    {
        return $this->pendingChanges()->pending()->exists();
    }

    public function latestPendingChange()
    {
        return $this->pendingChanges()->pending()->latest()->first();
    }

    public static function withPendingData($query = null)
    {
        $query = $query ?: static::query();
        
        return $query->with(['pendingChanges' => function ($q) {
            $q->pending()->latest();
        }]);
    }

    public function getPendingDataAttribute()
    {
        $pendingChange = $this->latestPendingChange();
        
        if ($pendingChange && $pendingChange->action === 'update') {
            return $pendingChange->new_data;
        }
        
        return null;
    }

    public function getEffectiveDataAttribute()
    {
        $pendingData = $this->pending_data;
        
        if ($pendingData) {
            return array_merge($this->toArray(), $pendingData);
        }
        
        return $this->toArray();
    }

    /**
     * Create model-specific activity log
     */
    protected function createActivityLog($activity, $oldData = null, $newData = null)
    {
        $modelClass = get_class($this);
        $userName = Auth::user()->name;

        switch ($modelClass) {
            case 'App\Models\Product':
                $this->createProductActivityLog($activity, $oldData, $newData, $userName);
                break;
                
            case 'App\Models\Expenditure':
                $this->createExpenditureActivityLog($activity, $oldData, $newData, $userName);
                break;
                
            case 'App\Models\Transaction':
                $this->createTransactionActivityLog($activity, $oldData, $newData, $userName);
                break;
                
            default:
                Log::info('RequiresVerification: No specific activity log for model', [
                    'model' => $modelClass,
                    'activity' => $activity
                ]);
        }
    }

    /**
     * Create Product activity log
     */
    protected function createProductActivityLog($activity, $oldData, $newData, $userName)
    {
        $log = new \App\Models\LogActivityProduct();
        $log->user = $userName;
        $log->activity = $activity . ' (pending)';
        
        if ($activity === 'create' && $newData) {
            $log->product = $newData['name'] ?? '';
            $log->new_name = $newData['name'] ?? '';
            $log->new_price = $newData['harga'] ?? '';
            $log->new_stok = $newData['stok'] ?? '';
        } elseif ($activity === 'update' && $oldData && $newData) {
            $log->product = $oldData['name'] ?? '';
            
            if (($oldData['name'] ?? '') !== ($newData['name'] ?? '')) {
                $log->old_name = $oldData['name'];
                $log->new_name = $newData['name'];
            }
            
            if (($oldData['harga'] ?? '') !== ($newData['harga'] ?? '')) {
                $log->old_price = $oldData['harga'];
                $log->new_price = $newData['harga'];
            }
            
            if (($oldData['stok'] ?? '') !== ($newData['stok'] ?? '')) {
                $log->old_stok = $oldData['stok'];
                $log->new_stok = $newData['stok'];
            }
        } elseif ($activity === 'delete' && $oldData) {
            $log->product = $oldData['name'] ?? '';
        }
        
        $log->save();
    }

    /**
     * Create Expenditure activity log
     */
    protected function createExpenditureActivityLog($activity, $oldData, $newData, $userName)
    {
        $log = new \App\Models\LogActivityExpenditure();
        $log->user = $userName;
        $log->activity = $activity . ' (pending)';
        
        if ($activity === 'create' && $newData) {
            $log->jenis = $newData['jenis'] ?? '';
            $log->new_jenis = $newData['jenis'] ?? '';
            $log->new_tanggal = $newData['tanggal'] ?? '';
            $log->new_total = $newData['total'] ?? '';
        } elseif ($activity === 'update' && $oldData && $newData) {
            $log->jenis = $oldData['jenis'] ?? '';
            
            if (($oldData['jenis'] ?? '') !== ($newData['jenis'] ?? '')) {
                $log->old_jenis = $oldData['jenis'];
                $log->new_jenis = $newData['jenis'];
            }
            
            if (($oldData['tanggal'] ?? '') !== ($newData['tanggal'] ?? '')) {
                $log->old_tanggal = $oldData['tanggal'];
                $log->new_tanggal = $newData['tanggal'];
            }
            
            if (($oldData['total'] ?? '') !== ($newData['total'] ?? '')) {
                $log->old_total = $oldData['total'];
                $log->new_total = $newData['total'];
            }
        } elseif ($activity === 'delete' && $oldData) {
            $log->jenis = $oldData['jenis'] ?? '';
            $log->old_jenis = $oldData['jenis'] ?? '';
            $log->old_tanggal = $oldData['tanggal'] ?? '';
            $log->old_total = $oldData['total'] ?? '';
        }
        
        $log->save();
    }

    /**
     * Create Transaction activity log
     */
    protected function createTransactionActivityLog($activity, $oldData, $newData, $userName)
    {
        $log = new \App\Models\LogActivityTransaction();
        $log->user = $userName;
        $log->activity = $activity . ' (pending)';
        $log->order_transaction = $oldData['order_transaction'] ?? $newData['order_transaction'] ?? '';
        
        if ($activity === 'update' && $oldData && $newData) {
            if (($oldData['customer_id'] ?? '') !== ($newData['customer_id'] ?? '')) {
                $oldCustomer = \App\Models\Customer::find($oldData['customer_id']);
                $newCustomer = \App\Models\Customer::find($newData['customer_id']);
                $log->old_customer = $oldCustomer ? $oldCustomer->name : '';
                $log->new_customer = $newCustomer ? $newCustomer->name : '';
            }
            
            if (($oldData['payment_method'] ?? '') !== ($newData['payment_method'] ?? '')) {
                $log->old_payment_method = $oldData['payment_method'];
                $log->new_payment_method = $newData['payment_method'];
            }
            
            if (($oldData['created_at'] ?? '') !== ($newData['created_at'] ?? '')) {
                $log->old_tanggal = $oldData['created_at'] ? \Carbon\Carbon::parse($oldData['created_at'])->format('Y-m-d') : '';
                $log->new_tanggal = $newData['created_at'] ? \Carbon\Carbon::parse($newData['created_at'])->format('Y-m-d') : '';
            }
        }
        
        $log->save();
    }
}