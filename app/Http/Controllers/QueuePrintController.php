<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QueuePrintController extends Controller
{
    public function getQueue($id)
    {
        $transactions = DB::table('transactions')
            ->leftJoin('transaction_items', function ($join) {
                $join->on('transactions.id', '=', 'transaction_items.transaction_id')
                    ->whereNull('transaction_items.deleted_at');
            })
            ->leftJoin('customers', 'transactions.customer_id', '=', 'customers.id')
            ->leftJoin('users', 'transactions.created_by', '=', 'users.id')
            ->select(
                'transactions.id as transaction_id',
                'transactions.biaya as transaction_biaya',
                'transactions.created_at as transaction_created_at',
                'transactions.created_by as transaction_created_by',
                'transactions.customer_id as transaction_customer_id',
                'transactions.deleted_at as transaction_deleted_at',
                'transactions.fee_teknisi as transaction_fee_teknisi',
                'transactions.modal as transaction_modal',
                'transactions.order_transaction as transaction_order_transaction',
                'transactions.payment_method as transaction_payment_method',
                'transactions.product_id as transaction_product_id',
                'transactions.service as transaction_service',
                'transactions.status as transaction_status',
                'transactions.technical_id as transaction_technical_id',
                'transactions.warranty as transaction_warranty',
                'transactions.warranty_type as transaction_warranty_type',
                'transactions.untung as transaction_untung',
                'transactions.updated_at as transaction_updated_at',
                'customers.name as customer_name',
                'customers.no_telp as customer_no_telp',
                'users.name as operator_name',
                'transaction_items.id as item_id',
                'transaction_items.biaya as item_biaya',
                'transaction_items.created_at as item_created_at',
                'transaction_items.deleted_at as item_deleted_at',
                'transaction_items.fee_teknisi as item_fee_teknisi',
                'transaction_items.modal as item_modal',
                'transaction_items.product_id as item_product_id',
                'transaction_items.service as item_service',
                'transaction_items.technical_id as item_technical_id',
                'transaction_items.untung as item_untung',
                'transaction_items.warranty as item_warranty',
                'transaction_items.warranty_type as item_warranty_type',
                'transaction_items.updated_at as item_updated_at'
            )
            ->where('transactions.id', $id)
            ->get()
            ->groupBy('transaction_id');

        $results = $transactions->map(function ($items, $transactionId) {
            $transaction = $items->first();

            $total = $transaction->transaction_biaya;

            return [
                'id' => $transaction->transaction_id,
                'created_at' => $transaction->transaction_created_at,
                'order_transaction' => $transaction->transaction_order_transaction,
                'status' => $transaction->transaction_status,
                'payment_method' => $transaction->transaction_payment_method,
                'customer_name' => $transaction->customer_name,
                'no_telp' => $transaction->customer_no_telp,
                'operator_name' => $transaction->operator_name,
                'biaya' => $transaction->transaction_biaya,
                'created_at' => $transaction->transaction_created_at,
                'fee_teknisi' => $transaction->transaction_fee_teknisi,
                'modal' => $transaction->transaction_modal,
                'product_id' => $transaction->transaction_product_id,
                'service' => $transaction->transaction_service,
                'technical_id' => $transaction->transaction_technical_id,
                'untung' => $transaction->transaction_untung,
                'warranty' => $transaction->transaction_warranty,
                'warranty_type' => $transaction->transaction_warranty_type,
                'items' =>  $transaction->item_biaya !== null ? $items->map(function ($item, $index) use (&$total) {

                    $total += $item->item_biaya;

                    return [
                        'biaya' => $item->item_biaya,
                        'created_at' => $item->item_created_at,
                        'fee_teknisi' => $item->item_fee_teknisi,
                        'modal' => $item->item_modal,
                        'product_id' => $item->item_product_id,
                        'service' => $item->item_service,
                        'technical_id' => $item->item_technical_id,
                        'untung' => $item->item_untung,
                        'warranty' => $item->item_warranty,
                        'warranty_type' => $item->item_warranty_type
                    ];
                })->toArray() : [],
                'total' => $total
            ];
        });

        $data = $results->values()->toArray()[0];

        return response()->json($data);
    }
}
