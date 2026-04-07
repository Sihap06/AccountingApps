<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddUniqueIndexToOrderTransaction extends Migration
{
    /**
     * Run the migrations.
     *
     * Adds a UNIQUE index on transactions.order_transaction so the database
     * itself prevents duplicate order IDs from being inserted (safety net for
     * the application-level lockForUpdate generator).
     *
     * Existing duplicates are renamed first by appending "-DUPn" so the
     * unique index can be applied without manual cleanup.
     *
     * @return void
     */
    public function up()
    {
        // Resolve any pre-existing duplicates by suffixing later rows.
        $duplicates = DB::table('transactions')
            ->select('order_transaction', DB::raw('COUNT(*) as cnt'))
            ->whereNotNull('order_transaction')
            ->groupBy('order_transaction')
            ->having('cnt', '>', 1)
            ->pluck('order_transaction');

        foreach ($duplicates as $orderId) {
            $rows = DB::table('transactions')
                ->where('order_transaction', $orderId)
                ->orderBy('id')
                ->get(['id']);

            // Keep the first row as-is, rename subsequent ones.
            foreach ($rows->slice(1)->values() as $i => $row) {
                DB::table('transactions')
                    ->where('id', $row->id)
                    ->update(['order_transaction' => $orderId . '-DUP' . ($i + 1)]);
            }
        }

        Schema::table('transactions', function (Blueprint $table) {
            $table->unique('order_transaction', 'transactions_order_transaction_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropUnique('transactions_order_transaction_unique');
        });
    }
}
