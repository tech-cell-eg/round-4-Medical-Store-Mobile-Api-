<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $medication = Category::firstOrCreate([
            'name' => 'Medication',
            'description' => 'Medications used to relieve pain.',
            'slug' => 'medication',
            'is_active' => true,
        ]);
        $devices = Category::firstOrCreate([
            'name' => 'Devices',
            'description' => 'Devices used to relieve pain.',
            'slug' => 'devices',
            'is_active' => true,
        ]);
        $supplies = Category::firstOrCreate([
            'name' => 'Supplies',
            'description' => 'Supplies used to relieve pain.',
            'slug' => 'supplies',
            'is_active' => true,
        ]);
        $categories = [
            ['parent_id' => $medication->id, 'name' => 'Painkillers', 'description' => 'Medications used to relieve pain.', 'slug' => 'painkillers', 'is_active' => true],
            ['parent_id' => $medication->id, 'name' => 'Skin Care', 'description' => 'Products for skin health and treatment.', 'slug' => 'skin-care', 'is_active' => true],
            ['parent_id' => $medication->id, 'name' => 'Antibiotics', 'description' => 'Drugs that fight bacterial infections.', 'slug' => 'antibiotics', 'is_active' => true],
            ['parent_id' => $medication->id, 'name' => 'Vitamins', 'description' => 'Supplements for nutritional support.', 'slug' => 'vitamins', 'is_active' => true],
            ['parent_id' => $medication->id, 'name' => 'Digestive Health', 'description' => 'Products for gastrointestinal issues.', 'slug' => 'digestive-health', 'is_active' => true],
            ['parent_id' => $medication->id, 'name' => "Pain Relief", 'description' => "Medications for pain management.", 'slug' => 'pain-relief', 'is_active' => true],

            // Medical Devices
            ['parent_id' => $devices->id, 'name' => "Monitoring Devices", "description" => "Devices for health monitoring (e.g., blood pressure, glucose).", 'slug' => 'monitoring-devices', 'is_active' => true],
            ['parent_id' => $devices->id, 'name' => "Mobility Aids", "description" => "Products to assist with movement (e.g., crutches, wheelchairs).", 'slug' => 'mobility-aids', 'is_active' => true],
            ['parent_id' => $devices->id, 'name' => "Wound Care", "description" => "Supplies for treating wounds (e.g., bandages, antiseptic).", 'slug' => 'wound-care', 'is_active' => true],
            ['parent_id' => $devices->id, 'name' => "Diagnostic Tools", "description" => "Instruments for medical diagnosis.", 'slug' => 'diagnostic-tools', 'is_active' => true],
            ['parent_id' => $devices->id, 'name' => "Surgical Instruments", "description" => "Tools used in surgical procedures.", 'slug' => 'surgical-instruments', 'is_active' => true],

            // Medical Supplies
            ['parent_id' => $supplies->id, 'name' => "First Aid", "description" => "Products for immediate medical care (e.g., bandages, antiseptic).", 'slug' => 'first-aid', 'is_active' => true],
            ['parent_id' => $supplies->id, 'name' => "Disinfectants", "description" => "Products to kill bacteria and viruses (e.g., alcohol, bleach).", 'slug' => 'disinfectants', 'is_active' => true],
            ['parent_id' => $supplies->id, 'name' => "Syringes", "description" => "Devices for administering medications (e.g., insulin, vaccines).", 'slug' => 'syringes', 'is_active' => true],
            ['parent_id' => $supplies->id, 'name' => "Gauze", "description" => "Products for bandaging wounds (e.g., bandages, antiseptic).", 'slug' => 'gauze', 'is_active' => true],
            ['parent_id' => $supplies->id, 'name' => "Surgical Supplies", "description" => "Products for surgical procedures (e.g., scalpels, needles).", 'slug' => 'surgical-supplies', 'is_active' => true],

        ];

        // إنشاء التصنيفات الجديدة
        foreach ($categories as $category) {
            // التأكد من وجود slug لكل تصنيف
            if (!isset($category['slug']) || empty($category['slug'])) {
                // إنشاء slug تلقائيًا من اسم التصنيف
                $category['slug'] = \Illuminate\Support\Str::slug($category['name']);
            }

            // التأكد من وجود is_active لكل تصنيف
            if (!isset($category['is_active'])) {
                $category['is_active'] = true;
            }

            Category::create($category);
        }
    }
}
