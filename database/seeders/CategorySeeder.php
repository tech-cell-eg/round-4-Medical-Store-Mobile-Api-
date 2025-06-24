<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Painkillers', 'description' => 'Medications used to relieve pain.', 'slug' => 'painkillers', 'is_active' => true],
            ['name' => 'Skin Care', 'description' => 'Products for skin health and treatment.', 'slug' => 'skin-care', 'is_active' => true],
            ['name' => 'Antibiotics', 'description' => 'Drugs that fight bacterial infections.', 'slug' => 'antibiotics', 'is_active' => true],
            ['name' => 'Vitamins', 'description' => 'Supplements for nutritional support.', 'slug' => 'vitamins', 'is_active' => true],
            ['name' => 'Digestive Health', 'description' => 'Products for gastrointestinal issues.', 'slug' => 'digestive-health', 'is_active' => true],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate($category);
        }
    }
}
