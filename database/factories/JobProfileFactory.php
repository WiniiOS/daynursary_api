<?php

namespace Database\Factories;

use Faker\Core\Uuid;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\JobProfile;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class JobProfileFactory extends Factory
{
    protected $model = JobProfile::class;

    public function definition()
    {
        return [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'email' => $this->faker->unique()->safeEmail,
            // 'uuid' => Str::uuid()->toString(),
            'pronoun' => $this->faker->randomElement(['He', 'She', 'They']),
            'dob' => $this->faker->date(),
            'phone_number' => $this->faker->phoneNumber,
            'address' => $this->faker->address,
            'post_code' => $this->faker->postcode,
            'work_eligibility' => $this->faker->randomElement(['Eligible', 'Not Eligible']),
            'languages' => $this->faker->randomElement(['English', 'French', 'Spanish']),
            'logo' => 'path_to_your_logo',
            'cover' => 'path_to_your_cover_image',
            'user_id' => function () {
                return \App\Models\User::factory()->create()->id;
            },
            'open_to_opportunities' => $this->faker->boolean,
            'actively_looking' => $this->faker->boolean,
            'country_id' => $this->faker->numberBetween(1, 9), // Assuming you have a country table with IDs
            'state_id' => $this->faker->numberBetween(1, 9), // Assuming you have a state table with IDs
            'city_id' => $this->faker->numberBetween(1, 9), // Assuming you have a city table with IDs
        ];
    }
}
