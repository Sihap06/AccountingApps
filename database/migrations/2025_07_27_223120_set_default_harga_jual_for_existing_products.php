<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SetDefaultHargaJualForExistingProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Set default harga_jual to be 20% more than harga for existing products
        \DB::statement('UPDATE products SET harga_jual = ROUND(harga * 1.2) WHERE harga_jual IS NULL');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // We can't really reverse this operation
    }
}
