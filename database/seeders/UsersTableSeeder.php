<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserProfile;
use Database\Factories\UserFactory;
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
        $user = User::create([
            "phone" => "+201040729538",
        ]);

        UserProfile::create([
            'user_id' => $user->id,
            'first_name' => 'Unknown',
            'last_name' => 'Unknown',
        ]);
    }
}
