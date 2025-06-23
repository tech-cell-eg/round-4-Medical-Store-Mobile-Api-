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
        Category::firstOrCreate(['name' => 'Painkillers', 'description' => 'Medications used to relieve pain.']);
        Category::firstOrCreate(['name' => 'Skin Care', 'description' => 'Products for skin health and treatment.']);
        Category::firstOrCreate(['name' => 'Antibiotics', 'description' => 'Drugs that fight bacterial infections.']);
        Category::firstOrCreate(['name' => 'Vitamins', 'description' => 'Supplements for nutritional support.']);
        Category::firstOrCreate(['name' => 'Digestive Health', 'description' => 'Products for gastrointestinal issues.']);
    }
}
