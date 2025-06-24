<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use App\Models\User;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Unit; // إضافة الموديل للوحدات
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DashboardStats extends BaseWidget
{
    protected static ?int $sort = 1;
    
    protected function getStats(): array
    {
        return [
            Stat::make('المستخدمين', User::count())
                ->description('إجمالي عدد المستخدمين')
                ->icon('heroicon-o-user')
                ->color('success'),
                
            Stat::make('المنتجات', Product::count())
                ->description('إجمالي عدد المنتجات')
                ->icon('heroicon-o-shopping-bag')
                ->color('warning'),
                
            Stat::make('الفئات', Category::count())
                ->description('إجمالي عدد الفئات')
                ->icon('heroicon-o-tag')
                ->color('primary'),
                
            Stat::make('العلامات التجارية', Brand::count())
                ->description('إجمالي عدد العلامات التجارية')
                ->icon('heroicon-o-building-storefront')
                ->color('info'),

            Stat::make('الوحدات', Unit::count())
                ->description('إجمالي عدد الوحدات')
                ->icon('heroicon-o-cube')
                ->color('secondary'),
        ];
    }
}
