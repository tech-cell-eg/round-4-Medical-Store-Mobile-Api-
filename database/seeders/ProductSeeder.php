<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use App\Models\Unit;
use App\Models\Brand;
use App\Models\Ingredient;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing ingredient_product relationships
        DB::table('ingredient_product')->delete();
        // Clear existing products
        Product::query()->delete();

        // Get Categories, Units, and Brands
        $painkillerCategory = Category::where('name', 'Painkillers')->first();
        $antibioticsCategory = Category::where('name', 'Antibiotics')->first();
        $vitaminsCategory = Category::where('name', 'Vitamins')->first();
        $digestiveCategory = Category::where('name', 'Digestive Health')->first();

        $tabletUnit = Unit::where('short_name', 'Tab')->first();
        $capsuleUnit = Unit::where('short_name', 'Cap')->first();
        $syrupUnit = Unit::where('short_name', 'Syr')->first();

        $gskBrand = Brand::where('name', 'GSK')->first();
        $bayerBrand = Brand::where('name', 'Bayer')->first();
        $genericBrand = Brand::where('name', 'Generic')->first();

        // Get all ingredients
        $ingredients = Ingredient::all();

        // Define products data
        $products = [
            [
                'name' => 'Panadol Extra',
                'description' => 'For fast and effective relief of pain.',
                'category' => $painkillerCategory,
                'unit' => $tabletUnit,
                'brand' => $gskBrand,
            ],
            [
                'name' => 'Amoxil',
                'description' => 'A broad-spectrum penicillin antibiotic.',
                'category' => $antibioticsCategory,
                'unit' => $capsuleUnit,
                'brand' => $gskBrand,
            ],
            [
                'name' => 'Vitamin C Forte',
                'description' => 'Dietary supplement for immune support.',
                'category' => $vitaminsCategory,
                'unit' => $tabletUnit,
                'brand' => $bayerBrand,
            ],
            [
                'name' => 'Gaviscon Advance',
                'description' => 'For heartburn and indigestion relief.',
                'category' => $digestiveCategory,
                'unit' => $syrupUnit,
                'brand' => $genericBrand,
            ],
        ];

        foreach ($products as $productData) {
            if ($productData['category'] && $productData['unit'] && $productData['brand']) {
                $product = Product::updateOrCreate(
                    ['name' => $productData['name']],
                    [
                        'description' => $productData['description'],
                        'production_date' => Carbon::now()->subMonths(rand(2, 12)),
                        'expiry_date' => Carbon::now()->addMonths(rand(12, 36)),
                        'category_id' => $productData['category']->id,
                        'unit_id' => $productData['unit']->id,
                        'brand_id' => $productData['brand']->id,
                        'is_active' => true,
                        'image_url' => 'images/products/' . strtolower(str_replace(' ', '_', $productData['name'])) . '.jpg',
                    ]
                );

                // Attach random ingredients
                if ($ingredients->count() > 0) {
                    $product->ingredients()->sync(
                        $ingredients->random(rand(1, min(3, $ingredients->count())))->pluck('id')->toArray()
                    );
                }
            }
        }
    }
}