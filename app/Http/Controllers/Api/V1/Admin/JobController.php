<?php    
namespace App\Http\Controllers\Api\V1\Admin; 

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
use App\Http\Resources\V1\AdminJobResource;
use App\Models\FeatureJob;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Models\Skill;
use App\Models\Certification;
use App\Models\Immunisation;
use App\Models\Language;


 class JobController extends Controller
 {
    
    protected $jobService;

    public function __construct(JobService $jobService)
    {
        $this->jobService = $jobService;
    }



    public function getAdminJobs(){
        $jobs = Job::all();
        $data = AdminJobResource::collection($jobs);
        return apiResponse($data, 'success', 200);
    } 



    public function getJobDetails($slug){
         
        $job = Job::where('slug',$slug)->first();
        $data = new AdminJobResource($job);
        return apiResponse($data, 'success', 200);
    }

    public function updateJobImage(Request $request){
        $formData = $request->all();
        $job_id = $formData['job_id'];
        //validate job id
        $validated = Validator::make(['job_id'=>$job_id,'file'=> $formData['file']], [
            'job_id'=>'required|numeric|exists:App\Models\Job,id',
            'file' => 'required|file|mimes:jpg,jpeg,png,gif,bmp,tiff,webp|max:5120'
        ]);

        if($validated->fails()){
            return apiResponse(error_processor($validated),$validated->errors()->first());
        }
        $job = Job::where('id',$job_id)->first();
        if(empty($job)){
            return apiResponse([],'Job is not found',404);
        }

        //delete the existing update the profile image

        $file_path = $this->jobService->uploadFile($formData['file']);
        if($job->cover){
            if($file_path){
                
                $old_path = parse_url($job->cover)['path'];
                $old_path = ltrim($old_path, "/");
                delete($old_path);
            } else {
                return 'image could not be uploaded!'; 
            }
        }
        //update the data;
        $data['cover'] =  $file_path;
        $data['id'] =$job_id;

        $response = $this->jobService->EditJobImage($data);
        return $response;
        
    }

    public function add_featureJob(Request $request){

        $data = $request->all();
        $validate = Validator::make($data, [
            'feature_ids' => [
                'required',
                'array',
                'min:1',
                'distinct',
                'exists:App\Models\Feature,id', 
            ],

            'job_id'  => 'required|numeric|distinct|exists:App\Models\Job,id',
        ]);
    
        if ($validate->fails()) {
           
            return response()->json(['errors' => error_processor($validate)], 422);
        }

        $feature_ids = $data['feature_ids'];
        $feature_collection = new Collection();
        $ids = [];

        foreach ($feature_ids as $feature_id) {

            $existing_feature_job = DB::table('feature_job')
                ->where('feature_id', $feature_id)
                ->where('job_id', $data['job_id'])->first();
            
            if (empty($existing_feature_job)) {

                if ($featureJob = DB::table('feature_job')->insert(['feature_id'=>$feature_id, 'job_id'=>$data['job_id']])) {

                    $ids = array_push($ids, $existing_feature_job->id);
                    $feature_collection = $feature_collection->push($featureJob);

                }
            }
        }

        $feature_jobs = DB::table('feature_job')->get();

        foreach ($feature_jobs as $feature_job) {

            if (!in_array($feature_job->id, $ids)) {

                $feature_job->delete();

            }

        }
        
        return apiResponse($feature_collection,'Feature job successfull save',200);

    }


    public function add_jobRequirement(Request $request){

        $data = $request->all();
        $validate = Validator::make($data, [
            'skill_ids' => [
                'array',
                'min:1',
                'distinct',
                'exists:App\Models\Skill,id', 
            ],
            'certification_ids' => [
                'array',
                'min:1',
                'distinct',
                'exists:App\Models\Certification,id', 
            ],
            'immunisation_ids' => [
                'array',
                'min:1',
                'distinct',
                'exists:App\Models\Immunisation,id', 
            ],
            'language_ids' => [
                'array',
                'min:1',
                'distinct',
                'exists:App\Models\Language,id', 
            ],

            'job_id'  => 'required|numeric|distinct|exists:App\Models\Job,id',
        ]);
    
        if ($validate->fails()) {
           
            return response()->json(['errors' => error_processor($validate)], 422);
        }

        $data_collection = new Collection();

        if(isset($data['skill_ids']) and is_array($data['skill_ids'])) {
            $skill_collection = new Collection();
            $ids = [];

            foreach ($data['skill_ids'] as $skill_id) {

                $existing_job_skill = DB::table('job_skill')
                    ->where('skill_id', $skill_id)
                    ->where('job_id', $data['job_id'])->first();
            
                if (empty($existing_job_skill)) {

                    if (DB::table('job_skill')->insert(['skill_id'=>$skill_id, 'job_id'=>$data['job_id']])) {

                        $job_skill = DB::table('job_skill')->where('skill_id', $skill_id)
                            ->where('job_id', $data['job_id'])->first();

                        if($job_skill){
                            $ids[] = array_push($ids, $job_skill->id);
                            $skill_collection = $skill_collection->push($job_skill);
                        }

                    }
                }
            }

            $job_skills = DB::table('job_skill')->get();

            foreach ($job_skills as $job_skill) {

                if (!in_array($job_skill->id, $ids)) {
                    
                    DB::delete('DELETE FROM job_skill WHERE id = ?', [$job_skill->id]);

                }

            }

            $data_collection = $data_collection->push($skill_collection);
        
        }

        if(isset($data['certification_ids']) and is_array($data['certification_ids'])) {
            $certification_collection = new Collection();
            $ids = [];

            foreach ($data['certification_ids'] as $certification_id) {

                $existing_job_certification = DB::table('job_certification')
                    ->where('certification_id', $certification_id)
                    ->where('job_id', $data['job_id'])->first();
            
            if (empty($existing_job_certification)) {

                if ($job_certification = DB::table('job_certification')->insert(['certification_id'=>$certification_id, 'job_id'=>$data['job_id']])) {

                    $job_certification = DB::table('job_certification')->where('certification_id', $certification_id)
                        ->where('job_id', $data['job_id'])->first();

                    $ids[] = array_push($ids, $job_certification->id);
                    $certification_collection = $certification_collection->push($job_certification);

                }
            }
            }

            $job_certifications = DB::table('job_certification')->get();

            foreach ($job_certifications as $job_certification) {

                if (!in_array($job_certification->id, $ids)) {

                    DB::delete('DELETE FROM job_skill WHERE id = ?', [$job_certification->id]);

                }

            }

            $data_collection = $data_collection->push($certification_collection);
        
        }

        if(isset($data['immunisation_ids']) and is_array($data['immunisation_ids'])) {
            $immunisation_collection = new Collection();
            $ids = [];

            foreach ($data['immunisation_ids'] as $immunisation_id) {

                $existing_job_immunisation = DB::table('job_immunisation')
                    ->where('immunisation_id', $immunisation_id)
                    ->where('job_id', $data['job_id'])->first();
            
            if (empty($existing_job_immunisation)) {

                if ($job_immunisation = DB::table('job_immunisation')->insert(['immunisation_id'=>$immunisation_id, 'job_id'=>$data['job_id']])) {

                    $job_immunisation = DB::table('job_immunisation')->where('immunisation_id', $immunisation_id)
                        ->where('job_id', $data['job_id'])->first();

                    $ids[] = array_push($ids, $job_immunisation->id);
                    $immunisation_collection = $immunisation_collection->push($job_immunisation);

                }
            }
            }

            $job_immunisations = DB::table('job_immunisation')->get();

            foreach ($job_immunisations as $job_immunisation) {

                if (!in_array($job_immunisation->id, $ids)) {

                    DB::delete('DELETE FROM job_skill WHERE id = ?', [$job_immunisation->id]);

                }

            }

            $data_collection = $data_collection->push($immunisation_collection);
        
        }

        if(isset($data['language_ids']) and is_array($data['language_ids'])) {
            $language_collection = new Collection();
            $ids = [];

            foreach ($data['language_ids'] as $language_id) {

                $existing_job_language = DB::table('job_language')
                    ->where('language_id', $language_id)
                    ->where('job_id', $data['job_id'])->first();
            
            if (empty($existing_job_language)) {

                if ($job_language = DB::table('job_language')->insert(['language_id'=>$language_id, 'job_id'=>$data['job_id']])) {

                    $job_language = DB::table('job_language')->where('language_id', $language_id)
                        ->where('job_id', $data['job_id'])->first();

                    $ids[] = array_push($ids, $job_language->id);
                    $language_collection = $language_collection->push($job_language);

                }
            }
            }

            $job_languages = DB::table('job_language')->get();

            foreach ($job_languages as $job_language) {

                if (!in_array($job_language->id, $ids)) {

                    DB::delete('DELETE FROM job_skill WHERE id = ?', [$job_language->id]);

                }

            }

            $data_collection = $data_collection->push($language_collection);
        
        }

        if (!(isset($data['skill_ids']) || isset($data['certification_ids']) || isset($data['immunisation_ids']) || isset($data['language_ids']))) {

            return apiResponse([],'No job requirement to save',404);
        }
        
        return apiResponse($data_collection,'job requirements successfull save',200);

    }

    public function add_jobBenefit(Request $request){

        $data = $request->all();
        $validate = Validator::make($data, [

            'benefits' => 'required|array',
            'job_id'  => 'required|numeric|distinct|exists:App\Models\Job,id',

        ]);
    
        if ($validate->fails()) {
           
            return response()->json(['errors' => error_processor($validate)], 422);
        }


        if(isset($data['benefits']) and is_array($data['benefits'])) {
            
            $data['benefits'] = json_encode($data['benefits']);
        
        }

        $job = Job::find($data['job_id']);

        if($job) {

            $job->benefits = $data['benefits'];
            $job->save();

            return apiResponse($job,'job benefits successfull save',200);

        }

        return apiResponse([],'No job benefit to save',404);

    }

    public function update_jobTitle(Request $request){

        $data = $request->all();
        
        $validate = Validator::make($data, [

            'title' => 'string',
            'job_info' => 'string',
            'job_type' => 'string',
            'pay_type' => 'string',
            'service_to_render' => 'string',
            'job_id'  => 'required|numeric|distinct|exists:App\Models\Job,id',

        ]);
    
        if ($validate->fails()) {
           
            return response()->json(['errors' => error_processor($validate)], 422);
        }


        $job = Job::find($data['job_id']);

        if ($job) {
            if(isset($data['title']))   $job->title = $data['title'];
            if(isset($data['job_info']))   $job->job_info = $data['job_info'];
            if(isset($data['job_type']))   $job->job_type = $data['job_type'];
            if(isset($data['pay_type']))   $job->pay_type = $data['pay_type'];
            if(isset($data['service_to_render']))   $job->service_to_render = $data['service_to_render'];

            $job->save();


            return apiResponse($job,'job successfull updated',200);
        }

        return apiResponse([],'No job benefit to update',404);
        

    }


}




