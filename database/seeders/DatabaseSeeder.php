<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleAndPermissionSeeder::class,
            AllowedEmailDomainSeeder::class,
            TagSeeder::class,
            UserSeeder::class,
            MissionSeeder::class,
            ApplicationSeeder::class,
            MessageSeeder::class,
        ]);
    }
}
