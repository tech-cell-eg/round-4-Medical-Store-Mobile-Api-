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
            IngredientSeeder::class, // إضافة IngredientSeeder
            CategorySeeder::class,
            UnitsTableSeeder::class,
            BrandSeeder::class, // Added BrandSeeder
            
            // ProductsTableSeeder::class,
            PackageSeeder::class,
            ReviewsTableSeeder::class,
        ]);
    }
}
