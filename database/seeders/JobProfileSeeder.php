<?php

namespace Database\Seeders;

use App\Models\JobProfile;
use Illuminate\Database\Seeder;

class JobProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        JobProfile::factory()->count(5)->create();
    }
}
