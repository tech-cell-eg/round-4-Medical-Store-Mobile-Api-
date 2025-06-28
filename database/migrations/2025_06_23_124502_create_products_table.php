<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            // إضافة حقل السعر القديم بناءا على طلب تيم الفرونت
            $table->decimal('old_price', 10, 2)->nullable()->default(1);

            //  بناءا على طلب تيم الفرونت إعادة تسمية حقل السعر الحالي إلى السعر الجديد
            $table->decimal('new_price', 10, 2)->nullable()->default(1);

            $table->integer('quantity')->default(0);
            $table->string('barcode')->unique()->nullable();
            $table->string('image_url')->nullable();
            $table->boolean('is_active')->default(true);
            $table->date('production_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->unsignedBigInteger('brand_id')->nullable();
            $table->unsignedBigInteger('unit_id')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            
            $table->dropForeign(['brand_id']);
            $table->dropForeign(['unit_id']);
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
        });

        Schema::dropIfExists('products');
    }
};
