<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UpdateRolesAndCreatePermissionsTables extends Migration
{
    public function up()
    {
        // Change role enum on users table
        DB::statement("ALTER TABLE users MODIFY COLUMN role VARCHAR(50) NOT NULL DEFAULT 'kasir'");

        // Update existing roles
        DB::table('users')->where('role', 'master_admin')->update(['role' => 'owner']);
        DB::table('users')->where('role', 'admin')->update(['role' => 'owner']);
        DB::table('users')->where('role', 'sysadmin')->update(['role' => 'kasir']);

        // Create permissions table
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // e.g. 'inventory', 'pos', 'reporting'
            $table->string('name'); // Display name e.g. 'Inventory'
            $table->string('group')->nullable(); // Group for UI display
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // Create user_permissions pivot table
        Schema::create('user_permissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('permission_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            $table->unique(['user_id', 'permission_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_permissions');
        Schema::dropIfExists('permissions');

        // Revert roles
        DB::table('users')->where('role', 'owner')->update(['role' => 'master_admin']);
        DB::table('users')->where('role', 'kasir')->update(['role' => 'sysadmin']);
        DB::table('users')->where('role', 'manajer')->update(['role' => 'sysadmin']);

        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('sysadmin', 'admin') NOT NULL");
    }
}
