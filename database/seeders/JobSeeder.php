<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory as FakerFactory;

use App\Models\Job;
use App\Models\Skill;
use App\Models\Certification;
use App\Models\Feature;
use Illuminate\Support\Str;

class JobSeeder extends Seeder
{
    public function run()
    {
        $faker = FakerFactory::create();

        for ($i = 0; $i < 10; $i++) { 
            $job = Job::create([
                'title' => $faker->jobTitle,
                'slug' => Str::slug($faker->jobTitle),
                'center_id' => $faker->numberBetween(2, 3),
                'job_type' => $faker->randomElement(['Full-time', 'Part-time', 'Contract']),
                'job_info' => $faker->paragraph,
                'service_to_render' => $faker->sentence,
                'start_date' => $faker->dateTimeBetween('-1 month', '+1 month'),
                'min_pay' => $faker->numberBetween(1000, 5000),
                'max_pay' => $faker->numberBetween(5000, 10000),
                'pay_type' => $faker->randomElement(['Hourly', 'Monthly']),
                'center_id' => 1,
                'benefits' => 'none',
                // 'eligibility' => $faker->sentence,
                'cover' => $faker->imageUrl(),
                'work_eligibility' => 'none',
            ]);

            // Attach fake data for relationships
            $job->skills()->attach(Skill::inRandomOrder()->limit(3)->pluck('id')->toArray());
            $job->certifications()->attach(Certification::inRandomOrder()->limit(2)->pluck('id')->toArray());
            $job->features()->attach(Feature::inRandomOrder()->limit(2)->pluck('id')->toArray());
        }
    }
}
