<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,  // roles first
            UserSeeder::class,            // users with roles
            FinancialRecordSeeder::class, // records tied to users
        ]);
    }
}
