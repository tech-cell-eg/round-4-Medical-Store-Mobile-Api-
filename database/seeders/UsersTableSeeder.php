<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'مدير النظام',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
            ],
            [
                'name' => 'أحمد محمد',
                'email' => 'ahmed@example.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
            ],
            [
                'name' => 'سارة أحمد',
                'email' => 'sara@example.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
            ],
            [
                'name' => 'محمد علي',
                'email' => 'mohamed@example.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
            ],
            [
                'name' => 'نورا خالد',
                'email' => 'nora@example.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
