<?php

namespace App\Http\Controllers\Api\V1\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\StoreParentProfileRequest;
use App\Http\Requests\V1\uploadProfileRequest;
use App\Http\Resources\V1\ProfileResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\ParentProfile;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\Country;
use App\Models\City;
use App\Models\State;
use App\Http\Resources\V1\CountryResouce;
use App\Http\Resources\V1\StatesResource;
use App\Http\Resources\V1\CityResource;
use Illuminate\Support\Facades\Auth;
use App\Services\ProfileService;
use Illuminate\Support\Arr;

use function Laravel\Prompts\error;

class ProfileController extends Controller
{
    
    protected $profileService;

    public function __construct(ProfileService $profileService)
    {
        $this->profileService = $profileService;
    }

  
    public function upload(Request $request){

        $file = $request->file('image');
        $path = storeFile($file);  
         
         $user = Auth::user();
         $parent = $user->profile; 
         $parent->image = $path;
         $parent->save();
      

        return env("AWS_URL").'/'.$path;
    }


    public function createProfile(StoreParentProfileRequest $request){
        
        
        $data = $request->validated();

        $response = $this->profileService->createProfile($data, Auth::user()->id);
        return $response;
       
    }

    public function editProfile(StoreParentProfileRequest $request,$id){ 

          $data = $request->validated();
           $response = $this->profileService->updateProfile($data, $id );
           return $response;

       
    }

    public function getProfile($profile_id){ 
        
        $response = $this->profileService->getProfile($profile_id);
        return $response; 
        
    } 


    public function getProfileAll(){ 

        $profile = Auth::user()->profile;
        $response = $this->profileService->getProfileAll($profile->id);
        return $response; 
        
    } 

    


    public function getAuthProfile(){

        $profile = Auth::user()->profile;

         if($profile){   
        $response = $this->profileService->getProfile($profile->id);
        return $response;  
      }else{
        return response()->json(['errors' => 'profile not found'], 400);
      }

    }

    public function deleteProfile($id){

       $response = $this->profileService->deleteProfile($id);
       return $response;
    }
   

 //profile country, city, and state api  
 

    
    public function getCountries(){
        try{
            $countries = Country::all();
            return CountryResouce::collection($countries);

        }catch (\Exception $exception) {
            return response()->json('An error occurred', 500);
        }
    }

    //get a country

    public function getCountry($id){
        try{
            $country = Country::findOrFail($id);
        
            return new CountryResouce($country);
            
        }catch(ModelNotFoundException $exception){
            return response()->json('country not found',404);
        }catch (\Exception $exception) {
            return response()->json('An error occurred', 500);
        }

    }
    //get a state 

    public function getState($id){

        try{
            $state = State::findOrFail($id);
        
            return new StatesResource($state);
            
        }catch(ModelNotFoundException $exception){
            return response()->json(["status"=>"failed","message"=>"country not found"],404);
        }catch (\Exception $exception) {
            return response()->json(["status"=>"failed","message"=>"An unexpected error occured"], 500);
        }

    }

    public function getCountryStates($id){
        try{
            $country = Country::findOrFail($id);
            $states = $country->states;

            return StatesResource::collection($states);



        }catch(ModelNotFoundException $exception){
            
            return response()->json('country not found',404);
        }catch (\Exception $exception) {
            info($exception);
            return response()->json('An error occurred: '.$exception, 500);
        }
    }

    //get a city

    public function getCity($id){
        try{
            $city = City::findOrFail($id);
        
            return new CityResource($city);
            
        }catch(ModelNotFoundException $exception){
            
            return response()->json(["status"=>"failed","message"=>"city not found"],404);
        }catch (\Exception $exception) {
            info($exception);
            return response()->json(["status"=>"failed","message"=>"An unexpected error occured"], 500);
        }

    }

    public function getStateCities($id){
        try{
            $state = State::findOrFail($id);
            $cities = $state->cities;

            return CityResource::collection($cities);

        }catch(ModelNotFoundException $exception){
            return response()->json('country not found',404);
        }catch (\Exception $exception) {
            return response()->json('An error occurred: '.$exception, 500);
        }
    }

    //upload a profile(using a specific profile id)

    public function uploadProfile(Request $request,$id){
      
        $validate = Validator::make($request->all(), [
            'file' => 'required|image|mimes:jpeg,png,jpg,gif|max:5012',
        ]);

    
        if ($validate->fails()) {
            return response()->json(['errors' => error_processor($validate)], 422);
        }
  

      
        try{
          
            if ($request->hasFile('file') && $request->file('file')->isValid()) {
                $imagePath = $this->profileService->uploadProfile($request->file('file'),'parent_profiles');
                

            }
            
            Arr::set($data, 'image', $imagePath);
            $response = $this->profileService->updateProfile($data, $id );
            return $response;
        



        }catch(\Exception $exception){
            return response()->json(["message"=>'An error occurred: '.$exception], 500);

        }
    }


    public function applications(){ 

        $response = $this->profileService->getApplications();

        return  $response;  
    }
    

    public function application($id){
        
        $response = $this->profileService->getApplication($id);

        return  $response; 

    }


}
