<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            ['key' => 'dashboard', 'name' => 'Dashboard', 'group' => 'Umum', 'sort_order' => 1],
            ['key' => 'inventory', 'name' => 'Inventory', 'group' => 'Umum', 'sort_order' => 2],
            ['key' => 'pos', 'name' => 'Point of Sales', 'group' => 'Umum', 'sort_order' => 3],
            ['key' => 'reporting', 'name' => 'Reporting', 'group' => 'Umum', 'sort_order' => 4],
            ['key' => 'customers', 'name' => 'Customers', 'group' => 'Umum', 'sort_order' => 5],
            ['key' => 'financial_summary', 'name' => 'Financial Summary', 'group' => 'Keuangan', 'sort_order' => 6],
            ['key' => 'payment_methods', 'name' => 'Metode Pembayaran', 'group' => 'Keuangan', 'sort_order' => 7],
            ['key' => 'stock_opname', 'name' => 'Stok Opname', 'group' => 'Operasional', 'sort_order' => 8],
            ['key' => 'technician', 'name' => 'Teknisi', 'group' => 'Operasional', 'sort_order' => 9],
            ['key' => 'verification', 'name' => 'Verifikasi', 'group' => 'Administrasi', 'sort_order' => 10],
            ['key' => 'user_management', 'name' => 'User Management', 'group' => 'Administrasi', 'sort_order' => 11],
            ['key' => 'log_activity', 'name' => 'Catatan Aktivitas', 'group' => 'Administrasi', 'sort_order' => 12],
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(
                ['key' => $permission['key']],
                $permission
            );
        }
    }
}
