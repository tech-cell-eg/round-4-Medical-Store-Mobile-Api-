<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Filament\Facades\Filament;
use Filament\Navigation\NavigationGroup;

class FilamentServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // تخصيص اللوجو في لوحة التحكم Filament
        Filament::serving(function () {
            // تعيين مجموعات التنقل
            Filament::registerNavigationGroups([
                NavigationGroup::make()
                    ->label('المنتجات والمخزون'),
                NavigationGroup::make()
                    ->label('إدارة المستخدمين'),
                NavigationGroup::make()
                    ->label('إعدادات النظام'),
            ]);

            // تعيين لوجو التطبيق
            if (file_exists(public_path('images/logo.png'))) {
                Filament::getPanel()
                    ->brandLogo(asset('images/logo.png'))
                    ->brandLogoHeight('2.5rem')
                    ->favicon(asset('images/favicon.ico'));
            }
        });
    }
}
