<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Unit;
use App\Models\User;
use Faker\Factory as Faker;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // حذف البيانات الموجودة مسبقاً
        Product::truncate();
        
        // إنشاء مثيل Faker
        $faker = Faker::create('ar_SA');
        
        // الحصول على أول مستخدم (للاستخدام في created_by و updated_by)
        $user = User::first();
        
        // الحصول على فئة افتراضية أو إنشائها
        $category = Category::firstOrCreate(
            ['name' => 'أدوية'],
            [
                'description' => 'أدوية متنوعة',
                'is_active' => true,
                'created_by' => $user ? $user->id : null
            ]
        );
        
        // الحصول على علامة تجارية افتراضية أو إنشائها
        $brand = Brand::firstOrCreate(
            ['name' => 'الشركة الوطنية للأدوية'],
            [
                'description' => 'واحدة من أكبر شركات الأدوية في المملكة',
                'is_active' => true,
                'created_by' => $user ? $user->id : null
            ]
        );
        
        // الحصول على وحدة قياس افتراضية أو إنشائها
        $unit = Unit::firstOrCreate(
            ['name' => 'علبة'],
            [
                'short_name' => 'علبة',
                'is_active' => true,
                'is_default' => true,
                'created_by' => $user ? $user->id : null
            ]
        );
        
        // بيانات المنتجات التجريبية
        $products = [
            [
                'name' => 'بنادول اكسترا',
                'description' => 'مسكن للآلام وخافض للحرارة',
                'price' => 15.50,
                'quantity' => 100,
                'barcode' => '1234567890123',
                'is_active' => true,
                'category_id' => $category->id,
                'brand_id' => $brand->id,
                'unit_id' => $unit->id,
                'created_by' => $user ? $user->id : null,
            ],
            [
                'name' => 'فيفادول اكسترا',
                'description' => 'مسكن قوي للآلام الشديدة',
                'price' => 18.75,
                'quantity' => 75,
                'barcode' => '1234567890124',
                'is_active' => true,
                'category_id' => $category->id,
                'brand_id' => $brand->id,
                'unit_id' => $unit->id,
                'created_by' => $user ? $user->id : null,
            ],
            [
                'name' => 'أدول',
                'description' => 'خافض للحرارة ومسكن للآلام',
                'price' => 12.00,
                'quantity' => 120,
                'barcode' => '1234567890125',
                'is_active' => true,
                'category_id' => $category->id,
                'brand_id' => $brand->id,
                'unit_id' => $unit->id,
                'created_by' => $user ? $user->id : null,
            ],
            [
                'name' => 'بروفين',
                'description' => 'مسكن للآلام ومضاد للالتهابات',
                'price' => 10.00,
                'quantity' => 90,
                'barcode' => '1234567890126',
                'is_active' => true,
                'category_id' => $category->id,
                'brand_id' => $brand->id,
                'unit_id' => $unit->id,
                'created_by' => $user ? $user->id : null,
            ],
            [
                'name' => 'فيتامين سي 1000',
                'description' => 'مكمل غذائي غني بفيتامين سي',
                'price' => 45.00,
                'quantity' => 50,
                'barcode' => '1234567890127',
                'is_active' => true,
                'category_id' => $category->id,
                'brand_id' => $brand->id,
                'unit_id' => $unit->id,
                'created_by' => $user ? $user->id : null,
            ]
        ];
        
        // إضافة المنتجات إلى قاعدة البيانات
        foreach ($products as $productData) {
            Product::create($productData);
        }
    }
}
