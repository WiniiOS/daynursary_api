<?php 
namespace App\Services;
use App\Models\Country;
use App\Models\City;
use App\Models\State;
use App\Models\ParentProfile;
use App\Models\ParentChild;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\User;
use App\Models\Center;
use App\Models\Job;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\V1\CenterResource;
use App\Http\Resources\V1\CertificationResource;
use App\Http\Resources\V1\ImmunisationResource;
use App\Http\Resources\V1\JobResource;
use App\Http\Resources\V1\JobApplicationResource;
use App\Http\Resources\V1\LanguageResource;
use App\Http\Resources\V1\Skill_Resource;
use App\Models\Certification;
use App\Models\Feature;
use App\Models\Immunisation;
use App\Models\JobApplication;
use App\Models\JobProfileFavouriteJob;
use App\Models\JobProfileViewedJob;
use App\Models\Language;
use App\Models\Skill;
use App\Traits\Transformer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\FlareClient\Api;

class JobService
{   
   




 public function featuredJobs($limit=10 ,$offset=1) { 
   
    $jobs = Job::orderBy('created_at', 'asc')->paginate($limit, ['*'], 'page', $offset); 
     
    $data =  [
        'total_size' => $jobs->total(),
        'limit' => $limit,
        'offset' => $offset,
        'jobs' =>JobResource::collection($jobs)
    ];
    
    return response()->json($data, 200);

 }   


 public function searchJobs($search) {  

    if (empty($search)) {
        
        $jobs = Job::orderBy('created_at', 'desc')->get();
    }else{
        $jobs = Job::where('name','like','%'.$search.'%')->get();
    }

   


    

    $data =  [
        'jobs' =>JobResource::collection($jobs)
    ];
    
    return response()->json($data, 200);

 }


 public function getJob($slug) {
    $job = Job::where('slug', $slug)->first();

    if (!$job) {
        return response()->json(['error' => 'job not found'], 404);
    }

    $data =  [
        'job' => new JobResource($job),
    ];

    return response()->json($data, 200);
}




public function getJobDetail($slug) {
   
    $job = Job::where('slug', $slug)->first();
    
    
    $job = Job::jobWithRelationships( $job->id) ;
   

    if (!$job) {
        return response()->json(['error' => 'Job not found'], 404);
    }

    $data =  [
        'job' => new JobResource($job),
    ];

    return response()->json($data, 200);
}


//apply for a job   

public function applyJob($job){ 

  //get the auth user job profile 
  $jopProfile = Auth::User()->jobProfile()->first();
 
  // Create a new job application for the job profile  

  $existingApplication = $jopProfile->jobApplication()->where('job_id', $job->id)->first();

  if ($existingApplication) {
    // User has already applied for this job
     $data =  new JobApplicationResource($existingApplication); 
     return apiResponse($data, 'Successfully found Job application', 200);

    }




 $newJobApplication = $jopProfile->jobApplication()->create([
    'job_id' => $job->id,
    'status' => 'pending',
    
]);  


//get the job details  

    $jobapplication = JobApplication::find($newJobApplication->id); 
    $data =  new JobApplicationResource($jobapplication); 
    

    return apiResponse($data, 'Successfully found Job application', 200);


   
} 

 
//get all applied jobs  

public function getApplyJobDetails($id) {
    
    $jobapplication = JobApplication::find($id); 
   
    if (!$jobapplication) {
          return apiResponse([], 'Job not found', 404);
    }else{
        
        $data =  new JobApplicationResource($jobapplication); 
       
        return apiResponse($data, 'Successfully found Job application', 200);
    }  
} 


//get applied job details

public function getAppliedJobs($request) { 


    //get the paginate params  

    $limit = $request->limit ?? 5; 
    $offset = $request->offset ?? 1; 

    //get the auth user job profile 
    $jopProfile = Auth::User()->jobProfile()->first();
  
    $jobapplications = $jopProfile->jobApplication()->paginate($limit);

    $searchValues = array(
        'filter' => $request->filter,
        'page' => $request->page,
    ); 

    $meta = Transformer::transformCollection($jobapplications);
    
    
    $data =  JobApplicationResource::collection($jobapplications);  
    return apiResponse($data, 'Applied Jobs', 200, $meta, $searchValues);
   
   
} 
    //add fav 

    public function addFavourite($data){
        try{ 

            $profile = JobProfileFavouriteJob::create($data);
            
            return apiResponse(['job added as fav'],'success');


        }catch (\Exception $exception) {
            return response()->json(['errors' => 'An error occurred ' . $exception], 500);
        }
    }
    public function deleteFavourite($id){
    

            try{
                $fav = JobProfileFavouriteJob::findOrFail($id);
            
                $fav->delete();
                return response([ 'message' => 'job updated successfully'], 200);
            
                
            }catch(ModelNotFoundException $exception){
            
                return response([ 'errors' => 'fav not found', 'message' => 'fav not found'], 400);
                
            }catch (\Exception $exception) {
            
                return response([ 'errors' => 'An error occurred', 'message' => 'An error occurred'], 500); 
            }
        
    }
    //viewed jobs
    public function addView($data){
        try{ 

            $profile = JobProfileViewedJob::create($data);
            
            return response()->json([
                'status'=>'success',
                'message' => 'job successfully updated',
                'profile' => $profile
            ], 201); 


        }catch (\Exception $exception) {
            return response()->json(['errors' => 'An error occurred ' . $exception], 500);
        }
    }
    public function deleteView($id){
    

        try{
            $viewed = JobProfileFavouriteJob::findOrFail($id);
        
            $viewed->delete();
            return response([ 'message' => 'job viewed deleted successfully'], 200);
        
            
        }catch(ModelNotFoundException $exception){
        
            return response([ 'errors' => 'viewed job not found', 'message' => 'viewed job not found'], 400);
            
        }catch (\Exception $exception) {
        
            return response([ 'errors' => 'An error occurred', 'message' => 'An error occurred'], 500); 
        }
    
    }

