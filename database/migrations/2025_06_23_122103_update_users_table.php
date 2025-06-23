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
        Schema::table('users', function (Blueprint $table) {
            // إضافة الحقول الجديدة
            $table->string('first_name')->after('id');
            $table->string('last_name')->after('first_name');
            $table->string('phone')->unique()->nullable()->after('email');
            $table->text('address')->nullable()->after('phone');
            $table->boolean('is_active')->default(true)->after('address');
            $table->timestamp('last_login_at')->nullable()->after('is_active');
            $table->unsignedBigInteger('role_id')->nullable()->after('last_login_at');
            
            // تعديل الحقول الموجودة
            $table->dropColumn('name'); // حذف حقل name القديم
            
            // إضافة مفاتيح خارجية
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // حذف المفاتيح الخارجية أولاً
            $table->dropForeign(['role_id']);
            
            // حذف الحقول المضافة
            $table->dropColumn([
                'first_name',
                'last_name',
                'phone',
                'address',
                'is_active',
                'last_login_at',
                'role_id'
            ]);
            
            // إعادة حقل name
            $table->string('name')->after('id');
        });
    }
};
