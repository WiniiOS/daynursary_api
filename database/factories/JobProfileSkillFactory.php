<?php

namespace Database\Factories;

use App\Models\JobProfileSkill;
use App\Models\JobProfile;
use App\Models\Skill;
use Illuminate\Database\Eloquent\Factories\Factory;

class JobProfileSkillFactory extends Factory
{
    protected $model = JobProfileSkill::class;

    public function definition()
    {
        $jobProfile = JobProfile::inRandomOrder()->first();
        $skill = Skill::inRandomOrder()->first();

        if (!$jobProfile || !$skill) {
            $jobProfile = JobProfile::factory()->create();
            $skill = Skill::factory()->create();
        }

        return [
            'job_profile_id' => $jobProfile->id,
            'skill_id' => $skill->id,
            'skill_level' => $this->faker->randomElement(['Beginner', 'Intermediate', 'Advanced']),
        ];
    }
}