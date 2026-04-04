<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeActivityColumnToStringInActivityLogTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('log_activity_products', function (Blueprint $table) {
            $table->string('activity')->change();
        });
        Schema::table('log_activity_expenditures', function (Blueprint $table) {
            $table->string('activity')->change();
        });
        Schema::table('log_activity_transactions', function (Blueprint $table) {
            $table->string('activity')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // We don't necessarily want to go back to ENUM if it's causing issues,
        // but for completeness we can define the previous state.
        // However, changing back to ENUM might fail if there's new data.
        Schema::table('log_activity_products', function (Blueprint $table) {
            $table->enum('activity', ['store', 'update', 'delete', 'stock_update'])->change();
        });
        Schema::table('log_activity_expenditures', function (Blueprint $table) {
            $table->enum('activity', ['store', 'update'])->change();
        });
        Schema::table('log_activity_transactions', function (Blueprint $table) {
            $table->enum('activity', ['store', 'update'])->change();
        });
    }
}
