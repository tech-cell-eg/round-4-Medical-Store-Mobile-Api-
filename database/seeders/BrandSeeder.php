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
        Brand::firstOrCreate(['name' => 'GSK'], ['description' => 'GlaxoSmithKline']);
        Brand::firstOrCreate(['name' => 'Pfizer'], ['description' => 'Pfizer Inc.']);
        Brand::firstOrCreate(['name' => 'Bayer'], ['description' => 'Bayer AG']);
        Brand::firstOrCreate(['name' => 'Novartis'], ['description' => 'Novartis AG']);
        Brand::firstOrCreate(['name' => 'Generic'], ['description' => 'Generic Manufacturer']);
    }
}
