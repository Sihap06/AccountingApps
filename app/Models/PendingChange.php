<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PendingChange extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'old_data' => 'array',
        'new_data' => 'array',
        'requested_at' => 'datetime',
        'verified_at' => 'datetime',
        'applied_at' => 'datetime',
    ];

    public function changeable()
    {
        return $this->morphTo();
    }

    public function requestedBy()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function verificationLogs()
    {
        return $this->hasMany(VerificationLog::class);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isApproved()
    {
        return $this->status === 'approved';
    }

    public function isRejected()
    {
        return $this->status === 'rejected';
    }

    public function approve($userId, $notes = null)
    {
        $this->status = 'approved';
        $this->verified_by = $userId;
        $this->verified_at = now();
        $this->verification_notes = $notes;
        $this->save();

        return $this;
    }

    public function reject($userId, $notes = null)
    {
        $this->status = 'rejected';
        $this->verified_by = $userId;
        $this->verified_at = now();
        $this->verification_notes = $notes;
        $this->save();

        return $this;
    }

    public function applyChange()
    {
        if (!$this->isApproved()) {
            throw new \Exception('Cannot apply unapproved changes');
        }

        $modelClass = $this->changeable_type;

        switch ($this->action) {
            case 'create':
                $model = new $modelClass($this->new_data);
                $model->save();
                $this->changeable_id = $model->id;

                // Create activity log for successful creation
                $this->createActivityLogForAppliedChange('store', null, $this->new_data);
                break;

            case 'update':
                $model = $modelClass::find($this->changeable_id);
                if ($model) {
                    // Special handling for Transaction updates with product changes
                    if (
                        $modelClass === 'App\Models\Transaction' &&
                        isset($this->old_data['product_id']) &&
                        isset($this->new_data['product_id']) &&
                        $this->old_data['product_id'] != $this->new_data['product_id']
                    ) {

                        // Return old product to stock
                        if ($this->old_data['product_id']) {
                            $oldProduct = \App\Models\Product::find($this->old_data['product_id']);
                            if ($oldProduct) {
                                $oldProduct->bypassVerification = true;
                                $oldProduct->stok = $oldProduct->stok + 1;
                                $oldProduct->save();
                            }
                        }

                        // Deduct new product from stock
                        if ($this->new_data['product_id']) {
                            $newProduct = \App\Models\Product::find($this->new_data['product_id']);
                            if ($newProduct) {
                                $newProduct->bypassVerification = true;
                                $newProduct->stok = $newProduct->stok - 1;
                                $newProduct->save();
                            }
                        }
                    }

                    // Special handling for TransactionItem updates with product changes
                    if (
                        $modelClass === 'App\Models\TransactionItem' &&
                        isset($this->old_data['product_id']) &&
                        isset($this->new_data['product_id']) &&
                        $this->old_data['product_id'] != $this->new_data['product_id']
                    ) {

                        // Return old product to stock
                        if ($this->old_data['product_id']) {
                            $oldProduct = \App\Models\Product::find($this->old_data['product_id']);
                            if ($oldProduct) {
                                $oldStock = $oldProduct->stok;
                                $oldProduct->bypassVerification = true;
                                $oldProduct->stok = $oldProduct->stok + 1;
                                $oldProduct->save();

                                // Log stock return
                                $log = new \App\Models\LogActivityProduct();
                                $log->user = $this->verifiedBy ? $this->verifiedBy->name : 'System';
                                $log->activity = 'update';
                                $log->product = $oldProduct->name;
                                $log->old_stok = $oldStock;
                                $log->new_stok = $oldProduct->stok;
                                $log->save();
                            }
                        }

                        // Deduct new product from stock
                        if ($this->new_data['product_id']) {
                            $newProduct = \App\Models\Product::find($this->new_data['product_id']);
                            if ($newProduct) {
                                $oldStock = $newProduct->stok;
                                $newProduct->bypassVerification = true;
                                $newProduct->stok = $newProduct->stok - 1;
                                $newProduct->save();

                                // Log stock usage
                                $log = new \App\Models\LogActivityProduct();
                                $log->user = $this->verifiedBy ? $this->verifiedBy->name : 'System';
                                $log->activity = 'update';
                                $log->product = $newProduct->name;
                                $log->old_stok = $oldStock;
                                $log->new_stok = $newProduct->stok;
                                $log->save();
                            }
                        }
                    }

                    $model->fill($this->new_data);
                    $model->save();

                    // Create activity log for successful update
                    $this->createActivityLogForAppliedChange('update', $this->old_data, $this->new_data);
                }
                break;

            case 'delete':
                $model = $modelClass::find($this->changeable_id);
                if ($model) {
                    // Special handling for Transaction deletions
                    if ($modelClass === 'App\Models\Transaction') {
                        // Return product to stock
                        if ($model->product_id) {
                            $product = \App\Models\Product::find($model->product_id);
                            if ($product) {
                                $product->bypassVerification = true;
                                $product->stok = $product->stok + 1;
                                $product->save();
                            }
                        }

                        // Handle transaction items
                        $transactionItems = \App\Models\TransactionItem::where('transaction_id', $model->id)->get();
                        foreach ($transactionItems as $item) {
                            if ($item->product_id) {
                                $product_item = \App\Models\Product::find($item->product_id);
                                if ($product_item) {
                                    $product_item->bypassVerification = true;
                                    $product_item->stok = $product_item->stok + 1;
                                    $product_item->save();
                                }
                            }
                            $item->delete();
                        }
                    }

                    // Special handling for TransactionItem deletions
                    if ($modelClass === 'App\Models\TransactionItem') {
                        // Return product to stock if product was used
                        if ($model->product_id) {
                            $product = \App\Models\Product::find($model->product_id);
                            if ($product) {
                                $oldStock = $product->stok;
                                $product->bypassVerification = true;
                                $product->stok = $product->stok + 1;
                                $product->save();

                                // Log stock return
                                $log = new \App\Models\LogActivityProduct();
                                $log->user = $this->verifiedBy ? $this->verifiedBy->name : 'System';
                                $log->activity = 'update';
                                $log->product = $product->name;
                                $log->old_stok = $oldStock;
                                $log->new_stok = $product->stok;
                                $log->save();
                            }
                        }
                    }

                    // Create activity log for successful deletion
                    $this->createActivityLogForAppliedChange('delete', $this->old_data, null);

                    $model->delete();
                }
                break;
        }

        $this->applied_at = now();
        $this->save();

        return $this;
    }

    /**
     * Create model-specific activity log for approved changes
     */
    protected function createActivityLogForAppliedChange($activity, $oldData = null, $newData = null)
    {
        $modelClass = $this->changeable_type;
        $userName = $this->verifiedBy ? $this->verifiedBy->name : 'System';

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
        }
    }

    /**
     * Create Product activity log
     */
    protected function createProductActivityLog($activity, $oldData, $newData, $userName)
    {
        $log = new \App\Models\LogActivityProduct();
        $log->user = $userName;
        $log->activity = $activity;

        if ($activity === 'store' && $newData) {
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
        $log->activity = $activity;

        if ($activity === 'store' && $newData) {
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
        $log->activity = $activity;
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
