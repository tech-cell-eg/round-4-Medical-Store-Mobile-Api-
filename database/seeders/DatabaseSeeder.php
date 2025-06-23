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
            CategorySeeder::class,
            UnitSeeder::class,
            BrandSeeder::class, // Added BrandSeeder
            IngredientSeeder::class, // إضافة IngredientSeeder
            ProductSeeder::class,
            PackageSeeder::class,
        ]);
        // إنشاء البيانات الأولية
        $this->call([
            UsersTableSeeder::class,
            UnitsTableSeeder::class,

        ]);
    }
}
