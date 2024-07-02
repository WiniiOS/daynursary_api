<?php 
namespace App\Services;
use App\Models\Country;
use App\Models\City;
use App\Models\State;
use App\Models\ParentProfile;
use App\Models\ParentChild;
use App\Models\Center;
use App\Models\CenterAdmin;
use App\Models\CentersClaim;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\User;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\Hash; 
use App\Http\Resources\V1\ProfileResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Mail\VerifyEmail;
use App\Mail\VerifyCenter;
use Illuminate\Support\Facades\Mail;
use App\Http\Resources\V1\ParentChildResource; 
use App\Http\Resources\V1\ApplicationResource;
use App\Models\ChildCareApplication;
use Illuminate\Support\Facades\Auth;

class ProfileService
{   
    public function createUserProfile($data){
    
        try {   
            $user = User::create([
                'uuid' => Uuid::uuid4(),
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'status_id' => 1,
                'password' => Hash::make($data['password']), 
            ]);

            $user->assignRole("user");  
            $user->profile()->create($data); 
            $user->jobProfile()->create($data);


             // Generate verification token
            $verificationToken = rand(1000, 9999);

            // Store the verification token
            DB::table('email_verifications')->insert([
                'user_id' => $user->id,
                'token' => $verificationToken,
                'created_at' => now(),
            ]);

            // Send verification email
            try{ 
            Mail::to($user->email)->send(new VerifyEmail($verificationToken));
        }catch (\Exception $exception) {
           
            info($exception);
        }


           
            return response()->json([
                'message' => 'success',
                'user' => $user
            ], 201); 

        } catch (\Exception $exception) {
            return response()->json(['errors' => 'An error occurred ' . $exception], 500);
        }
    }


    public function createProfile($data, $userId){
        // Your logic to create user profile
        try{ 

           $profile = ParentProfile::create($data + ['parent_id' => $userId]);
           
            return response()->json([
                'status'=>'success',
                'message' => 'profile successfully created',
                'profile' => $profile
            ], 201); 


        }catch (\Exception $exception) {
            return response()->json(['errors' => 'An error occurred ' . $exception], 500);
        }
        
    }



    public function getProfile($profile_id)
    {
        try{
             
            
            $profile = ParentProfile::getProfileWithRelationships($profile_id);

            return response()->json(['profile' => new ProfileResource($profile)], 200);

            
        }catch(ModelNotFoundException $exception){ 

          
            return response([ 'errors' => 'profile not found', 'message' => 'profile not found'], 400);

        }catch (\Exception $exception) {

            return response()->json(['errors' => 'An error occurred ' . $exception], 500);

        }
        
    }   
   

    public function getProfileAll($profile_id)
    {
        try{
             
            
            $profile = ParentProfile::getProfileWithRelationships($profile_id);

            return response()->json(['profile' => new ProfileResource($profile)], 200);

            
        }catch(ModelNotFoundException $exception){ 

          
            return response([ 'errors' => 'profile not found', 'message' => 'profile not found'], 400);

        }catch (\Exception $exception) {

            return response()->json(['errors' => 'An error occurred ' . $exception], 500);

        }
        
    }   

    

    public function updateProfile($data, $userId)
    {
        // Your logic to update user profile data  

        try{ 

            $parent_profile = ParentProfile::findOrFail($userId);
            $datas = array_filter($data);
            $parent_profile->update($datas);
 
            return response(['data' => ['profile' => $parent_profile],'status'=>'success', 'message' => 'profile updated successfully'], 200);
            
        }catch(ModelNotFoundException $exception){
           
            return response(['error' => 'profile not found', 'message' => 'profile not found'], 404);
        }catch (\Exception $exception) { 
            return response(['error' => 'An error occurred '.$exception, 'message' => 'An error occurred'], 500);
           
        }

    } 


    public function deleteProfile($userId){

        try{
            $parent_profile = ParentProfile::findOrFail($userId);
        
            $parent_profile->delete();
            return response([ 'message' => 'profile not found'], 200);
           
            
        }catch(ModelNotFoundException $exception){
           
            return response([ 'errors' => 'profile not found', 'message' => 'profile not found'], 400);
            
        }catch (\Exception $exception) {
           
            return response([ 'errors' => 'An error occurred', 'message' => 'An error occurred'], 500); 
        }
    } 
    

    public function createChildProfile($data, $userId){  

     
      $parent = Auth::user()->profile;

      if($parent == null){

       
        return apiResponse(['errors' => 'profile not found'], 'Profile not found', 500); 

      } else{

        try { 
         
            $imageFile = $data->file('image');

            
            if ($imageFile) {
              
                $imagePath = storeFile($imageFile);
                
            } else {
                
                $imagePath = null; 
            }


      
         

         $childProfile = $parent->children()->create(array_merge($data->all(), ['image' => $imagePath]));

        
         return apiResponse(['profiles' => $childProfile], 'An error occurred', 201); 

       } catch (\Exception $exception) {
            
           return apiResponse(['errors' => $exception], 'An error occurred', 500); 
           
   
       }  

      }
     
       
    }   

    public function getAllChildProfile($profile_id){
        try{  
            
           
            $parent = ParentProfile::find($profile_id);
            $profile = $parent->children;
            
           
            
            return response()->json([
                'profiles' => ParentChildResource::collection($profile)
            ], 200);

            
        }catch(ModelNotFoundException $exception){ 

          
            return response([ 'errors' => 'profile not found', 'message' => 'profile not found'], 400);

        }catch (\Exception $exception) {

            return response()->json(['errors' => 'An error occurred ' . $exception], 500);

        }
      }  



