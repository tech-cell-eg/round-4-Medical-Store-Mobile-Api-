<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use App\Models\User;
use App\Models\Category;
use App\Models\Brand;
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
                
            BaseWidget\Stat::make('المنتجات', Product::count())
                ->description('إجمالي عدد المنتجات')
                ->icon('heroicon-o-shopping-bag')
                ->color('warning'),
                
            BaseWidget\Stat::make('الفئات', Category::count())
                ->description('إجمالي عدد الفئات')
                ->icon('heroicon-o-tag')
                ->color('primary'),

            BaseWidget\Stat::make('المكونات', \App\Models\Ingredient::count())
                ->description('إجمالي عدد المواد الفعالة')
                ->icon('heroicon-o-beaker')
                ->color('secondary'),
        ];
    }
}
