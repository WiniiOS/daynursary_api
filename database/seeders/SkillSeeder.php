<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory as FakerFactory;
use App\Models\Skill;

class SkillSeeder extends Seeder
{
    public function run()
    {
        $faker = FakerFactory::create();

        for ($i = 0; $i < 5; $i++) { // Change 5 to the number of fake records you want to generate
            $skill = Skill::create([
                'name' => $faker->word,
                'skill_type_id' => $faker->numberBetween(1, 3),
            ]);

          
        }
    }
}
