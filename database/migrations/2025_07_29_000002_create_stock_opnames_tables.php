<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockOpnamesTables extends Migration
{
    public function up()
    {
        Schema::create('stock_opnames', function (Blueprint $table) {
            $table->id();
            $table->foreignId('triggered_by')->constrained('users')->comment('Owner yang trigger');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->comment('Kasir yang ditugaskan');
            $table->foreignId('completed_by')->nullable()->constrained('users');
            $table->enum('status', ['pending', 'in_progress', 'completed', 'cancelled'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });

        Schema::create('stock_opname_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stock_opname_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained();
            $table->string('product_name');
            $table->integer('system_stock')->comment('Stok di sistem');
            $table->integer('actual_stock')->nullable()->comment('Stok fisik di toko');
            $table->integer('difference')->nullable()->comment('Selisih stok');
            $table->text('notes')->nullable();
            $table->boolean('checked')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('stock_opname_items');
        Schema::dropIfExists('stock_opnames');
    }
}
