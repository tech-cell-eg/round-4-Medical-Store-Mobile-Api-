<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use App\Models\User;
use App\Models\Category;
use App\Models\Brand;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            BaseWidget\Stat::make('المستخدمين', User::count())
                ->description('إجمالي عدد المستخدمين')
                ->icon('heroicon-o-user')
                ->color('success'),

            BaseWidget\Stat::make('الفئات', Category::count())
                ->description('إجمالي عدد الفئات')
                ->icon('heroicon-o-tag')
                ->color('primary'),

            BaseWidget\Stat::make('المكونات', \App\Models\Ingredient::count())
                ->description('إجمالي عدد المواد الفعالة')
                ->icon('heroicon-o-beaker')
                ->color('secondary'),

            BaseWidget\Stat::make('الوحدات', \App\Models\Unit::count())
                ->description('إجمالي عدد الوحدات')
                ->icon('heroicon-o-beaker')
                ->color('secondary'),


            // تم إزالة بطاقة المنتجات منخفضة المخزون بسبب إزالة جدول العبوات

            // المنتجات منتهية الصلاحية قريبًا
            BaseWidget\Stat::make('منتجات ستنتهي قريبًا', Product::where('expiry_date', '<', Carbon::now()->addDays(30))->count())
                ->description('منتجات ستنتهي صلاحيتها خلال 30 يومًا')
                ->icon('heroicon-o-clock')
                ->color('warning'),



            // Placeholder: المنتجات الأكثر مبيعًا (يتطلب جدول مبيعات/طلبات)
            BaseWidget\Stat::make('المنتجات الأكثر مبيعًا', '-')
                ->description('يتطلب ربط مع جدول الطلبات')
                ->icon('heroicon-o-fire')
                ->color('primary'),

        ];
    }
}
