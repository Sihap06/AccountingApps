<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddReportingDetailPermissions extends Migration
{
    public function up()
    {
        $permissions = [
            [
                'key' => 'reporting_transaction',
                'name' => 'Laporan Transaksi',
                'group' => 'Reporting',
                'sort_order' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'reporting_expenditure',
                'name' => 'Laporan Pengeluaran',
                'group' => 'Reporting',
                'sort_order' => 11,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'reporting_income_fee',
                'name' => 'Laporan Pendapatan & Fee',
                'group' => 'Reporting',
                'sort_order' => 12,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'reporting_export',
                'name' => 'Fungsi Export Laporan',
                'group' => 'Reporting',
                'sort_order' => 13,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];

        DB::table('permissions')->insert($permissions);

        // Jika ada tabel Role, berikan ini otomatis kepada yang punya izin reporting awal
        // Supaya tidak mendadak hilang aksesnya
        $manajerRole = DB::table('roles')->where('name', 'Manajer')->first();
        if ($manajerRole) {
            $insertedPerms = DB::table('permissions')->whereIn('key', [
                'reporting_transaction', 
                'reporting_expenditure', 
                'reporting_income_fee', 
                'reporting_export'
            ])->get();

            $pivots = [];
            foreach ($insertedPerms as $perm) {
                $pivots[] = [
                    'role_id' => $manajerRole->id,
                    'permission_id' => $perm->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            DB::table('role_permissions')->insert($pivots);
        }
    }

    public function down()
    {
        // Temukan IDs dari permission yang baru ditambah
        $permIds = DB::table('permissions')->whereIn('key', [
            'reporting_transaction',
            'reporting_expenditure',
            'reporting_income_fee',
            'reporting_export'
        ])->pluck('id');

        // Hapus dari pivot
        if ($permIds->isNotEmpty()) {
            DB::table('role_permissions')->whereIn('permission_id', $permIds)->delete();
        }

        // Hapus dari tabel permissions
        DB::table('permissions')->whereIn('key', [
            'reporting_transaction',
            'reporting_expenditure',
            'reporting_income_fee',
            'reporting_export'
        ])->delete();
    }
}
