<?php

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UnitsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // تعطيل فحص المفاتيح الأجنبية مؤقتًا
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // حذف البيانات الحالية من الجدول
        Unit::truncate();
        
        // تفعيل فحص المفاتيح الأجنبية مرة أخرى
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        // مصفوفة تحتوي على وحدات القياس الشائعة في الصيدليات
        $units = [
            [
                'name' => 'حبة',
                'symbol' => 'ح',
                'description' => 'الوحدة الأساسية للدواء',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'علبة',
                'symbol' => 'علبة',
                'description' => 'علبة الدواء',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'شريط',
                'symbol' => 'شر',
                'description' => 'شريط الأدوية',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'زجاجة',
                'symbol' => 'ز',
                'description' => 'زجاجة الدواء',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'جرام',
                'symbol' => 'جم',
                'description' => 'وحدة قياس الوزن',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'مللي جرام',
                'symbol' => 'ملجم',
                'description' => 'مليجرام (واحد من الألف من الجرام)',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'لتر',
                'symbol' => 'لتر',
                'description' => 'وحدة قياس السوائل',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'مللي لتر',
                'symbol' => 'مل',
                'description' => 'ملي لتر (واحد من الألف من اللتر)',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'ميكروجرام',
                'symbol' => 'ميكروجرام',
                'description' => 'واحد من المليون من الجرام',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'وحدة دولية',
                'symbol' => 'وحدة',
                'description' => 'وحدة قياس الفيتامينات والهرمونات',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'مليلتر',
                'symbol' => 'مل',
                'description' => 'مليلتر (واحد من الألف من اللتر)',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'سنتيمتر مكعب',
                'symbol' => 'سم³',
                'description' => 'سنتيمتر مكعب (يعادل الملليلتر)',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'قطرة',
                'symbol' => 'قطرة',
                'description' => 'وحدة قياس السوائل بالقطارة',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'ملعقة صغيرة',
                'symbol' => 'ملعقة ص',
                'description' => 'ملعقة صغيرة (5 مل تقريبًا)',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'ملعقة طعام',
                'symbol' => 'ملعقة ك',
                'description' => 'ملعقة طعام (15 مل تقريبًا)',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'أوقية',
                'symbol' => 'أوقية',
                'description' => 'أوقية (28.35 جرام تقريبًا)',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'رطل',
                'symbol' => 'رطل',
                'description' => 'رطل (453.59 جرام تقريبًا)',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'كيلوجرام',
                'symbol' => 'كجم',
                'description' => 'كيلوجرام (1000 جرام)',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'لتر',
                'symbol' => 'لتر',
                'description' => 'لتر (1000 ملليلتر)',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        
        // إضافة الوحدات إلى قاعدة البيانات
        foreach ($units as $unit) {
            Unit::create($unit);
        }
    }
}
