<?php

namespace App\Http\Controllers\Api\V1\Conversation;

use App\Http\Controllers\Controller;
use App\Models\Center;
use App\Models\ConversationAttachment;
use App\Models\ConversationMessage;
use App\Models\User;
use App\Models\Job;
use App\Models\Profile;
use App\Models\ParentProfile;
use App\Models\JobProfile;
use App\Models\Conversation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Services\ConversationService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use App\Http\Resources\V1\ConversationResource;
use App\Http\Resources\V1\ConversationMessageResource;
use Illuminate\Http\Response as HTTP_STATUS;



class ConversationController extends Controller
{

    protected $conversationService;

    public function __construct(ConversationService $conversationService)
    {
        $this->conversationService = $conversationService;
    }



    public function getParentProfileConversations()
    {
        $parentProfile = Auth::user()->Profile;

        // Retrieve conversations associated with the parent profile
        $conversations = $parentProfile->conversations;

        $transformedConversations = ConversationResource::collection($conversations);

        return apiResponse($transformedConversations, 'Conversations', 200);
    }

    public function getJobProfileConversations()
    {
        $jobProfile = Auth::user()->JobProfile;

        // Retrieve conversations associated with the parent profile
        $conversations = $jobProfile->conversations;
        $transformedConversations = ConversationResource::collection($conversations);

        return apiResponse($transformedConversations, 'Conversations', 200);
    }



    public function getConversationMessages($conversationId)
    {
        $messages = Conversation::with(['messages.attachments', 'messages.user'])->find($conversationId);

        if ($messages->is_read === false || $messages->is_read === 0) {
            $messages->is_read = true ?? 1;
            $messages->save();
        }

        $messages =  ConversationMessageResource::collection($messages->messages);

        return Response::success($messages);
    }


    public function create(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'message' => 'required|string',
            'sender_type' => 'required|string',
            'sender_id' => 'required|integer',
            'recipient_type' => 'required|string',
            'recipient_id' => 'required|integer',
        ]);

        if ($validate->fails()) {
            return response()->json(['errors' => $validate->errors()], 422);
        }

        $conversation = new Conversation([
            'message' => $request->input('message'),
        ]);

        $sender = $this->getModelInstance($request->input('sender_type'), $request->input('sender_id'));
        $recipient = $this->getModelInstance($request->input('recipient_type'), $request->input('recipient_id'));

        $conversation->sender()->associate($sender);
        $conversation->recipient()->associate($recipient);

        // if ($request->input('type') === 'application') {
        //     $applicationDetails = $this->getApplicationDetails($request->all());
        //     $application = Application::create($applicationDetails);
        //     $conversation->application()->associate($application);
        //     $conversation->save();
        // }

        $conversation->save();

        return response()->json(['conversation' => $conversation]);
    }


    public function createParentConversation(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'title' => 'required|string',
            'center_id' => 'required|integer',
            'type' =>  'required|string',
            'application_id' => ($request->input('type') == 'application') ? 'required|integer' : '',
        ]);

        if ($validate->fails()) {
            return response()->json(['errors' => $validate->errors()], 422);
        }

        $response = $this->conversationService->createParentConversation($request->all());

        return apiResponse($response, 'Conversations', 201);
    }


    public function createParentProfileCenterMessage(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'message' => 'sometimes|string|nullable',
            'conversation_id' => 'required|integer',
            'files' => 'nullable|array',
            'files.*' => 'file',
        ]);

        if ($validate->fails()) {
            $errors = $validate->errors()->toArray(); // Convertir les erreurs en tableau
            info($errors);
            return response()->json(['errors' => $errors], 422);
        }

        // check if files are provided
        if ($request->hasFile('files')) {
            $files = $request->file('files');
        } else {
            $files = [];
        }

        $response = $this->conversationService->createParentProfileCenterMessage($request->all(), $files);

        return Response::success($response, HTTP_STATUS::HTTP_CREATED);
    }


    public function createJobConversation(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'title' => 'required|string',
            'job_id' => 'required|integer',
            'type' =>  'required|string',
            'application_id' => ($request->input('type') == 'application') ? 'required|integer' : '',
        ]);

        if ($validate->fails()) {
            return response()->json(['errors' => $validate->errors()], 422);
        }

        $response = $this->conversationService->createJobConversation($request->all());

        return apiResponse($response, 'Conversations', 201);
    }


    public function createJobProfileJobMessage(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'message' => 'required|string',
            'conversation_id' => 'required|integer',
            'files' => 'nullable|array',
            'files.*' => 'file',
        ]);

        if ($validate->fails()) {
            return response()->json(['errors' => $validate->errors()], 422);
        }

        // check if files are provided
        if ($request->hasFile('files')) {
            $files = $request->file('files');
        } else {
            $files = [];
        }

        $response = $this->conversationService->createJobProfileCenterMessage($request->all(), $files);

        return apiResponse($response, 'Conversations', 201);
    }


    public function createCenterParentProfileConversation(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'title' => 'required|string',
            'center_id' => 'required|integer',
            'profile_id' => 'required|integer',
            'type' =>  'required|string',
            'application_id' => ($request->input('type') == 'application') ? 'required|integer' : '',
        ]);

        if ($validate->fails()) {
            return response()->json(['errors' => $validate->errors()], 422);
        }

        $response = $this->conversationService->createCenterParentProfileConversation($request->all());

        return apiResponse($response, 'Conversations', 201);
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


    public function createJobJobProfileConversation(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'title' => 'required|string',
            'job_id' => 'required|integer',
            'job_profile_id' =>  'required|integer',
            'type' =>  'required|string',
            'application_id' => ($request->input('type') == 'application') ? 'required|integer' : '',
        ]);

        if ($validate->fails()) {
            return response()->json(['errors' => $validate->errors()], 422);
        }

        $response = $this->conversationService->createJobJobProfileConversation($request->all());

        return apiResponse($response, 'Conversations', 201);
    }



    public function createCenterParentProfileMessage(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'message' => 'required|string',
            'conversation_id' => 'required|integer',
            'center_id' => 'required|integer',
            'files' => 'nullable|array',
            'files.*' => 'file',
        ]);

        if ($validate->fails()) {
            return response()->json(['errors' => $validate->errors()], 422);
        }

        // check if files are provided
        if ($request->hasFile('files')) {
            $files = $request->file('files');
        } else {
            $files = [];
        }

        $response = $this->conversationService->createCenterParentProfileMessage($request->all(), $files);

        return apiResponse($response, 'Conversations', 201);
    }


    public function createJobJobProfileMessage(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'message' => 'required|string',
            'conversation_id' => 'required|integer',
            'job_id' => 'required|interger',
            'files' => 'nullable|array',
            'files.*' => 'file',
        ]);

        if ($validate->fails()) {
            return response()->json(['errors' => $validate->errors()], 422);
        }

        // check if files are provided
        if ($request->hasFile('files')) {
            $files = $request->file('files');
        } else {
            $files = [];
        }

        $response = $this->conversationService->createJobJobProfileMessage($request->all(), $files);

        return apiResponse($response, 'Conversations', 201);
    }
}
