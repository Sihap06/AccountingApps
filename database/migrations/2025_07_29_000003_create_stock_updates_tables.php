<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockUpdatesTables extends Migration
{
    public function up()
    {
        Schema::create('stock_updates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->string('nota_image')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('stock_update_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stock_update_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained();
            $table->string('product_name');
            $table->integer('qty_added');
            $table->bigInteger('purchase_price')->default(0)->comment('Harga beli per item');
            $table->integer('stock_before');
            $table->integer('stock_after');
            $table->bigInteger('price_before')->default(0)->comment('Harga beli rata-rata sebelum');
            $table->bigInteger('price_after')->default(0)->comment('Harga beli rata-rata sesudah');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('stock_update_items');
        Schema::dropIfExists('stock_updates');
    }
}
