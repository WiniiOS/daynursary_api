<?php

namespace Database\Seeders;

use App\Models\ProfileCertification;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProfileCertificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ProfileCertification::factory()->count(20)->create();
    }
}
