<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'masteradmin',
            'email' => 'master_admin@gmail.com',
            'password' => bcrypt('rahasia'),
            'role' => 'master_admin'
        ]);
    }
}