    public function uploadFile($file){
        try{
            $file_path = storeFile($file);
            $link = env("AWS_URL").'/'.$file_path;
            return $link;
            
        }
        catch(\Exception $exception){
            return null;
            info($exception);
            
        }
       
    }
    protected function formatFeatures($features){
        
        $groupedFeatures = [];

        foreach ($features as $feature) {
            // Check if the feature is a parent
            if ($feature['type'] == 'parent') {
                $groupedFeature = [
                    'id'=>$feature['id'],
                    'icon' => $feature['image'],
                    'title' => $feature['name'],
                    'tab' => [],
                ];

                // Find children for the parent
                $children = array_filter($features, function ($child) use ($feature) {
                    return $child['parent_feature_slug'] == $feature['slug'];
                });

                // Organize children data
                foreach ($children as $child) {
                    $groupedFeature['tab'][] = [
                        'id' => $child['id'],
                        'icon' => $child['image'],
                        'text' => $child['name'],
                    ];
                }

                $groupedFeatures[] = $groupedFeature;
            }
        }

       return $groupedFeatures;
    }

    public function getJobFeaturesImmuSkillsCertifs(){
        try{
             $Immunisations = Immunisation::all();
             $Skills = Skill::all();
             $Certifications = Certification::all();
             $Languages = Language::all();
             $features_eloquent = Feature::where('for','job')->get();
             $features = [];
             if($features_eloquent){
                 $features=  $this->formatFeatures($features_eloquent->toArray());
             }
 
             $data = [
                 'immunisations'=>ImmunisationResource::collection($Immunisations),
                 'skills'=>Skill_Resource::collection($Skills),
                 'certifications'=>CertificationResource::collection($Certifications),
                 'features'=>$features,
                 'languages'=>LanguageResource::collection($Languages)
             ];
 
             return apiResponse($data,'success',200);
        }
        catch(\Exception $exception){
             info($exception);
             return apiResponse([],'An unexpected error occured',500);
        }
 
     }

     public function createJob($saving_data,$data){

        try{
            $job = Job::create($saving_data);

            if($job){

                try{
                        //create certifications
                    if(isset($data['certifications'])){
                        //create certifications
                        foreach ($data['certifications'] as $certification) {
                            DB::table('job_certification')->insert(['job_id'=>$job->id,'certification_id'=>$certification]);
                        }
                    }
                    if(isset($data['feature_array'])){

                        //create features
                        info($data['feature_array']);
                        foreach ($data['feature_array'] as $feature) {
                            info($feature);
                            DB::table('job_feature')->insert(['job_id'=>$job->id,'feature_id'=>$feature[0]]);
                        }
                    }
                    if(isset($data['skills'])){

                        //create skills
                        foreach($data['skills'] as $skill){
                            DB::table('job_skill')->insert(['job_id'=>$job->id,'skill_id'=>$skill]);
                        }
                    }
                    if(isset($data['immunisation'])){
                        //create immunisations
                        foreach($data['immunisation'] as $immunisation){
                            DB::table('job_immunisation')->insert(['job_id'=>$job->id,'immunisation_id'=>$immunisation]);
                        }
                    }
                    if(isset($data['language'])){
                        //create languages
                        foreach($data['language'] as $immunisation){
                            DB::table('job_language')->insert(['job_id'=>$job->id,'language_id'=>$immunisation]);
                        }
                        
                    }

                }catch(\Exception $exception){
                    info($exception);
                    $job->delete();
                    return apiResponse([],'An unexpected error occured',500);
                }

            }
            return apiResponse([],'success',200);

        }catch(\Exception $exception){
            info($exception);
            return apiResponse([],'An unexpected error occured');
        }
        
    }

    public function getAllJobs(){
        try{
             $jobs = Job::all();
 
             return apiResponse(JobResource::collection($jobs),'success',200);
        }catch(\Exception $exception){
             info($exception);
             return apiResponse([],'An unexpected error occured');
        }
 
    }

    public function EditJobImage($data){
        try{
            $job = Job::where('id',$data['id'])->first();
            if(empty($job)){
                return apiResponse([],'job not found',404);
            }
            if($job->update(['cover'=>$data['cover']])){
                return apiResponse(['job profile updated successfully'],'success');

            }else{
                return apiResponse([],'job profile image could not be updated');
            }


        }catch(\Exception $exception){
            info($exception);
            return apiResponse([],'an unexpected error occured');
        }

    }

}
 