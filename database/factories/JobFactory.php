<?php

namespace Database\Factories;

use App\Models\Job;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Job>
 */
class JobFactory extends Factory
{
    protected $model = Job::class;

    public function definition()
    {
        return [
            'title' => $this->faker->jobTitle,
            'job_type' => $this->faker->randomElement(['Full-time', 'Part-time', 'Contract']),
            'job_info' => $this->faker->text,
            'service_to_render' => $this->faker->word,
            'start_date' => $this->faker->date(),
            'min_pay' => $this->faker->numberBetween(1000, 5000),
            'max_pay' => $this->faker->numberBetween(5000, 10000),
            'pay_type' => $this->faker->randomElement(['Hourly', 'Weekly', 'Monthly']),
            'about_applicant' => $this->faker->paragraph,
            'language' => $this->faker->languageCode,
            'eligibility' => $this->faker->sentence,
            'center_id' => optional(\App\Models\Center::inRandomOrder()->first())->id,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
