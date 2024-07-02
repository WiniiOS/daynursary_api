<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\JobProfileImmunisation;


class JobProfileImmunisationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = JobProfileImmunisation::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    
    public function definition(): array
    {
        return [
            'job_profile_id' => 1,
            'immunisation_id' => 1,
            'vaccination_date' => 1
        ];
    } 
}
