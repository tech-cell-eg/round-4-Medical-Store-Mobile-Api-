<?php

namespace App\Filament\Resources\BrandResource\Pages;

use App\Filament\Resources\BrandResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateBrand extends CreateRecord
{
    protected static string $resource = BrandResource::class;
    
    // لا نحتاج إلى دالة mutateFormDataBeforeCreate لأن Filament يتعامل مع رفع الملفات تلقائيًا
    // عند استخدام مكون FileUpload
}
