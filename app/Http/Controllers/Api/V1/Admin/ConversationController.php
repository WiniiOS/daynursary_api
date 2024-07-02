<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Center; 
use App\Models\CenterAdmin;
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



    public function getCenterAdminConversations()
    { 
        $admin =  request()->vendor;

        $centerAdmin = CenterAdmin::findOrFail($admin->id);

        // Retrieve conversations related to Centers
       // $centerConversations = $centerAdmin->conversations()->latest()->paginate(1); 
        $centerConversations = $centerAdmin->conversations()->latest()->get(); 
        $transformedConversations = ConversationResource::collection($centerConversations)->response()->getData(true);
       

       // $jobConversations = $centerAdmin->conversationsFromJobs()->latest()->paginate(10);

       return Response::success($transformedConversations);
   
    }

  


}
