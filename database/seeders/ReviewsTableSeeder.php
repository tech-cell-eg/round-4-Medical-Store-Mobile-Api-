<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Review;
use App\Models\User;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class ReviewsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // حذف جميع التقييمات القديمة
        DB::table('reviews')->truncate();

        $users = User::all();
        $products = Product::all();

        // إذا لم يوجد مستخدمون أو منتجات، لا يتم الإدخال
        if ($users->count() === 0 || $products->count() === 0) {
            return;
        }

        // إضافة تقييمات تجريبية
        foreach ($products as $product) {
            foreach ($users->random(min(3, $users->count())) as $user) {
                Review::create([
                    'user_id'    => $user->id,
                    'product_id' => $product->id,
                    'rating'     => rand(3, 5),
                    'comment'    => 'منتج ممتاز جداً! تم الإدخال تلقائياً.',
                    'review_date' => now(),
                ]);
            }
        }
    }
}
