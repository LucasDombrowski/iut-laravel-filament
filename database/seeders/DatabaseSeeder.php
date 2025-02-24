<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            CategorySeeder::class,
            ProductSeeder::class,
            ColorSeeder::class,
            SizeSeeder::class,
            VariantSeeder::class,
            CapabilitySeeder::class,
            RoleSeeder::class,
            DiscountSeeder::class,
            UserSeeder::class,
        ]);
    }
}
