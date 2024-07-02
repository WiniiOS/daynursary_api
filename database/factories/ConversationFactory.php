<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Conversation;
use App\Models\User;
use App\Models\Center;
use App\Models\Job;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Conversation>
 */
 
 class ConversationFactory extends Factory
{
    protected $model = Conversation::class;

    public function definition()
    {
        // Define your conversation data here
        return [
            'message' => $this->faker->sentence,
            'sender_type' => User::class,
            'sender_id' => User::factory(),
            'recipient_type' => Center::class,
            'recipient_id' => Center::factory(),
            'center_id' => null,
            'job_type' => null,
            'profile_type' => null,
            'profile_id' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
