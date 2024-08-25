<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogActivityExpendituresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('log_activity_expenditures', function (Blueprint $table) {
            $table->id();
            $table->string('user')->nullable();
            $table->string('jenis')->nullable();
            $table->string('old_jenis')->nullable();
            $table->string('new_jenis')->nullable();
            $table->string('old_tanggal')->nullable();
            $table->string('new_tanggal')->nullable();
            $table->integer('old_total')->nullable();
            $table->integer('new_total')->nullable();
            $table->enum('activity', ['store', 'update']);
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
        Schema::dropIfExists('log_activity_expenditures');
    }
}
