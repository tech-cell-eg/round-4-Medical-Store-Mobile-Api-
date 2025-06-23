<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Unit;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Unit::firstOrCreate(['name' => 'Tablet', 'symbol' => 'Tab']);
        Unit::firstOrCreate(['name' => 'Capsule', 'symbol' => 'Cap']);
        Unit::firstOrCreate(['name' => 'Ointment', 'symbol' => 'Oint']);
        Unit::firstOrCreate(['name' => 'Syrup', 'symbol' => 'Syr']);
        Unit::firstOrCreate(['name' => 'Injection', 'symbol' => 'Inj']);
    }
}
