<?php

namespace Database\Seeders;

use App\Models\ParentProfile;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ParentProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ParentProfile::factory()->count(20)->create();
        
    }
}
