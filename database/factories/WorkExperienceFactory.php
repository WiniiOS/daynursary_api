<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\WorkExperience;


class WorkExperienceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = WorkExperience::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    
    public function definition(): array
    {
        return [
            'company_name' => Str::slug($this->faker->company),
            'description' => $this->faker->realText(200), // Add company description,
            'currently_working' => $this->faker->boolean, // Add fake boolean value
            'end_date' => $this->faker->date,
            'start_date' => $this->faker->date,
            'job_type_id' => 1,
            'job_profile_id' => 1,
            'role_id' => 1
        ];
    } 
}
