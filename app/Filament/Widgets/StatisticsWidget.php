<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use App\Models\User;
use App\Models\Category;
use App\Models\Brand;
use Filament\Widgets\Widget;

class StatisticsWidget extends Widget
{
    protected static string $view = 'filament.widgets.statistics-widget';
    
    protected static ?int $sort = 1;
    
    public function getViewData(): array
    {
        return [
            'userCount' => User::count(),
            'productCount' => Product::count(),
            'categoryCount' => Category::count(),
            'brandCount' => Brand::count(),
        ];
    }
}
