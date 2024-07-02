<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Conversation;
use App\Models\User;
use App\Models\Center;
use App\Models\Job;

class ConversationSeeder extends Seeder
{
    public function run()
    {
      
        $this->seedUserParentCenterConversations();

        // Seed conversations involving job profiles
        $this->seedUserJobProfileJobConversations();
    }

   

    private function seedUserParentCenterConversations()
    {
        // Use existing IDs for users and parent profiles
        $userId = 14; // Replace with the actual user ID
        $parentId = 8; // Replace with the actual parent profile 
        $centerId = 1; // Replace with the actual center ID

        Conversation::factory()
            ->count(5)
            ->create([
                'sender_type' => 'App\Models\User',
                'recipient_type' => 'App\Models\Center',
                'sender_id' => $userId,
                'recipient_id' => $centerId, 
                'profile_type' => 'App\Models\ParentProfile',
                'profile_id' => $parentId,
            ]);
    }

    private function seedUserJobProfileJobConversations()
    {
        // Use existing IDs for users and job profiles
        $userId = 14; // Replace with the actual user ID
        $jobProfileId = 8; // Replace with the actual job profile ID
        $jobId = 10; // Replace with the actual job ID
    
        Conversation::factory()
            ->count(5)
            ->create([
                'sender_type' => 'App\Models\User',
                'recipient_type' => 'App\Models\Job',
                'sender_id' => $userId,
                'recipient_id' => $jobId, // Use the correct job ID
                'profile_type' => 'App\Models\JobProfile', // Corrected type
                'profile_id' => $jobProfileId,
                'job_id' => $jobId, // Use the correct job ID
            ]);
    }
    
}
