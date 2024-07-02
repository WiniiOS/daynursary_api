<?php

namespace Database\Factories;

use App\Models\SkillType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SkillType>
 */
class SkillTypeFactory extends Factory
{
    protected $model = SkillType::class;

    public function definition()
    {
        return [
            // Define the attributes for SkillType model here
            'name' => $this->faker->word,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
