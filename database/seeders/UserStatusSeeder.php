<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       // Insert "active" status
       DB::table('statuses')->insert([
        'name' => 'active',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    // Insert "inactive" status
    DB::table('statuses')->insert([
        'name' => 'inactive',
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    }
}
