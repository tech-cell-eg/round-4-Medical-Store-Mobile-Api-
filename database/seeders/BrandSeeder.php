<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Brand;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Brand::firstOrCreate(['name' => 'GSK'], ['description' => 'GlaxoSmithKline', 'slug' => 'gsk']);
        Brand::firstOrCreate(['name' => 'Pfizer'], ['description' => 'Pfizer Inc.', 'slug' => 'pfizer']);
        Brand::firstOrCreate(['name' => 'Bayer'], ['description' => 'Bayer AG', 'slug' => 'bayer']);
        Brand::firstOrCreate(['name' => 'Novartis'], ['description' => 'Novartis AG', 'slug' => 'novartis']);
        Brand::firstOrCreate(['name' => 'Generic'], ['description' => 'Generic Manufacturer', 'slug' => 'generic']);
    }
}
