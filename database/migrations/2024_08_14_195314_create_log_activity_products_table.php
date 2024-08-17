<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogActivityProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('log_activity_products', function (Blueprint $table) {
            $table->id();
            $table->string('user')->nullable();
            $table->string('product')->nullable();
            $table->string('old_name')->nullable();
            $table->string('new_name')->nullable();
            $table->string('old_price')->nullable();
            $table->string('new_price')->nullable();
            $table->integer('old_stok')->nullable();
            $table->integer('new_stok')->nullable();
            $table->enum('activity', ['store', 'update', 'delete']);
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
        Schema::dropIfExists('log_activity_products');
    }
}