    public function updateChildProfile($data, $childId)
    {
        // Your logic to update user profile data  

        try{  



            $parent_child = ParentChild::findOrFail($childId);

            $imageFile = $data->file('image');

            
            if ($imageFile) {
              
                $imagePath = storeFile($imageFile);
                $parent_child->image = $imagePath;
                $parent_child->save();
                
            } else {
                
                $imagePath = null; 
            }




           
            $parent_child->update($data->all());
 
            return response(['data' => ['profile' => $parent_child], 'message' => 'profile updated successfully'], 200);
            
        }catch(ModelNotFoundException $exception){
           
            return response(['error' => 'profile not found', 'message' => 'profile not found'], 404);

        }catch (\Exception $exception) { 

            return response(['error' => 'An error occurred', 'message' => 'An error occurred '.$exception], 500);
           
        }

    } 
    


    public function deleteChildProfile($userId){

        try{
            $parent_profile = ParentChild::findOrFail($userId);
        
            $parent_profile->delete();
            return response([ 'message' => 'profile not found'], 200);
           
            
        }catch(ModelNotFoundException $exception){
           
            return response([ 'errors' => 'profile not found', 'message' => 'profile not found'], 400);
            
        }catch (\Exception $exception) {
           
            return response([ 'errors' => 'An error occurred', 'message' => 'An error occurred'], 500); 
        }
    } 
    

    
    public function getChildProfile($userId)
    {
        try{

            $parent_profile = ParentChild::findOrFail($userId);
         
            return response()->json(['profile'=>$parent_profile], 200); 
            
            
        }catch(ModelNotFoundException $exception){ 

          
            return response([ 'errors' => 'profile not found', 'message' => 'profile not found'], 400);

        }catch (\Exception $exception) {

            return response()->json(['errors' => 'An error occurred ' . $exception], 500);

        }
        
    }   


  
    
//  public function claimCenter($data , $slug) {

   

//     try {  

//         $center = Center::where('slug', $slug)->first();  
       

//         if($center){ 

//         $user = CentersClaim::create([
//             'first_name' => $data['first_name'],
//             'last_name' => $data['last_name'],
//             'email' => $data['email'],
//             'center_id' => $center->id,
//             'phone' => $data['phone'],
//             'verification_code' => rand(100000, 999999),
//         ]);

        
//        //email verification code to user .   

//        Mail::to($user->email)->send(new VerifyCenter($user->verification_code));

//       return response()->json($user, 200);
//       }else{
        
//          return response()->json(['errors' => 'Center Not Found'], 400);

//       }
   
//     } catch (\Exception $exception) {

//         return response()->json(['errors' => 'An error occurred ' . $exception], 500);
//     }

//  }  



public function claimCenter($data, $slug)
{
    try {
        return DB::transaction(function () use ($data, $slug) {
            $center = Center::where('slug', $slug)->first();

            if (!$center) {
                return response()->json(['errors' => 'Center Not Found'], 400);
            }

           

            $user = CentersClaim::create([
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' =>  $center->centerInfo->email,
                'center_id' => $center->id,
                'phone' => $data['phone'],
                'verification_code' => rand(100000, 999999),
            ]);

            // Email verification code to the user.
            Mail::to($user->email)->send(new VerifyCenter($user->verification_code));

            return response()->json($user, 200);
        });
    } catch (\Exception $exception) {
        return response()->json(['errors' => 'An error occurred ' . $exception], 500);
    }
}



 public function registerCenterProfile($data ,$cliam, $center, $location,  $user) {

   
   try{   
    $user = CenterAdmin::create([
        'location_id' => $location->id,
        'ghl_user_id' =>$user->id,
        'email' => $data['email'],
        'password' => Hash::make($data['password']), 
    ]);  
 



    $cliam->verified = true;
    $cliam->save();
    
    $center->claim = true;
    $center->center_admin_id = $user->id;
    $center->save();
   
    
    return response()->json($user, 200);


 } catch (\Exception $exception) {

    return response()->json(['errors' => 'An error occurred ' . $exception], 500);
 }
 
}

//upload profile

    public function uploadProfile($file, $directory)
    {
        // Your logic to update user profile data  

        try{ 


            $uniqueid=uniqid();
                $original_name=$file->getClientOriginalName();
                $size=$file->getSize();
                $extension=$file->getClientOriginalExtension();
                $name=time().'_'.$uniqueid.'.'.$extension;
               // $imagepath=url('/storage/app/parent_profiles/'.$name);
                $imagepath = storeFile($file);  
         
               



            $url =$imagepath;
            return $url;
            
        
        }catch (\Exception $exception) { 
            return response()->json(['errors' => 'An error occurred ' . $exception], 500);
        
        }

    } 

   

    
public function getApplications(){ 

    //get parent id

    $parent = Auth::user()->profile;

    $applications = ChildCareApplication::where('parent_id', $parent->id)->get();
    $applications =  ApplicationResource::collection($applications); 

    return apiResponse($applications, 'Applications fetched successfully', 200); 
 }
  

 public function getApplication($id){ 

    //get parent id

  

    $application = ChildCareApplication::find($id); 
    $application =   new ApplicationResource($application); 

    return apiResponse($application, 'Applications fetched successfully', 200); 
 }

}
