<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Storage;

class EditProduct extends EditRecord
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    
    protected function mutateFormDataBeforeSave(array $data): array
    {
        // معالجة رفع الملفات
        if (request()->hasFile('image_url')) {
            // حذف الصورة القديمة إذا كانت موجودة
            if ($this->record->image_url) {
                Storage::disk('public')->delete($this->record->image_url);
            }
            
            // رفع الصورة الجديدة
            $file = request()->file('image_url');
            $path = $file->store('products', 'public');
            $data['image_url'] = $path;
        }
        
        return $data;
    }
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    
    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('تم تحديث المنتج')
            ->body('تم تحديث بيانات المنتج بنجاح.');
    }
}
