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
            $file = request()->file('image_url');
            
            // التحقق من نوع الملف
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            if (!in_array($file->getMimeType(), $allowedTypes)) {
                // إذا كان نوع الملف غير مسموح، نظهر إشعار خطأ
                Notification::make()
                    ->danger()
                    ->title('خطأ في رفع الصورة')
                    ->body('نوع الملف غير مسموح. يجب أن تكون الصورة بصيغة JPG أو PNG أو GIF أو WEBP.')
                    ->send();
                
                // نحذف الصورة من البيانات
                unset($data['image_url']);
                return $data;
            }
            
            // التحقق من حجم الملف (الحد الأقصى 2 ميجابايت)
            $maxSize = 2048 * 1024; // 2 ميجابايت بالبايت
            if ($file->getSize() > $maxSize) {
                // إذا كان حجم الملف كبير جداً، نظهر إشعار خطأ
                Notification::make()
                    ->danger()
                    ->title('خطأ في رفع الصورة')
                    ->body('حجم الملف كبير جداً. يجب أن يكون حجم الصورة أقل من 2 ميجابايت.')
                    ->send();
                
                // نحذف الصورة من البيانات
                unset($data['image_url']);
                return $data;
            }
            
            try {
                // حذف الصورة القديمة إذا كانت موجودة
                if ($this->record->image_url) {
                    Storage::disk('public')->delete($this->record->image_url);
                }
                
                // رفع الصورة الجديدة
                $path = $file->store('products', 'public');
                $data['image_url'] = $path;
                
                // إشعار نجاح رفع الصورة
                Notification::make()
                    ->success()
                    ->title('تم رفع الصورة بنجاح')
                    ->send();
            } catch (\Exception $e) {
                // إشعار خطأ في رفع الصورة
                Notification::make()
                    ->danger()
                    ->title('خطأ في رفع الصورة')
                    ->body('حدث خطأ أثناء رفع الصورة. يرجى المحاولة مرة أخرى.')
                    ->send();
                
                // نحذف الصورة من البيانات
                unset($data['image_url']);
            }
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
