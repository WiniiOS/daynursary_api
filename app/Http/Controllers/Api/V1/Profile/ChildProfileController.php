<?php

namespace App\Http\Controllers\Api\V1\Profile;

use App\Models\ParentChild;
use Illuminate\Http\Request;
// use App\Services\ProfileService;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\ProfileChildRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Services\ProfileService;  
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ChildProfileController extends Controller
{  
     

    protected $profileService;

    public function __construct(ProfileService $profileService)
    {
        $this->profileService = $profileService;
    }



    public function createdProfile(Request $request)
    {
        
    

        $validate = Validator::make($request->all(), [
           
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'dob' => 'required|date',
            'gender' => 'required|in:boy,girl',
            'centrelink' => 'nullable|string',
            'child_allergies' => 'nullable|string',
            'special_needs' => 'nullable|string',
        ],
        );



        $validation_errors = $validate->errors();

        if (!$validate->fails()) {

            $user = Auth::user()->profile;


            $response = $this->profileService->createChildProfile($request, $user->id);
          
            return $response;
        } else {

            return response()->json(['errors' => error_processor($validate)], 422);
        }



      
       

    }

  
    public function updateProfile(Request $request, $id){

      

        $validate = Validator::make($request->all(), [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'dob' => 'required|date',
            'gender' => 'required|in:Boy,Girl',
            'centrelink' => 'nullable|string',
            'child_allergies' => 'nullable|string',
            'special_needs' => 'nullable|string',
        ],
        );

        

        $validation_errors = $validate->errors();

        if (!$validate->fails()) {

            $user = Auth::user()->profile;


            $response = $this->profileService->updateChildProfile($request, $id);
           
             
            return $response;
        } else {

            return response()->json(['errors' => error_processor($validate)], 422);
        }


      
    }



    public function getProfile($id){

        $response = $this->profileService->getChildProfile($id);
        return $response;
    }

    public function getChild($id){

        $response = $this->profileService->getAllChildProfile($id);
        return $response;
    } 

    public function getAuthChildrenProfiles(){
        $user = Auth::user()->profile;

      
       
         $response = $this->profileService->getAllChildProfile($user->id);
         return $response;
    }

    public function deleteProfile($id)
    {
        $response = $this->profileService->deleteChildProfile($id);
        return $response;
    }

    public function uploadImage(Request $request){
        dd($request->all());
    }
  
}
