<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAcknowledgedAtToPendingChanges extends Migration
{
    /**
     * Run the migrations.
     *
     * Adds an `acknowledged_at` timestamp so the system can tell which
     * approved/rejected pending changes the requester has already seen.
     * Used by the dashboard verification result notification.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pending_changes', function (Blueprint $table) {
            $table->timestamp('acknowledged_at')->nullable()->after('applied_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pending_changes', function (Blueprint $table) {
            $table->dropColumn('acknowledged_at');
        });
    }
}
