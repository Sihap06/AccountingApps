<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPhoneFieldsToTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->string('phone_brand')->nullable()->after('warranty_type');
            $table->string('phone_type')->nullable()->after('phone_brand');
            $table->string('phone_color')->nullable()->after('phone_type');
            $table->string('phone_imei')->nullable()->after('phone_color');
            $table->string('phone_internal')->nullable()->after('phone_imei');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['phone_brand', 'phone_type', 'phone_color', 'phone_imei', 'phone_internal']);
        });
    }
}
