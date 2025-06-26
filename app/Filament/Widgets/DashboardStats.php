<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use App\Models\User;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Unit; // إضافة الموديل للوحدات
use Illuminate\Support\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DashboardStats extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        return [];
    }
}
