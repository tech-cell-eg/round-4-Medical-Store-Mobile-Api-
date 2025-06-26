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
                'short_name' => 'ح',
                'description' => 'الوحدة الأساسية للدواء',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'علبة',
                'short_name' => 'علبة',
                'description' => 'علبة الدواء',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'شريط',
                'short_name' => 'شر',
                'description' => 'شريط الأدوية',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'زجاجة',
                'short_name' => 'ز',
                'description' => 'زجاجة الدواء',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'جرام',
                'short_name' => 'جم',
                'description' => 'وحدة قياس الوزن',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'مللي جرام',
                'short_name' => 'ملجم',
                'description' => 'مليجرام (واحد من الألف من الجرام)',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'لتر',
                'short_name' => 'لتر',
                'description' => 'وحدة قياس السوائل',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'مللي لتر',
                'short_name' => 'مل',
                'description' => 'ملي لتر (واحد من الألف من اللتر)',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'ميكروجرام',
                'short_name' => 'ميكروجرام',
                'description' => 'واحد من المليون من الجرام',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'وحدة دولية',
                'short_name' => 'وحدة',
                'description' => 'وحدة قياس الفيتامينات والهرمونات',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'مليلتر',
                'short_name' => 'مل',
                'description' => 'مليلتر (واحد من الألف من اللتر)',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'سنتيمتر مكعب',
                'short_name' => 'سم³',
                'description' => 'سنتيمتر مكعب (يعادل الملليلتر)',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'قطرة',
                'short_name' => 'قطرة',
                'description' => 'وحدة قياس السوائل بالقطارة',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'ملعقة صغيرة',
                'short_name' => 'ملعقة ص',
                'description' => 'ملعقة صغيرة (5 مل تقريبًا)',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'ملعقة طعام',
                'short_name' => 'ملعقة ك',
                'description' => 'ملعقة طعام (15 مل تقريبًا)',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'أوقية',
                'short_name' => 'أوقية',
                'description' => 'أوقية (28.35 جرام تقريبًا)',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'رطل',
                'short_name' => 'رطل',
                'description' => 'رطل (453.59 جرام تقريبًا)',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'كيلوجرام',
                'short_name' => 'كجم',
                'description' => 'كيلوجرام (1000 جرام)',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'لتر',
                'short_name' => 'لتر',
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
