<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ingredient;

class IngredientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ingredients = [
            [
                'name' => 'باراسيتامول',
                'description' => 'مادة فعالة مسكنة للألم وخافضة للحرارة'
            ],
            [
                'name' => 'كافيين',
                'description' => 'منبه للجهاز العصبي المركزي'
            ],
            [
                'name' => 'أموكسيسيلين',
                'description' => 'مضاد حيوي واسع المجال'
            ],
            [
                'name' => 'فيتامين سي',
                'description' => 'مضاد أكسدة يدعم جهاز المناعة'
            ],
            [
                'name' => 'زنك',
                'description' => 'معدن أساسي لوظائف الجسم المختلفة'
            ],
            [
                'name' => 'ألجينات الصوديوم',
                'description' => 'مادة تشكل حاجزًا على سطح المعدة لمنع ارتجاع الحمض'
            ]
        ];

        foreach ($ingredients as $ingredientData) {
            Ingredient::firstOrCreate(
                ['name' => $ingredientData['name']],
                ['description' => $ingredientData['description']]
            );
        }
    }
}
