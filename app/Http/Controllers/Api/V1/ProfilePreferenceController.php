<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\ParentProfile;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\Country;
use App\Models\City;
use App\Models\State;
use App\Models\JobProfile;
use App\Models\JobProfilePreference;
use Illuminate\Support\Facades\Auth;
use App\Services\PreferenceService;
use Illuminate\Validation\Rule;

class ProfilePreferenceController extends Controller
{
    protected $preferenceService;

    public function __construct(PreferenceService $preferenceService)
    {
        $this->preferenceService = $preferenceService;
    }

    // add a new profile preference
    public function createPreference(Request $request) {
        $data = $request->only(
            'jobs_interested', 
            'companies_selection', 
            'salary', 
            'distance_covered',
            'start_type',
            'start_date',
            'days',
            'jobs'
        );

        $validated = Validator::make($data, [
            'jobs_interested' => 'array',
            'companies_selection' => 'array',
            'salary' => 'array',
            'distance_covered' => 'integer|min:0',
            'start_type' => 'string|nullable',
            'start_date' => 'date|after:yesterday|nullable',
            'days' => 'array',
            'jobs' => 'array',
        ]);

        if($validated->fails()){
            return apiResponse(error_processor($validated), error_processor($validated)[0]['message']); 
 
        }

        $user = Auth::user();
        if (empty($user)) {
            return apiResponse([], 'User  not found or user not logged', 404); 
        }
        $job_profile = $user->jobProfile;
        if (empty($job_profile)) {
            return apiResponse([], 'Job profile  not found', 404);
        }
        
        $data = array_merge($data, ['job_profile_id' => $job_profile->id]);
        $data['jobs_interested'] =isset($data['jobs_interested'])?$data['jobs_interested']:null;
        

        $data['companies_selection'] = isset($data['companies_selection'])?$data['companies_selection']:null;
        $data['salary'] = isset($data['salary'])?$data['salary']:null;
        $data['distance_covered'] = isset($data['distance_covered'])?$data['distance_covered']:0;
        $data['start_type'] = isset($data['start_type'])?$data['start_type']:null;
        $data['start_date'] = isset($data['start_date'])?$data['start_date']:null;
        $data['days'] = isset($data['days'])?$data['days']:null;
        $data['jobs'] = isset($data['jobs'])?$data['jobs']:null;


        $response = $this->preferenceService->addProfilePreference($data); 
        return $response;
    }

    // update existing profile preference
    public function updatePreference(Request $request, $id) {
        $data = $request->only(
            'jobs_interested', 
            'companies_selection', 
            'salary', 
            'distance_covered',
            'start_type',
            'start_date',
            'days',
            'jobs'
        );
        // 'jobs_interested', 
        //     'companies_selection', 
        //     'salary', 
        //     'distance_covered',
        //     'start_type',
        //     'start_date',
        //     'days',
        //     'jobs'

        $JobProfilePreference = JobProfilePreference::find($id);
        if(empty($JobProfilePreference)) {
            return apiResponse([], 'profile Preference not found', 409); 
        }

        $validated = Validator::make($data, [
            'jobs_interested' => 'array',
            'companies_selection' => 'array',
            'salary' => 'array',
            'distance_covered' => 'integer|min:0',
            'start_type' => 'string|nullable',
            'start_date' => 'date|nullable',
            'days' => 'array',
            'jobs' => 'array',
        ]);

        if($validated->fails()){

            return apiResponse(error_processor($validated), error_processor($validated)[0]['message']); 
 
        }

        $user = Auth::user();
        if (empty($user)) {
            return response()->json(['errors' => 'User  not found or user not logged'], 404); 
        }
        $job_profile = $user->jobProfile;
        if (empty($job_profile)) {
            return response()->json(['errors' => 'Job profile  not found'], 404);
        }
        $data = array_merge($data, ['id' => $id, 'job_profile_id' => $job_profile->id]);
        $data['jobs_interested'] =isset($data['jobs_interested'])?$data['jobs_interested']:null;
        

        $data['companies_selection'] = isset($data['companies_selection'])?$data['companies_selection']:null;
        $data['salary'] = isset($data['salary'])?$data['salary']:null;
        $data['distance_covered'] = isset($data['distance_covered'])?$data['distance_covered']:0;
        $data['start_type'] = isset($data['start_type'])?$data['start_type']:null;
        $data['start_date'] = isset($data['start_date'])?$data['start_date']:null;
        $data['days'] = isset($data['days'])?$data['days']:null;
        $data['jobs'] = isset($data['jobs'])?$data['jobs']:null;

        $response = $this->preferenceService->updateProfilePreference($data); 
        return $response;
    }

    
    //get all  profile_preferences and get one by id
    public function profile_preference(Request $request, $id=null) {

        if (isset($id)) {
            $profile_preference = JobProfilePreference::find($id);
            if ($profile_preference) {

                return apiResponse($profile_preference,  'success', 201);

            } else {
                return apiResponse([], 'Profile preference not found', 404); 
            }  
        }
        $user = Auth::user();
        if (empty($user)) {
            return apiResponse([], 'User  not found or user not logged', 404); 
        }
        $job_profile = $user->jobProfile;
        if ($job_profile) {
            $profile_preferences = JobProfilePreference::where('job_profile_id', $job_profile->id)->get();

            return apiResponse($profile_preferences,  'success', 201);
            
        } else {
            return apiResponse([], 'Job profile preference not deleted', 404); 
        }
    }

    //delete an existing profile_preference 
    public function delete_preference(Request $request, $id) {

        $preference = JobProfilePreference::find($id);

        if ($preference->delete()) {

            return apiResponse([], 'Profile preference deleted successful', 201);
        } else {
            return apiResponse([], 'Profile preference not deleted', 404); 
        }
    }

       
}
