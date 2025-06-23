<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // إنشاء مستخدم مسؤول
        User::create([
            'first_name' => 'Admin',
            'last_name' => 'User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'phone' => '1234567890',
            'address' => '123 Main St',
            'is_active' => true,
            'role_id' => 1, // افتراضيًا، 1 هو معرف دور المسؤول
        ]);

        // إنشاء مستخدم عادي
        User::create([
            'first_name' => 'Normal',
            'last_name' => 'User',
            'email' => 'user@example.com',
            'password' => Hash::make('password'),
            'phone' => '0987654321',
            'address' => '456 Side St',
            'is_active' => true,
            'role_id' => 2, // افتراضيًا، 2 هو معرف دور المستخدم العادي
        ]);
    }
}
