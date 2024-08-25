<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogActivityTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('log_activity_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('user')->nullable();
            $table->string('order_transaction');
            $table->string('old_customer')->nullable();
            $table->string('new_customer')->nullable();
            $table->string('old_payment_method')->nullable();
            $table->string('new_payment_method')->nullable();
            $table->string('old_tanggal')->nullable();
            $table->string('new_tanggal')->nullable();
            $table->string('old_service')->nullable();
            $table->string('new_service')->nullable();
            $table->string('old_biaya')->nullable();
            $table->string('new_biaya')->nullable();
            $table->string('old_teknisi')->nullable();
            $table->string('new_teknisi')->nullable();
            $table->string('old_sparepart')->nullable();
            $table->string('new_sparepart')->nullable();
            $table->enum('activity', ['store', 'update']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('log_activity_transactions');
    }
}
