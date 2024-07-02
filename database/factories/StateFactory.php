<?php

namespace Database\Factories;

use App\Models\State;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Country;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\State>
 */
class StateFactory extends Factory
{
    protected $model = State::class;

    public function definition()
    {
        return [
            'name' => $this->faker->state,
            'created_at' => now(),
            'updated_at' => now(),
            'country_id' => Country::inRandomOrder()->first()->id ?? 1,
        ];
    }
}
