<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRecheckTrackingToStockOpnameItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stock_opname_items', function (Blueprint $table) {
            $table->boolean('needs_recheck')->default(false)->after('checked')
                ->comment('Stok produk berubah setelah item dihitung, perlu dihitung ulang');
            $table->timestamp('checked_at')->nullable()->after('needs_recheck');
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
            $table->dropColumn(['needs_recheck', 'checked_at']);
        });
    }
}
