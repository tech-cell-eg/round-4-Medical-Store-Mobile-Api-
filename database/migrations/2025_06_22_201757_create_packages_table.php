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
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->string('size'); // حجم العبوة (مثال: 500 pellets, 110 pellets, 300 pellets)
            $table->decimal('price', 8, 2); // سعر العبوة (مثال: Rs.106, Rs.166, Rs.252)
            $table->string('sku')->unique()->nullable(); // رمز تعريف المنتج لحجم معين (Stock Keeping Unit)
            $table->string('barcode')->unique()->nullable(); // رمز التعريف (Barcode)
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};
