<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePendingChangesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pending_changes', function (Blueprint $table) {
            $table->id();
            $table->string('changeable_type'); // Model type: Product/Transaction/Expenditure
            $table->unsignedBigInteger('changeable_id')->nullable(); // ID of record, null for new records
            $table->enum('action', ['create', 'update', 'delete']);
            $table->json('old_data')->nullable(); // Original data before change
            $table->json('new_data'); // Proposed new data
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->foreignId('requested_by')->constrained('users');
            $table->timestamp('requested_at')->useCurrent();
            $table->foreignId('verified_by')->nullable()->constrained('users');
            $table->timestamp('verified_at')->nullable();
            $table->text('verification_notes')->nullable();
            $table->timestamp('applied_at')->nullable(); // When change was applied
            $table->timestamps();

            // Indexes for performance
            $table->index(['changeable_type', 'changeable_id']);
            $table->index('status');
            $table->index('requested_by');
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
        Schema::dropIfExists('pending_changes');
    }
}
