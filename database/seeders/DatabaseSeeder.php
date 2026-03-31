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
        $this->call(PermissionSeeder::class);

        User::create([
            'name' => 'Owner',
            'email' => 'owner@gmail.com',
            'password' => bcrypt('rahasia'),
            'role' => 'owner'
        ]);
    }
}
