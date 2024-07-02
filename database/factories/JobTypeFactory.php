<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\JobType;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\JobType>
 */
class JobTypeFactory extends Factory
{
     /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = JobType::class;
    
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = [
        'Nursery Teacher', 
        'Early Years Educator', 
        'Special Educational Needs Coordinator', 
        'Nursery Manager', 
        'Assistant Nursery Teacher'
        ];

        return [
            'name' => $name[random_int(0, 4)],
            'description' => fake()->paragraph(),// Add job description
        ];
    } 
}
