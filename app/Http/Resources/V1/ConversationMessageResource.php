<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;


class ConversationMessageResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user' => $this->transformUser(),
            'message' => $this->message,
            'status' => $this->status,
            'attachments' => $this->transformAttachments(),
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
        ];
    }

    protected function transformUser()
    {
       
        
        $sender = $this->user; 
        if(!$sender){
            return null;
        }
        // Check sender type
        switch ($sender->getMorphClass()) {
            case 'App\Models\ParentProfile':
                return $this->transformParentUser($sender);
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

    protected function transformAttachments()
    {
        return $this->attachments->map(function ($attachment) {
            return [
                'id' => $attachment->id,
                'file_name' => $attachment->file_name,
                'file_type' => $attachment->file_type,
                'file_path' => $this->getAttachmentUrl($attachment->file_path),
            ];
        });
    }

    protected function getAttachmentUrl($filePath)
    {
        $baseUrl = env('AWS_URL');
        return $baseUrl . '/' . $filePath;
    }



    protected function transformParentUser($user)
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



}
