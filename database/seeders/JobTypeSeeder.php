<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JobType;

class JobTypeSeeder extends Seeder
{
    public function run()
    {
        $jobTypes = [
            ['name' => 'Full Time', 'description' => 'Full-Time job description'],
            ['name' => 'Part Time', 'description' => 'Part-Time job description'],
            ['name' => 'Contract Full-Time', 'description' => 'Contract Full-Time job description'],
            ['name' => 'Contract Part-Time', 'description' => 'Contract Part-Time job description'],
            ['name' => 'Temporary', 'description' => 'Temporary job description'],
            ['name' => 'Fixed Term Contract', 'description' => 'Fixed Term Contract job description'],
            ['name' => 'Casual', 'description' => 'Casual job description'],
        ];

        foreach ($jobTypes as $type) {
            JobType::create($type);
        }
    }
}
