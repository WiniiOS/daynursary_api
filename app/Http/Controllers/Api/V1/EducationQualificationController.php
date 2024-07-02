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
use App\Models\JobType;
use App\Models\JobRole;
use App\Models\ProfileEducation;
use Illuminate\Support\Facades\Auth;
use App\Services\EducationService;
use Illuminate\Validation\Rule;

class EducationQualificationController extends Controller
{
    protected $educationService;

    public function __construct(EducationService $educationService)
    {
        $this->educationService = $educationService;
    }

    // add a new profile Education 
    public function createEducation(Request $request) {
        $data = $request->only(
            'qualification', 
            'start_date', 
            'end_date',
            'description',
            'currently_studying',
            'field_of_study',
            'school'
        );

        $validated = Validator::make($data, [
            'qualification' => 'required|string|min:3',
            'start_date' => 'required|date_format:Y-m-d',
            'end_date' => 'date_format:Y-m-d|nullable',
            'currently_studying' => 'boolean',
            'description' => 'string|min:10|nullable',
            'field_of_study'  => 'string|min:3|nullable',
            'school'  => 'required|string|min:3',
        ]);

        if($validated->fails()){

            return apiResponse(error_processor($validated),$validated->errors()->first(),200);
 
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


        $data['end_date'] = isset($data['end_date'])?$data['end_date']:null;
        $data['currently_studying'] = isset($data['currently_studying'])?$data['currently_studying']:false;
        $data['description'] = isset($data['description'])?$data['description']:null;
     
        $response = $this->educationService->addEducation($data);
        return $response;
    }

    // update existing profile education
    public function updateEducation(Request $request, $id) {
        $data = $request->only(
            'qualification', 
            'start_date', 
            'end_date',
            'description',
            'currently_studying',
            'field_of_study',
            'school'
        );

        $profileEducation = ProfileEducation::find($id);
        if(empty($profileEducation)) {
            return apiResponse([],'profile Education not found', 404); 
        }
        info($data);
        $validated = Validator::make($data, [
            'qualification' => 'string|min:3',
            'start_date' => 'date_format:Y-m-d',
            'end_date' => 'date_format:Y-m-d|nullable',
            'currently_studying' => 'boolean',
            'description' => 'string|min:10|nullable',
            'field_of_study'  => 'string|min:3|nullable',
            'school'  => 'string|min:3',
        ]);

        if($validated->fails()){

            return apiResponse(error_processor($validated),$validated->errors()->first(),200);
 
        }

        $user = Auth::user();
        if (empty($user)) {
            return apiResponse([], 'User  not found or user not logged', 404); 
        }
        $job_profile = $user->jobProfile;
        if (empty($job_profile)) {
            return apiResponse([], 'Job profile  not found', 404);
        }
        $data = array_merge($data, ['id' => $id, 'job_profile_id' => $job_profile->id]);


        $data['qualification'] = isset($data['qualification'])?$data['qualification']:$profileEducation->qualification;
        $data['start_date'] = isset($data['start_date'])?$data['start_date']:$profileEducation->start_date;
        $data['end_date'] = isset($data['end_date'])?$data['end_date']:$profileEducation->end_date;
        $data['currently_studying'] = isset($data['currently_studying'])?$data['currently_studying']:$profileEducation->currently_studying;
        $data['description'] = isset($data['description'])?$data['description']:'';
        $data['field_of_study'] = isset($data['field_of_study'])?$data['field_of_study']:$profileEducation->field_of_study;
        $data['school'] = isset($data['school'])?$data['school']:$profileEducation->school;

        $response = $this->educationService->updateProfileEducation($data); 
        return $response;
    }

    //get all  profile_educations and get one by id
    public function profile_education(Request $request, $id=null) {

        if (isset($id)) {
            $profile_education = ProfileEducation::find($id);
            if ($profile_education) {

                return apiResponse($profile_education,  'success', 201);

            } else {
                return apiResponse([], 'Profile Education not found', 404); 
            }  
        }
        $user = Auth::user();
        if (empty($user)) {
            return apiResponse([], 'User  not found or user not logged', 404); 
        }
        $job_profile = $user->jobProfile;
        if ($job_profile) {
            $profile_educations = ProfileEducation::where('job_profile_id', $job_profile->id)->get();

            return apiResponse($profile_educations,  'success', 201);
            
        } else {
            return apiResponse([], 'Job profile not deleted', 404); 
        }
    }

    //delete an existing profile_education 
    public function delete_education(Request $request, $id) {

        $education = ProfileEducation::find($id);

        if ($education->delete()) {

            return apiResponse([], 'success', 201);
        } else {
            return apiResponse([], 'Profile Education not deleted', 404); 
        }
    }


}