<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Package;

class PackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some products to create packages for them
        $product1 = \App\Models\Product::where('name', 'Panadol Extra')->first();
        $product2 = \App\Models\Product::where('name', 'Amoxil')->first();
        $product3 = \App\Models\Product::where('name', 'Vitamin C Forte')->first();

        if ($product1) {
            Package::firstOrCreate(
                ['product_id' => $product1->id, 'size' => '24 Tab'],
                ['price' => 25.50, 'sku' => 'PAND-24T', 'barcode' => '100001']
            );
            Package::firstOrCreate(
                ['product_id' => $product1->id, 'size' => '48 Tab'],
                ['price' => 45.00, 'sku' => 'PAND-48T', 'barcode' => '100002']
            );
        }

        if ($product2) {
            Package::firstOrCreate(
                ['product_id' => $product2->id, 'size' => '15 Cap'],
                ['price' => 80.00, 'sku' => 'AMOX-15C', 'barcode' => '200001']
            );
        }

        if ($product3) {
            Package::firstOrCreate(
                ['product_id' => $product3->id, 'size' => '30 Tab'],
                ['price' => 50.75, 'sku' => 'VITC-30T', 'barcode' => '300001']
            );
            Package::firstOrCreate(
                ['product_id' => $product3->id, 'size' => '90 Tab'],
                ['price' => 120.00, 'sku' => 'VITC-90T', 'barcode' => '300002']
            );
        }
    }
}
