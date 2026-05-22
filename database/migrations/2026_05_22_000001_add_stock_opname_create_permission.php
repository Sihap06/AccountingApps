<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddStockOpnameCreatePermission extends Migration
{
    public function up()
    {
        // Geser sort_order technician dari 9 ke 10 supaya permission baru bisa muncul setelah stock_opname
        DB::table('permissions')->where('key', 'technician')->update([
            'sort_order' => 10,
            'updated_at' => now(),
        ]);

        DB::table('permissions')->insert([
            'key' => 'stock_opname_create',
            'name' => 'Buat Stok Opname',
            'group' => 'Operasional',
            'sort_order' => 9,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down()
    {
        $permId = DB::table('permissions')->where('key', 'stock_opname_create')->value('id');

        if ($permId) {
            DB::table('role_permissions')->where('permission_id', $permId)->delete();
            DB::table('permissions')->where('id', $permId)->delete();
        }

        DB::table('permissions')->where('key', 'technician')->update([
            'sort_order' => 9,
            'updated_at' => now(),
        ]);
    }
}
