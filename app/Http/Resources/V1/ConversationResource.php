<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class ConversationResource extends JsonResource  
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'center' => $this->center,
            'type' => $this->type,
            'is_read' => $this->is_read,
            'sender_type' => $this->sender_type,
            'recipient_type' => $this->recipient_type,
            'application_type' => $this->application_type,
             'sender' => $this->transformSender(),
             'recipient' => $this->transformRecipient(),
            'application' => $this->transformApplication(),
             'messages'=> $this->messages->count()? new ConversationMessageResource($this->messages->last()):'',
           
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
        ]; 
    }

    protected function transformSender()
    {
        $sender = $this->sender;
        // Check sender type
        switch ($sender->getMorphClass()) {
            case 'App\Models\ParentProfile':
                return $this->transformUser($sender);
            case 'App\Models\Center':
                return $this->transformCenter($sender);
            case 'App\Models\Job':
                return $this->transformJob($sender);
            case 'App\Models\JobProfile':
                return $this->transformJobUser($sender);
            default:
                // Handle unknown sender type
                return null;
        }
    }
    

    protected function transformRecipient()
    {
        $recipient = $this->recipient;
        // Check sender type
        switch ($recipient->getMorphClass()) {
            case 'App\Models\ParentProfile':
                return $this->transformUser($recipient);
            case 'App\Models\Center':
                return $this->transformCenter($recipient);
            case 'App\Models\Job':
                return $this->transformJob($recipient);
            case 'App\Models\JobProfile':
                return $this->transformJobUser($recipient);
            default:
                // Handle unknown sender type
                return null;
        }
    }



    protected function transformUser($user)
    {
        
        return [
            'id' => $user->id,
            'name' => $user->first_name.' '. $user->last_name,
            'avatar' => env("AWS_URL").'/'.$user->image,
        ];
    }


    protected function transformJobUser($user)
    {
        
        return [
            'id' => $user->id,
            'name' => $user->first_name.' '. $user->last_name,
            'avatar' => env("AWS_URL").'/'.$user->logo,
        ];
    }

    protected function transformCenter($center)
    {
        // Customize this based on your Center model structure
        return [
            'id' => $center->id,
            'name' => $center->name, 
            'avatar' => $center->logo
            // Add other center fields as needed
        ];
    }

    protected function transformJob($job)
    {
        // Customize this based on your Job model structure
        return [
            'id' => $job->id,
            'title' => $job->title,
            'avatar' => $job->cover
            // Add other job fields as needed
        ];
    }

    protected function transformApplication()
    {
        $application = $this->application; 
       
        if(!$application) {
            return null;
        }
        return [
            'id' => $application->id,
            'name' => $application->name,
            'status' => $application->status
        ];
    }


}

