<?php    
 
 namespace App\Http\Controllers\Api\V1\Job; 

 use App\Http\Controllers\Controller;
use App\Http\Resources\v1\JobFavouriteResource;
use App\Models\Center;
 use App\Models\Job;
use App\Models\JobProfileFavouriteJob;
use App\Models\JobProfileViewedJob;
use Illuminate\Http\Request;
 use Illuminate\Support\Facades\Validator;
 use Illuminate\Validation\Rule;
 use App\Services\JobService;
use Illuminate\Support\Facades\Auth;


 class JobController extends Controller
 {
    
    protected $jobService;

    public function __construct(JobService $jobService)
    {
        $this->jobService = $jobService;
    }


  
    
    

    public function getFeaturedJobs()
    {
        $response = $this->jobService->featuredJobs();
        return  $response;
    } 





    public function getJob($slug){

        $response = $this->jobService->getJob($slug);
        return  $response;
    } 



    public function jobDetails($slug){
        $response = $this->jobService->getJobDetail($slug);
        return  $response; 
    }
    

    public function applyJob($id){
        //find the job 
        $job = Job::find($id);
        if($job){
            $response = $this->jobService->applyJob($job);
            return  $response; 
        } 

        return apiResponse([], 'job not found', 404);
       
    } 
   


    public function getApplyJobs(Request $request){

        //get all jobs 
           
            $response = $this->jobService->getAppliedJobs($request);
            return  $response;  
            
    }

    

    public function getApplyJobDetails($id){

        //get all jobs 
     
            $response = $this->jobService->getApplyJobDetails($id);
            return  $response;  
            
    }


    //add fav job
     public function addFavJob(Request $request){
        $data = $request->all();
        $validated = Validator::make($data, [
            'job_id' => 'required|numeric|distinct|exists:App\Models\Job,id',
        ]);

        if ($validated->fails()) {

            return apiResponse( error_processor($validated), $validated->errors()->first(), 422);
        }

        $user = Auth::user();
        if (empty($user)) {
            return apiResponse([],'User  not found or user not logged',404);
        }
        $job_profile = $user->jobProfile;

       
        if(empty($job_profile)){
            return apiResponse([],'User  not found or user not logged',404);
        }
        
         //check if already saved
         $existingFavorite = JobProfileFavouriteJob::where('jobprofile_id', $job_profile->id)
         ->where('job_id', $data['job_id'])
         ->first();
        if ($existingFavorite) {
                $existingFavorite->delete();
               return apiResponse(['the job has been removed as fav'],'success',200);
        }

        
        $id = $job_profile->id;
        $data = array_merge($data, ['jobprofile_id' => $id]);

        $response = $this->jobService->addFavourite($data);

        return $response;


     }
     public function deleteFavJob($id){
        $response = $this->jobService->deleteFavourite($id);

        return  $response; 
     }

     public function addViewedJob(Request $request){
        $data = $request->all();
        $validated = Validator::make($data, [
            'job_id' => 'required|numeric|distinct|exists:App\Models\Job,id',
        ]);

        if ($validated->fails()) {

            return apiResponse( error_processor($validated), $validated->errors()->first(), 422);
        }

        $user = Auth::user();
        if (empty($user)) {
            return apiResponse([],'User  not found or user not logged',404);
        }
        $job_profile = $user->jobProfile;
        //check if already saved
        $existingFavorite = JobProfileViewedJob::where('jobprofile_id', $job_profile->id)
                ->where('job_id', $data['job_id'])
                ->first();

        if ($existingFavorite) {
               return apiResponse([],'The job is already saved as a viewed',409);
        }
        if(empty($job_profile)){
            return apiResponse([],'User  not found or user not logged',404);
        }
        $id = $job_profile->id;
        $data = array_merge($data, ['jobprofile_id' => $id]);

        $response = $this->jobService->addView($data);

        return $response;

     }

     public function deleteViewedJob($id){
        $response = $this->jobService->deleteView($id);

        return  $response; 

     }

     //get user fav jobs
     public function getUserFavJobs(){
        $user = Auth::user();
        if (empty($user)) {
            return apiResponse([],'User  not found or user not logged',404);
        }
        $job_profile = $user->jobProfile;
        if(empty($job_profile)){
            return apiResponse([],'User  not found or user not logged',404);
        }

        $favoriteJobs = $job_profile->JobProfileFavouriteJobs()->with('Job.center')->get();
        $data = JobFavouriteResource::collection($favoriteJobs);

        return apiResponse($data,'success',200);
     }

     public function getUserViewedJobs(){
        $user = Auth::user();
        if (empty($user)) {
            return apiResponse([],'User  not found or user not logged',404);
        }
        $job_profile = $user->jobProfile;
        if(empty($job_profile)){
            return apiResponse([],'User  not found or user not logged',404);
        }
        
        $viewedJobs = $job_profile->JobProfileViewedJobs()->with('Job.center')->get();
        $data = JobFavouriteResource::collection($viewedJobs);

        return apiResponse($data,'success',200);

     }


     //get job features(get the features and arrange it in parent and child)
     public function getFeatCertSkillImmu(){

        $response = $this->jobService->getJobFeaturesImmuSkillsCertifs();
        return $response;
     }

     protected function createSlug($job_title){
        // Convert the title to lowercase
        $slug = strtolower($job_title);

        // Replace spaces with underscores
        $slug = str_replace(' ', '_', $slug);

        // Remove special characters and keep only alphanumeric and underscore
        $slug = preg_replace('/[^a-z0-9_]/', '', $slug);
        //$id = mt_rand(100000, 999999);
        $id  = date('YmdHis');
        $slug .= '_'.$id ;
        return $slug;
    }

    public function addJob(Request $request){
        $formData = $request->all();
        $file = null;
        $data = json_decode($formData['form'],true);
        info($data);

        $validated = Validator::make($data, [
            'benefits'=>'array',
            'certifications'=>'array',
            'startDate'=>'date|after:yesterday|nullable',
            'dueDate'=>'date|after:yesterday|nullable',
            'eligibility'=>'array',
            'feature_array'=>'array',
            'immunisation'=>'array',
            'job_description'=>'required|string|min:3',
            'job_title'=>'required|string|min:3',
            'job_type'=>'required|string|min:3',
            'language'=>'array',
            'pay_type'=>'string|min:3',
            'salary'=>'array',
            'skills'=>'array',
            'center_id'=>'required|numeric|exists:App\Models\Center,id',
            'about'=>'required|string|min:3'
        ]);


        if($validated->fails()){
            return apiResponse(error_processor($validated),$validated->errors()->first());
        }

        $saving_data['center_id'] = isset($data['center_id'])?$data['center_id']:null;
        $saving_data['title'] = isset($data['job_title'])?$data['job_title']:null;
        $saving_data['job_type'] = isset($data['job_type'])?$data['job_type']:null;
        $saving_data['job_info'] = isset($data['about'])?$data['about']:null;
        $saving_data['service_to_render'] = isset($data['job_description'])?$data['job_description']:null;
        $saving_data['start_date'] = isset($data['startDate'])?$data['startDate']:null;
        $saving_data['min_pay'] = isset($data['salary'][0])?$data['salary'][0]:0;
        $saving_data['max_pay'] = isset($data['salary'][1])?$data['salary'][1]:0;
        $saving_data['pay_type'] = isset($data['pay_type'])?$data['pay_type']:null;
        $saving_data['work_eligibility'] = isset($data['eligibility'])?$data['eligibility']:[];
        $saving_data['due_date'] = isset($data['dueDate'])?$data['dueDate']:null;
        $saving_data['benefits'] = isset($data['benefits'])?$data['benefits']:[];
        $saving_data['slug'] = isset($data['job_title'])?$this->createSlug($data['job_title']):null;
        $saving_data['slug'] = isset($data['job_title'])?$this->createSlug($data['job_title']):null;

        //manage file
        if(isset($formData['cover_image'])){
            $file = $formData['cover_image'];
            //validate file
            $validator = Validator::make(['file' => $file], [
                'file' => 'required|file|mimes:jpg,jpeg,png,gif,bmp,tiff,webp|max:5120',
            ]);
            if($validator->fails()){
                return apiResponse(error_processor($validator),$validator->errors()->first());
            }

            $link = $this->jobService->uploadFile($file);
            if($link){
                $saving_data['cover'] = $link;
            }else{
                return apiResponse([],'image could not be uploaded..');
            }
            

        }
    
        //create a job
        $response = $this->jobService->createJob($saving_data,$data);
        return $response;

        
        //
        // $data['companies_selection'] = isset($data['companies_selection'])?$data['companies_selection']:null;
        // $data['salary'] = isset($data['salary'])?$data['salary']:null;
        // $data['distance_covered'] = isset($data['distance_covered'])?$data['distance_covered']:0;
        // $data['start_type'] = isset($data['start_type'])?$data['start_type']:null;
        // $data['start_date'] = isset($data['start_date'])?$data['start_date']:null;
        // $data['days'] = isset($data['days'])?$data['days']:null;
        // $data['jobs'] = isset($data['jobs'])?$data['jobs']:null;

        // 'title',  'cover', 'job_type', 'job_info', 'service_to_render', 'start_date',
        // 'min_pay','center_id', 'max_pay', 'pay_type', 'work_eligibility','due_date','benefits'

        // benefits{},certifications[],startDate,dueDate,eligibility{},feature_array[],immunisation[],job_description,job_title,job_type,language[],pay_type,salary[],skills[],
        // 'min_pay','center_id', 'max_pay', 'pay_type', 'about_applicant', 'language', 'eligibility','about',

    }

    public function getJobs(){
        $response = $this->jobService->getAllJobs();
        return $response;
    }
}




