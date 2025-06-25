<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $admins = [
            [
                'name' => 'Admin One',
                'email' => 'admin1@example.com',
                'password' => Hash::make('password123'),
                'phone' => '0100000001',
                'is_active' => 1,
            ],
            [
                'name' => 'Admin Two',
                'email' => 'admin2@example.com',
                'password' => Hash::make('password123'),
                'phone' => '0100000002',
                'is_active' => 1,
            ],
            [
                'name' => 'Admin Three',
                'email' => 'admin3@example.com',
                'password' => Hash::make('password123'),
                'phone' => '0100000003',
                'is_active' => 1,
            ],
            [
                'name' => 'Admin Four',
                'email' => 'admin4@example.com',
                'password' => Hash::make('password123'),
                'phone' => '0100000004',
                'is_active' => 1,
            ],
            [
                'name' => 'Admin Five',
                'email' => 'admin5@example.com',
                'password' => Hash::make('password123'),
                'phone' => '0100000005',
                'is_active' => 1,
            ],
        ];

        foreach ($admins as $admin) {
            Admin::updateOrCreate([
                'email' => $admin['email'],
            ], $admin);
        }
    }
}
