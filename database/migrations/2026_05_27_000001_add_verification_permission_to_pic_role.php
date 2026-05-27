<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddVerificationPermissionToPicRole extends Migration
{
    public function up()
    {
        $picRole = DB::table('roles')->whereRaw('LOWER(name) = ?', ['pic'])->first();
        $permission = DB::table('permissions')->where('key', 'verification')->first();

        if (!$picRole || !$permission) {
            return;
        }

        $exists = DB::table('role_permissions')
            ->where('role_id', $picRole->id)
            ->where('permission_id', $permission->id)
            ->exists();

        if (!$exists) {
            DB::table('role_permissions')->insert([
                'role_id' => $picRole->id,
                'permission_id' => $permission->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down()
    {
        $picRole = DB::table('roles')->whereRaw('LOWER(name) = ?', ['pic'])->first();
        $permission = DB::table('permissions')->where('key', 'verification')->first();

        if (!$picRole || !$permission) {
            return;
        }

        DB::table('role_permissions')
            ->where('role_id', $picRole->id)
            ->where('permission_id', $permission->id)
            ->delete();
    }
}
