<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            "name" => "Samir",
            "password" => Hash::make('password'),
            "phone" => "+201040729538",
        ]);

        UserProfile::create([
            'user_id' => $user->id,
            'first_name' => 'Samir',
            'last_name' => 'El Pheel',
        ]);
    }
}
