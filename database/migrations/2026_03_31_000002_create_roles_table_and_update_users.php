<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateRolesTableAndUpdateUsers extends Migration
{
    public function up()
    {
        // 1. Buat Tabel Roles
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('description')->nullable();
            $table->timestamps();
        });

        // 2. Buat Tabel Pivot Role Permissions
        Schema::create('role_permissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('role_id')->constrained()->onDelete('cascade');
            $table->foreignId('permission_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            $table->unique(['role_id', 'permission_id']);
        });

        // 3. Masukkan Default Roles (Untuk transisi awal yang aman)
        $ownerId = DB::table('roles')->insertGetId(['name' => 'Owner', 'description' => 'Akses penuh ke semua fitur', 'created_at' => now(), 'updated_at' => now()]);
        $manajerId = DB::table('roles')->insertGetId(['name' => 'Manajer', 'description' => 'Akses manajerial operasional dan laporan', 'created_at' => now(), 'updated_at' => now()]);
        $kasirId = DB::table('roles')->insertGetId(['name' => 'Kasir', 'description' => 'Akses operasional kasir dan transaksi', 'created_at' => now(), 'updated_at' => now()]);

        // Berikan semua hak akses ke Owner
        $permissions = DB::table('permissions')->get();
        $ownerPermissions = [];
        foreach ($permissions as $permission) {
            $ownerPermissions[] = [
                'role_id' => $ownerId,
                'permission_id' => $permission->id,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        DB::table('role_permissions')->insert($ownerPermissions);

        // Berikan hak akses default dasar untuk Manajer dan Kasir lama agar tidak kehilangan akses
        $kasirPerms = ['dashboard', 'pos', 'customers'];
        $manajerPerms = ['dashboard', 'inventory', 'pos', 'reporting', 'customers', 'stock_opname'];

        foreach ($kasirPerms as $key) {
            $perm = DB::table('permissions')->where('key', $key)->first();
            if ($perm) {
                DB::table('role_permissions')->insert(['role_id' => $kasirId, 'permission_id' => $perm->id, 'created_at' => now(), 'updated_at' => now()]);
            }
        }

        foreach ($manajerPerms as $key) {
            $perm = DB::table('permissions')->where('key', $key)->first();
            if ($perm) {
                DB::table('role_permissions')->insert(['role_id' => $manajerId, 'permission_id' => $perm->id, 'created_at' => now(), 'updated_at' => now()]);
            }
        }

        // 4. Update Tabel Users: Tambah role_id (nullable untuk perantara), pindahkan data, droo kolom lama
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('role_id')->nullable()->after('password')->constrained('roles');
        });

        // Pindahkan role eksisting ke ID
        DB::table('users')->where('role', 'owner')->update(['role_id' => $ownerId]);
        DB::table('users')->where('role', 'manajer')->update(['role_id' => $manajerId]);
        DB::table('users')->where('role', 'kasir')->update(['role_id' => $kasirId]);
        DB::table('users')->whereNull('role_id')->update(['role_id' => $kasirId]); // fallback

        // Drop user_permissions table karena permissions sekarang melekat pada roles
        Schema::dropIfExists('user_permissions');

        // Drop kolom string role lama
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }

    public function down()
    {
        // Rollback strategy
        Schema::create('user_permissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('permission_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            $table->unique(['user_id', 'permission_id']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('role', 50)->default('kasir')->after('password');
        });

        DB::table('users')->join('roles', 'users.role_id', '=', 'roles.id')
            ->update(['users.role' => DB::raw('LOWER(roles.name)')]);

        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
            $table->dropColumn('role_id');
        });

        Schema::dropIfExists('role_permissions');
        Schema::dropIfExists('roles');
    }
}
