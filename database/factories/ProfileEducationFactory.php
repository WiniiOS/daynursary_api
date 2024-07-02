<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\ProfileEducation;


class ProfileEducationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ProfileEducation::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    
    public function definition(): array
    {
        $qualification = ['Computer Science', 'Software Engineering', 'Information Technology'];
        $field_of_study = [
            'Early Childhood Education',
            'Psychology',
            'Child Development',
            'Education',
            'Special Education',
            'Childcare Management',
        ];
        $school = [ 'Academy', 'School', 'Institution'];
        return [
            'qualification' =>  $qualification[random_int(0, 2)],
            'description' => $this->faker->realText(200), // Add company description,
            'currently_studying' => $this->faker->boolean, // Add fake boolean value
            'end_date' => $this->faker->date,
            'start_date' => $this->faker->date,
            'school' => $school[random_int(0, 2)], // Add fake school name using Faker
            'job_profile_id' => 1,
            'field_of_study' =>$field_of_study[random_int(0, 5)]
        ];
    } 
}
