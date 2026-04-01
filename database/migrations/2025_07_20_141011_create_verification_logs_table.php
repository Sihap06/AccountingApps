<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVerificationLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('verification_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pending_change_id')->constrained('pending_changes');
            $table->string('verifiable_type'); // Model type that was verified
            $table->unsignedBigInteger('verifiable_id')->nullable(); // ID of the verified record
            $table->enum('action', ['approved', 'rejected']);
            $table->foreignId('verified_by')->constrained('users');
            $table->text('notes')->nullable();
            $table->json('snapshot_before')->nullable(); // Data snapshot before verification
            $table->json('snapshot_after')->nullable(); // Data snapshot after verification
            $table->timestamps();

            // Indexes
            $table->index(['verifiable_type', 'verifiable_id']);
            $table->index('verified_by');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('verification_logs');
    }
}
