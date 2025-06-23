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

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get Categories, Units, and Brands
        $painkillerCategory = Category::where('name', 'Painkillers')->first();
        $antibioticsCategory = Category::where('name', 'Antibiotics')->first();
        $vitaminsCategory = Category::where('name', 'Vitamins')->first();
        $digestiveCategory = Category::where('name', 'Digestive Health')->first();

        $tabletUnit = Unit::where('symbol', 'Tab')->first();
        $capsuleUnit = Unit::where('symbol', 'Cap')->first();
        $syrupUnit = Unit::where('symbol', 'Syr')->first();

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
            // Ensure all required relations are loaded
            if ($productData['category'] && $productData['unit'] && $productData['brand']) {
                                $product = Product::firstOrCreate(
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

                // Attach random ingredients to the product
                if ($ingredients->count() > 0) {
                    $product->ingredients()->attach(
                        $ingredients->random(rand(1, min(3, $ingredients->count())))->pluck('id')->toArray()
                    );
                }
            }
        }
    }
}
