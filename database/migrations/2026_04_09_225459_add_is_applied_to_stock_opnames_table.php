<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsAppliedToStockOpnamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stock_opnames', function (Blueprint $table) {
            $table->boolean('is_applied')->default(false)->after('completed_at');
            $table->foreignId('applied_by')->nullable()->constrained('users')->nullOnDelete()->after('is_applied');
            $table->timestamp('applied_at')->nullable()->after('applied_by');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stock_opnames', function (Blueprint $table) {
            $table->dropForeign(['applied_by']);
            $table->dropColumn(['is_applied', 'applied_by', 'applied_at']);
        });
    }
}
