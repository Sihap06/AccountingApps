<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDiscountToTransactionsAndTransactionItemsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->integer('potongan')->nullable()->default(0)->after('biaya')->comment('Discount amount');
        });

        Schema::table('transaction_items', function (Blueprint $table) {
            $table->integer('potongan')->nullable()->default(0)->after('biaya')->comment('Discount amount');
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
            $table->dropColumn('potongan');
        });

        Schema::table('transaction_items', function (Blueprint $table) {
            $table->dropColumn('potongan');
        });
    }
}