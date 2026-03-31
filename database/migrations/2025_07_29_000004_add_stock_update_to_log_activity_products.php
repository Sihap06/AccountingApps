<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddStockUpdateToLogActivityProducts extends Migration
{
    public function up()
    {
        DB::statement("ALTER TABLE log_activity_products MODIFY COLUMN activity ENUM('store', 'update', 'delete', 'stock_update')");
    }

    public function down()
    {
        DB::statement("ALTER TABLE log_activity_products MODIFY COLUMN activity ENUM('store', 'update', 'delete')");
    }
}
