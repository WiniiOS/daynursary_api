<?php

namespace App\Services;

use App\Models\Job;
use App\Models\User;
use Ramsey\Uuid\Uuid;
use App\Models\Center;
use App\Models\JobProfile;
use App\Traits\Transformer;
use App\Models\Conversation;
use App\Models\ParentProfile;
use App\Models\JobApplication;
use App\Models\ConversationMessage;
use Illuminate\Support\Facades\Log;
use App\Models\ChildCareApplication;
use Illuminate\Support\Facades\Auth;
use App\Models\ConversationAttachment;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ConversationService
{



    public function createParentConversation($request)
    {


        $user = Auth::user();
        $profile = $user->profile;

        $center = Center::findOrFail($request['center_id']);

        $conversation = new Conversation([
            'title' => $request['title'],
            'type' => $request['type'],
        ]);

        $conversation->sender()->associate($user);
        $conversation->recipient()->associate($center);

        $conversation->save();

        if ($request['type'] === 'application') {

            $application = ChildCareApplication::findOrFail($request['application_id']);

            $conversation->application()->associate($application);
            $conversation->save();
        }


        $conversation->save();

        return $conversation;
    }


    public function createParentProfileCenterMessage($request, $files)
    {

        $user = Auth::user()->Profile;


        $conversation = new ConversationMessage([
            'message' => $request['message'],
            'conversation_id' => $request['conversation_id']
        ]);

        $conversation->user()->associate($user);
        $conversation->save();

        $attachments = [];

        $messages = Conversation::find($request['conversation_id']);
        
        if ($messages->is_read === false) {
            $messages->is_read = true;
            $messages->save();
        }

        // Handle multiple file uploads if files are provided
        foreach ($files as $file) {
            $attachment = new ConversationAttachment([
                'conversation_message_id' => $conversation->id,
                'file_name' => $file->getClientOriginalName(),
                'file_type' => $file->getClientMimeType(),
            ]);
            $attachment->storeFile($file);
            $attachment->save();
        }

        // Recover messages with attachments and associated user
        $conversation->load(['attachments', 'user']);

        // Add attachments to the message
        $conversation->attachments = $attachments;

        return $conversation;
    }


    public function createJobConversation($request)
    {


        $user = Auth::user()->JobProfile;

        $job = Job::findOrFail($request['job_id']);

        $conversation = new Conversation([
            'title' => $request['title'],
            'type' => $request['type'],
        ]);

        $conversation->sender()->associate($user);
        $conversation->recipient()->associate($job);

        $conversation->save();


        if ($request['type'] === 'application') {

            $application = JobApplication::findOrFail($request['application_id']);

            $conversation->application()->associate($application);
            $conversation->save();
        }


        $conversation->save();

        return $conversation;
    }


    public function createJobProfileCenterMessage($request, $files)
    {

        $user = Auth::user()->JobProfile;


        $conversation = new ConversationMessage([
            'message' => $request['message'],
            'conversation_id' => $request['conversation_id']
        ]);

        $conversation->user()->associate($user);

        $conversation->save();

        // Handle multiple file uploads if files are provided


        foreach ($files as $file) {
            $attachment = new ConversationAttachment([
                'conversation_message_id' => $conversation->id,
                'file_name' => $file->getClientOriginalName(),
                'file_type' => $file->getClientMimeType(),
            ]);

            $attachment->storeFile($file);
            $attachment->save();
        }


        return $conversation;
    }




    public function createCenterParentProfileConversation($request)
    {


        $user = ParentProfile::findOrFail($request['parent_id']);

        $center = Center::findOrFail($request['center_id']);

        $conversation = new Conversation([
            'title' => $request['title'],
            'type' => $request['type'],
        ]);

        $conversation->sender()->associate($center);
        $conversation->recipient()->associate($user);

        $conversation->save();


        if ($request['type'] === 'application') {

            $application = ChildCareApplication::findOrFail($request['application_id']);

            $conversation->application()->associate($application);
            $conversation->save();
        }


        $conversation->save();

        return $conversation;
    }




    public function createJobJobProfileConversation($request)
    {


        $user = JobProfile::findOrFail($request['job_profile_id']);

        $job = Job::findOrFail($request['job_id']);

        $conversation = new Conversation([
            'title' => $request['title'],
            'type' => $request['type'],
        ]);

        $conversation->sender()->associate($job);
        $conversation->recipient()->associate($user);

        $conversation->save();


        if ($request['type'] === 'application') {

            $application = JobApplication::findOrFail($request['application_id']);

            $conversation->application()->associate($application);
            $conversation->save();
        }


        $conversation->save();

        return $conversation;
    }




    public function createCenterParentProfileMessage($request, $files)
    {

        $user = Center::findOrFail($request['center_id']);


        $conversation = new ConversationMessage([
            'message' => $request['message'],
            'conversation_id' => $request['conversation_id']
        ]);

        $conversation->user()->associate($user);

        $conversation->save();

        // Handle multiple file uploads if files are provided
        foreach ($files as $file) {
            $attachment = new ConversationAttachment([
                'conversation_message_id' => $conversation->id,
                'file_name' => $file->getClientOriginalName(),
                'file_type' => $file->getClientMimeType(),
            ]);

            $attachment->storeFile($file);
            $attachment->save();
        }

        return $conversation;
    }



    public function createJobJobProfileMessage($request, $files)
    {

        $user = Job::findOrFail($request['job_id']);


        $conversation = new ConversationMessage([
            'message' => $request['message'],
            'conversation_id' => $request['conversation_id']
        ]);

        $conversation->user()->associate($user);

        $conversation->save();

        // Handle multiple file uploads if files are provided


        foreach ($files as $file) {
            $attachment = new ConversationAttachment([
                'conversation_message_id' => $conversation->id,
                'file_name' => $file->getClientOriginalName(),
                'file_type' => $file->getClientMimeType(),
            ]);

            $attachment->storeFile($file);
            $attachment->save();
        }


        return $conversation;
    }


    private function getModelInstance($modelType, $modelId)
    {
        switch ($modelType) {
            case 'App\Models\User':
                return User::findOrFail($modelId);
            case 'App\Models\Center':
                return Center::findOrFail($modelId);
            case 'App\Models\Job':
                return Job::findOrFail($modelId);
            case 'App\Models\ParentProfile':
                return ParentProfile::findOrFail($modelId);
            case 'App\Models\JobProfile':
                return JobProfile::findOrFail($modelId);
            default:
                abort(400, 'Invalid model type');
        }
    }
}
