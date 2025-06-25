<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Storage;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;
    
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // معالجة رفع الملفات
        if (request()->hasFile('image_url')) {
            $file = request()->file('image_url');
            $path = $file->store('products', 'public');
            $data['image_url'] = $path;
        } else {
            // إذا لم يتم رفع ملف، نحذف الحقل من البيانات
            unset($data['image_url']);
        }
        
        return $data;
    }
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    
    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('تم إنشاء المنتج')
            ->body('تم إنشاء المنتج بنجاح.');
    }
}
