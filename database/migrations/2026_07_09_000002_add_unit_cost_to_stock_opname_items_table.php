<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUnitCostToStockOpnameItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stock_opname_items', function (Blueprint $table) {
            $table->unsignedBigInteger('unit_cost')->nullable()->after('difference')
                ->comment('Snapshot harga modal produk saat item dihitung, untuk valuasi kerugian/surplus');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stock_opname_items', function (Blueprint $table) {
            $table->dropColumn('unit_cost');
        });
    }
}
