<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * إضافة حقول السعر القديم والسعر الجديد وإزالة العلاقة مع العبوات
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // إضافة حقل السعر القديم
            $table->decimal('old_price', 10, 2)->nullable()->after('price');
            
            // إعادة تسمية حقل السعر الحالي إلى السعر الجديد
            $table->renameColumn('price', 'new_price');
        });
        
        // حذف جدول العبوات إذا كان موجوداً
        if (Schema::hasTable('packages')) {
            Schema::dropIfExists('packages');
        }
    }

    /**
     * Reverse the migrations.
     * إعادة التغييرات إلى الحالة السابقة
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // إعادة تسمية حقل السعر الجديد إلى السعر
            $table->renameColumn('new_price', 'price');
            
            // حذف حقل السعر القديم
            $table->dropColumn('old_price');
        });
        
        // لا يمكن إعادة إنشاء جدول العبوات بسهولة في التراجع
        // لأنه يتطلب معرفة الهيكل الكامل للجدول
    }
};
