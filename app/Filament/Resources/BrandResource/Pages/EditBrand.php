<?php

namespace App\Filament\Resources\BrandResource\Pages;

use App\Filament\Resources\BrandResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Storage;

class EditBrand extends EditRecord
{
    protected static string $resource = BrandResource::class;
    
    // لا نحتاج إلى دالة mutateFormDataBeforeSave لأن Filament يتعامل مع رفع الملفات تلقائيًا
    // عند استخدام مكون FileUpload

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
