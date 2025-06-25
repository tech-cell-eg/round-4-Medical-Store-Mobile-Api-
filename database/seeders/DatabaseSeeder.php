<?php

namespace Database\Seeders;

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
            AdminSeeder::class,
            UsersTableSeeder::class,
            CategorySeeder::class,
            UnitsTableSeeder::class,
            BrandSeeder::class, // Added BrandSeeder
            IngredientSeeder::class, // إضافة IngredientSeeder
            ProductsTableSeeder::class,
            PackageSeeder::class,
            ReviewsTableSeeder::class,
        ]);
    }
}
