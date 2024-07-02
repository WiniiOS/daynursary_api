<?php

namespace Database\Seeders;

use App\Models\JobProfileSkill;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JobProfileSkillSeeder extends Seeder
{
    public function run()
    {
        JobProfileSkill::factory()->count(10)->create();
    }
}
